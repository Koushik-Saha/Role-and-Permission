<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\ManPower;
use App\Models\ProjectLogs;
use App\Models\Projects;
use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use LaravelFullCalendar\Facades\Calendar;

class ManPowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function addManPower()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/manpower/add-manpower", 'name' => "Manpower"], ['name' => "Add Manpower"]
        ];

        $projects = Projects::all();

        $role = Role::all();

        return view('front-end.manpower.add-manpower')->with([
            'breadcrumbs' => $breadcrumbs,
            'projects' => $projects,
            'role' => $role,
        ]);
    }

    public function processManPower(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'fathers_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email'],
            'username' => ['required'],
            'address' => ['required'],
            'password' => ['required','min:8'],
            'salary' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        $manpower = new User();

        $manpower->role_id = $request->post('role_id');
        $manpower->name = $request->post('name');
        $manpower->fathers_name = $request->post('fathers_name');
        $manpower->email = $request->post('email');
        $manpower->username = $request->post('username');
        $manpower->mobile = $request->post('mobile');
        $manpower->address = $request->post('address');
        $manpower->email_verified_at = Carbon::now();
        $manpower->password = Hash::make($request->post('password'));
        $manpower->image = $request->post('image');
        $manpower->can_login = $request->post('can_login');
        $manpower->salary = $request->post('salary');
        $manpower->note = $request->post('note');
        $manpower->status = $request->post('status');
        $manpower->role_id = $request->post('role_id');
        $manpower->cover_image = $request->post('cover_image');
        $manpower->fb_url = $request->post('fb_url');
        $manpower->instagram_url = $request->post('instagram_url');
        $manpower->section = $request->post('section');

        $manpower->save();

        Helper::addActivity('manpower', $manpower->id, 'Staff Created');

        $project = Projects::findOrFail($request->post('project_id'));
        $pLogs = new ProjectLogs();

        $pLogs->pl_project_id = $project->project_id;
        $pLogs->pl_user_id = $manpower->id;

        if($pLogs->save()) {
            Helper::addActivity('project', $project->project_id, 'Project Assigned');
            Helper::addActivity('user', $manpower->id, 'Project Assigned');
            return Helper::redirectUrlWithNotification(route('staff-list'), 'success', 'Staff Successfully Created & Assigned to the Project.');
        }

        return Helper::redirectBackWithNotification();

    }

    public function addDesignation()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/manpower/add-designation", 'name' => "Designation"], ['name' => "Add Designation"]
        ];

        $role = Role::all();

        return view('front-end.manpower.add-designation')->with([
            'breadcrumbs' => $breadcrumbs,
            'role' => $role,
        ]);
    }

    public function processDesignation(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'role_name' => ['required', 'string', 'max:191'],
            'role_slug' => ['required', 'string', 'max:191'],
        ]);

        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        $role = new Role();

        $role->role_name = $request->post('role_name');
        $role->role_slug = $request->post('role_slug');

        $role->save();

        Helper::addActivity('role', $role->role_id, 'Role Created!');

        return Helper::redirectBackWithNotification('success', 'Role Successfully Created!');

    }

    public function deleteDesignation(Request $request) {
        $role = Role::findOrFail($request->post('id'));

        if($role->users->count() > 0) {
            return Helper::redirectBackWithNotification('error', 'You can\'t Delete ' . $role->role_name . ' because It has staffs assigned in!');
        }

        if(!$role->delete()) {
            return Helper::redirectBackWithNotification();
        }

        Helper::addActivity('role', $role->role_id, 'Role Deleted!');

        return Helper::redirectBackWithNotification('success', 'Designation successfully deleted!');
    }

    public function staffList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/manpower/staff-list", 'name' => "Staff List"], ['name' => "Add Staff List"]
        ];

        $projects = Projects::all();

        return view('front-end.manpower.staff-list')->with([
            'breadcrumbs' => $breadcrumbs,
            'projects' => $projects,
        ]);
    }

    public function searchStaff(Request $request) {
        $project = Projects::findOrFail($request->post('pid'));

        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $staffs = $project->users()
            ->whereIn('role_id', $roles)
            ->get();

        return view('front-end.manpower.ajax-labours-list')
            ->with([
                'staffs'    => $staffs,
                'project'   => $project
            ]);
    }

    public function changeStaffStatus(Request $request)
    {
        $labor = User::find($request->user_id);
        $labor->status = $request->status;
        $labor->save();

//        Helper::addActivity('UserStatus', $labor, 'Status Changed!');

        return response()->json(['message' => 'User status updated successfully.']);
    }

    public function staffAttendance() {
        $projects = null;

        if(Auth::user()->can('manage-man-power')) {
            $projects = Projects::whereProjectStatus('1')
                ->orderBy('project_name')
                ->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', '1')
                ->orderBy('project_name')
                ->get();
        }

        return view('front-end.manpower.staff-list')->with([
            'projects'   => $projects
        ]);
    }

    public function searchAttendance(Request $request) {

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Projects::findOrFail($request->post('pid'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('pid'));
        }
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $staffs = $project->users()
            ->whereIn('role_id', $roles)
            ->where('status','=','1')
            ->orderBy('name')
            ->get();


        return view('front-end.manpower.ajax-labours-attendance')
            ->with([
                'staffs'    => $staffs,
                'project'   => $project
            ]);
    }


    public function monthlyIndex() {
        $projects = null;

        if(Auth::user()->can('manage-man-power')) {
            $projects = Projects::whereProjectStatus('1')
                ->orderBy('project_name')
                ->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', '1')
                ->orderBy('project_name')
                ->get();
        }

        return view('front-end.manpower.staff-list')->with([
            'projects'   => $projects
        ]);
    }

    public function salaryReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'pid'   => ['required', 'numeric'],
            'month' => ['required']
        ]);

        if($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        if(Auth::user()->can('manage-man-power')) {
            $project = Projects::findOrFail($request->post('pid'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('pid'));
        }

        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $staffs = $project->users()
            ->whereIn('role_id', $roles)
            ->get();



        $requestMonth = $request->post('month');
        $dt = Carbon::createFromDate(\Str::before($requestMonth, '-'), \Str::after($requestMonth, '-'), '01');

        return view('front-end.manpower.ajax-monthly-salary')
            ->with([
                'staffs'    => $staffs,
                'project'   => $project,
                'month'     => $dt->format('F Y'),
                'reqMonth'  => $requestMonth,
//                'title'     => 'Salary Report of ' . $dt->format('F Y') . '-' . $project->project_name . ' :: ' . getOption('company_name')
            ]);
    }

    public function manpowerDetails($project, $id)
    {
        $pageConfigs = [
            'sidebarCollapsed' => true
        ];

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/manpower/add-manpower", 'name' => "Manpower"], ['name' => "Add Manpower"]
        ];

        $pro = Projects::findOrFail($project);

        if(!Auth::user()->can('manage-man-power')) {
            $assigned = $pro->users()->find(Auth::id());
            if (!$assigned) {
                return Helper::redirectBackWithNotification('error', 'Not Found OR You are not authorised!');
            }
        }

        $labour = $pro->users()->findOrFail($id);

        $payable = $labour->attendances->sum('attendance_payable_amount');
        $paid = $labour->staffPayments->sum('payment_amount');

        $events = [];
        foreach ($labour->attendances as $attendance) {
            $dt_start = Carbon::parse($attendance->attendance_date)->setTimeFromTimeString($attendance->shift->shift_start);
            $dt_end = Carbon::parse($attendance->attendance_date)->setTimeFromTimeString($attendance->shift->shift_end);

            $event = Calendar::event(
                $attendance->shift->shift_name . ' Shift',
                false,
                $dt_start,
                $dt_end,
                null,
                [
                    'color' => '#f05050',
                    'url' => '#',
                ]
            );
            array_push($events, $event);
        }
        $calendar = Calendar::addEvents($events);

        return view('front-end.manpower.details-manpower')->with([
            'breadcrumbs' => $breadcrumbs,
            'pageConfigs' => $pageConfigs,
            'labour'    => $labour,
            'project'   => $pro,
            'payable'   => $payable,
            'paid'      => $paid,
            'calendar'  => $calendar,
        ]);
    }

    public function pay(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id'     => ['required', 'numeric'],
            'labour_id'      => ['required', 'numeric'],
//            'date'           => ['date', 'required'],
            'amount'         => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }
        if($request->post('amount') > 0) {
            $payment = Helper::createNewPayment([
                'type' => 'debit',
                'to_user' => $request->post('labour_id'),
                'from_user' => Auth::id(),
                'to_bank_account' => null,
                'from_bank_account' => null,
                'amount' => $request->post('amount'),
                'project' => $request->post('project_id'),
                'purpose' => 'salary',
                'by' => 'cash',
                'date' => $request->post('date'),
                'image' => null,
                'note' => $request->post('note')
            ], 'Worker Payment Successful!');
            if(!$payment) {
                return Helper::redirectBackWithNotification();
            }
            return Helper::redirectBackWithNotification('success', 'Payment Successful!');
        }
        return Helper::redirectBackWithNotification();
    }



}
