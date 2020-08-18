<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\ProjectLogs;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectLogsController extends Controller
{
    public function assignToProject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        $project = Projects::findOrFail($id);

        $existing = $project->projectLogs()
            ->where('pl_user_id', '=', $request->post('admin'))
            ->first();

        if ($existing) {
            return Helper::redirectBackWithNotification('error', 'Already Assigned To This Project');
        }

        $pLogs = new ProjectLogs();

        $pLogs->pl_project_id = $project->project_id;
        $pLogs->pl_user_id = $request->post('admin');

        if ($pLogs->save()) {
            Helper::addActivity('project', $project->project_id, 'Project Assigned');
            Helper::addActivity('user', $request->post('admin'), 'Project Assigned');
            return Helper::redirectBackWithNotification('success', 'Admin Successfully Assigned To this Project.');
        }

        return Helper::redirectBackWithNotification();
    }
}
