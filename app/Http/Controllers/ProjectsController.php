<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\ImageUpload;
use App\Models\MotherCategory;
use App\Models\Project;
use App\Models\ProjectLogs;
use App\Models\Projects;
use App\Models\Role;
use App\Models\Settings;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function addProject()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/add-project", 'name' => "Project"], ['name' => "Add Project"]
        ];

        $user = User::where('role_id', '=', '5')->get();

        return view('front-end.projects.add-project')->with([
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
        ]);
    }

    public function processfilemanager()
    {
        return view('back-end.demo');
    }

    public function processAddProject(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_name' => ['required', 'string', 'max:191'],
            'project_location' => ['required', 'string', 'max:191'],
            'project_price' => ['required', 'numeric'],
            'project_status' => ['required'],
            'project_description' => ['nullable', 'string'],
            'project_client_id' => ['required'],
            'project_total_member' => ['required'],
            'project_date' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        $project = new Projects();

        $project->project_name = $request->post('project_name');
        $project->project_location = $request->post('project_location');
        $project->project_price = $request->post('project_price');
        $project->project_status = $request->post('project_status');
        $project->project_client_id = $request->post('project_client_id');
        $project->project_date = $request->post('project_date');
        $project->project_total_member = $request->post('project_total_member');
        $project->project_description = $request->post('project_description');
        $project->project_image = $request->post('project_image');

        $project->save();

        Helper::addActivity('project', $project->project_id, 'Project Created');

        return Helper::redirectUrlWithNotification(route('active-project-list'),
            'success', 'Project Successfully Created!');

    }

    public function activeProjectList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/active-project-list", 'name' => "Project"], ['name' => "Active Project List"]
        ];

        $project = Projects::where('project_status', '=', '1')->get();

        return view('front-end.projects.active-project-list')->with([
            'project' => $project,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function holdProjectList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/hold-project-list", 'name' => "Project"], ['name' => "Hold Project List"]
        ];

        $project = Projects::where('project_status', '=', '2')->get();

        return view('front-end.projects.hold-project-list')->with([
            'project' => $project,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function completedProjectList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/completed-project-list", 'name' => "Project"], ['name' => "Completed Project List"]
        ];

        $project = Projects::where('project_status', '=', '4')->get();

        return view('front-end.projects.completed-project-list')->with([
            'project' => $project,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function cancelledProjectList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/cancelled-project-list", 'name' => "Project"], ['name' => "Cancelled Project List"]
        ];

        $project = Projects::where('project_status', '=', '3')->get();

        return view('front-end.projects.cancelled-project-list')->with([
            'project' => $project,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function allProjectList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/all-project-list", 'name' => "Project"], ['name' => "All Project List"]
        ];

        $project = Projects::all();
        $user = User::where('role_id','5')->get();

        return view('front-end.projects.all-project-list')->with([
            'project' => $project,
            'breadcrumbs' => $breadcrumbs,
            'user' => $user,
        ]);
    }

    public function projectDetails($id)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/add-project", 'name' => "Project"], ['name' => "Add Project"]
        ];

//        $project = Projects::findOrFail($id);

        if (Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Projects::findOrFail($id);
        } else {
            $project = Auth::user()->projects()->findOrFail($id);
        }
        if (!$project) {
            return Helper::redirectBackWithNotification('error', 'Project not found or not authorised!');
        }

        $user = User::where('role_id','2')->get();

        $role_manager = Role::whereRoleSlug('manager')
            ->firstOrFail();

        $projectLogs = $project->users()
            ->where('role_id', '=', $role_manager->role_id)
            ->get();

        $received = $project->payments()->where('payment_type', '=', 'credit')
            ->where('payment_purpose', '=', 'project_money')
            ->get();

        if (!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
            $expenses = $project->payments()->where('payment_type', '=', 'debit')
                ->orderByDesc('payment_date')
                ->select(
                    DB::raw('
                            payment_purpose,
                            sum(payment_amount) AS payment_amount,
                            MAX(payment_by) AS payment_by,
                            MAX(payment_date) AS payment_date
                    '))
                ->groupby('payment_purpose')
                ->get();
        } else {
            $expenses = $project->payments()->where('payment_type', '=', 'debit')
                ->orderByDesc('payment_date')
                ->select(
                    DB::raw('
                            payment_purpose,
                            sum(payment_amount) AS payment_amount,
                            MAX(payment_by) AS payment_by,
                            MAX(payment_date) AS payment_date
                    '))
                ->groupby('payment_purpose')
                ->get();
        }


        return view('front-end.projects.details-project')->with([
            'breadcrumbs' => $breadcrumbs,
            'project' => $project,
            'user' => $user,
            'projectLogs' => $projectLogs,
            'received' => $received,
            'expenses' => $expenses,
        ]);
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/project/add-project", 'name' => "Project"], ['name' => "Add Project"]
        ];

        $projects = Projects::findOrFail($id);

        $user = User::where('role_id','5')->get();

        return view('front-end.projects.edit-project')->with([
            'projects' => $projects,
            'user' => $user,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'project_name' => ['required', 'string', 'max:191'],
            'project_location' => ['required', 'string', 'max:191'],
            'project_price' => ['required', 'numeric'],
            'project_status' => ['required'],
            'project_description' => ['nullable', 'string'],
            'project_client_id' => ['required'],
            'project_total_member' => ['required'],
            'project_date' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        $project = Projects::findOrFail($id);

        $project->project_name = $request->post('project_name');
        $project->project_location = $request->post('project_location');
        $project->project_price = $request->post('project_price');
        $project->project_status = $request->post('project_status');
        $project->project_client_id = $request->post('project_client_id');
        $project->project_date = $request->post('project_date');
        $project->project_total_member = $request->post('project_total_member');
        $project->project_description = $request->post('project_description');
        $project->project_image = $request->post('project_image');

        $project->save();

        Helper::addActivity('project', $project->project_id, 'Project Updated!');

        return Helper::redirectUrlWithNotification(route('project-details', ['id' => $project->project_id]),
            'success', 'Project Successfully Updated!');
    }

    public function changeStatus(Request $request)
    {
        $project = Projects::findOrFail($request->post('project'));

        $project->project_status = $request->post('status');

        if ($project->save()) {
            Helper::addActivity('project', $project->project_id, 'Project Status Changed!');
            return Helper::redirectBackWithNotification('success', 'Project Status Successfully Changed!');
        }
        return Helper::redirectBackWithNotification();
    }

}
