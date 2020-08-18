<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Attendance;
use App\Models\Project;
use App\Models\Projects;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use stdClass;

class AttendanceController extends Controller
{

    public function storeAttendance(Request $request) {

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Projects::findOrFail($request->post('project_id'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('project_id'));
        }
        if(!$project) {
            return Helper::sendJsonResponse('error', 'Project not found or you are not authorised!');
        }
        $dtCheck = $this->checkDateTimeForAttendance($request->post('date'), $request->post('shift'));
        if($dtCheck != 'ok') {
            return Helper::sendJsonResponse('error', $dtCheck);
        }

        $staff = $project->users()->find($request->post('labour_id'));
        if(!$staff) {
            return Helper::sendJsonResponse('error', 'Staff not found!');
        }

        $oldAtt = Attendance::whereAttendanceProjectId($project->project_id)
            ->where('attendance_user_id', '=', $staff->id)
            ->where('attendance_date', '=', $request->post('date'))
            ->where('attendance_shift_id', '=', $request->post('shift'))
            ->first();

        if($oldAtt) {
            return Helper::sendJsonResponse('error', 'Attendance already taken!');
        }


        $attendance = new Attendance();

        $attendance->attendance_date = $request->post('date');
        $attendance->attendance_user_id = $request->post('labour_id');
        $attendance->attendance_project_id = $project->project_id;
        $attendance->attendance_shift_id = $request->post('shift');
        $attendance->attendance_payable_amount = number_format($staff->salary / 2, 2);
        $attendance->attendance_paid_amount = $request->post('paid');
        $attendance->attendance_note = $request->post('note');

        $attendance->save();

        Helper::addActivity('attendance', $attendance->attendance_id, 'Attendance Added');

        if($attendance->attendance_paid_amount > 0) {
            $payment = Helper::createNewPayment([
                'type' => 'debit',
                'to_user' => $attendance->attendance_user_id,
                'from_user' => (!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) ? Auth::id() : null,
                'to_bank_account' => null,
                'from_bank_account' => null,
                'amount' => $attendance->attendance_paid_amount,
                'project' => $attendance->attendance_project_id,
                'purpose' => 'salary',
                'by' => 'cash',
                'date' => $attendance->attendance_date,
                'image' => null,
                'note'  => $attendance->attendance_note
            ]);
            if(!$payment) {
                return Helper::redirectBackWithNotification();
            }
        }

        return Helper::sendJsonResponse('success', 'Attendance Added Successfully!');
    }

    protected function checkDateTimeForAttendance(string $date, int $shift_id) {
        $shift = WorkingShift::findOrFail($shift_id);
        $date = Carbon::parse($date);
        $date->setTimeFromTimeString($shift->shift_start);

        if(!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
            if($date->diffInMinutes(Carbon::now(), false) > 180) {
                return 'You Can\'t add attendance for the shift now!';
            }
        }
        if($date->diffInMinutes(Carbon::now(), false) < 0) {
            return 'Shift has\'t started yet!';
        }
        return 'ok';
    }

    public function report(Request $request) {
        $projects = null;

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/manpower/attendance/report", 'name' => "Attendance Report"], ['name' => "Attendance Report"]
        ];

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $projects = Projects::where('project_status','=','1')
                ->orderBy('project_name')
                ->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', '1')
                ->orderBy('project_name')
                ->get();
        }

        return view('front-end.manpower.attendance-report')->with([
                'projects'  => $projects,
                'breadcrumbs' => $breadcrumbs,
            ]);

    }

    public function showReport(Request $request) {

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Projects::findOrFail($request->post('pid'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('pid'));
        }

        $attendances = $project->attendances()
            ->whereBetween('attendance_date', [Carbon::parse($request->post('start')), Carbon::parse($request->post('end'))])
            ->orderByDesc('attendance_date')
            ->get();

        return view('front-end.manpower.ajax-attendance')
            ->with([
                'project'       => $project,
                'attendances'   => $this->makeAttendanceReport($attendances),
                'start'         => Carbon::parse($request->post('start'))->toFormattedDateString(),
                'end'           => Carbon::parse($request->post('end'))->toFormattedDateString()
            ]);
    }

    protected function makeAttendanceReport(Collection $collection) {
        $attendances = collect();

        foreach ($collection as $item) {
            $uuid = Carbon::parse($item->attendance_date)->format('Y_m_d') . '_' . $item->user->id;

            if($attendances->isNotEmpty() && $existingKey = $this->uuidExists($uuid, $attendances)) {
                $shift = ['id' => $item->shift->shift_id, 'name' => $item->shift->shift_name];

                array_push($attendances[$existingKey]->shifts, $shift);
                $attendances[$existingKey]->payable += ($item->attendance_payable_amount ? $item->attendance_payable_amount : 0);
                $attendances[$existingKey]->paid += ($item->attendance_paid_amount ? $item->attendance_paid_amount : 0);
            }
            else {
                $attendances->push( $this->makeNewCollectionItem($item) );
            }
        }

        return $attendances;
    }

    protected function uuidExists(string $uniqueId, Collection $collection) {
        return $collection->search(function ($item, $key) use ($uniqueId) {
            return $item->uuid == $uniqueId;
        });
    }

    protected function makeNewCollectionItem(Attendance $attendance) {
        $item = new stdClass();
        $item->uuid = Carbon::parse($attendance->attendance_date)->format('Y_m_d') . '_' . $attendance->user->id;
        $item->user = ['id' => $attendance->user->id, 'name' => $attendance->user->name];
        $item->date = Carbon::parse($attendance->attendance_date)->toFormattedDateString();
        $item->shifts = [
            ['id'    => $attendance->shift->shift_id, 'name'  => $attendance->shift->shift_name]
        ];
        $item->payable = $attendance->attendance_payable_amount ? $attendance->attendance_payable_amount : 0;
        $item->paid = $attendance->attendance_paid_amount ? $attendance->attendance_paid_amount : 0;
        $item->taken_by = $attendance->activity->activityBy->name;
        $item->attendance_id = $attendance->attendance_id;

        return $item;
    }

}
