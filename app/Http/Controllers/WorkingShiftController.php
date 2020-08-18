<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Project;
use App\Models\Projects;
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkingShiftController extends Controller
{

    public function addWorkingShift()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/working-shift/add-working-shift", 'name' => "WorkingShift"], ['name' => "Add WorkingShift"]
        ];

        $projects = Projects::all();

        return view('front-end.working-shift.add-working-shift')->with([
            'breadcrumbs' => $breadcrumbs,
            'projects' => $projects,
        ]);
    }

    public function processWorkingShift(Request $request) {
        $validator = Validator::make($request->all(), [
            'shift_project_id'        => ['required'],
            'shift_name'              => ['required', 'string', 'max:255'],
            'shift_start'        => ['required'],
            'shift_end'          => ['required']
        ]);

        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        $shift = new WorkingShift();

        $shift->shift_name = $request->post('shift_name');
        $shift->shift_project_id = $request->post('shift_project_id');
        $shift->shift_start = $request->post('shift_start');
        $shift->shift_end = $request->post('shift_end');

        if($shift->save()) {
            Helper::addActivity('project', $request->post('shift_project_id'), 'Shift added to the Project');
            Helper::addActivity('shift', $shift->shift_id, 'Shift added to the Project');

            return Helper::redirectBackWithNotification('success', 'Shift Successfully Added!');
        }
        return Helper::redirectBackWithNotification();
    }

    public function workingShiftList(Request $request) {
        $project = Projects::findOrFail($request->post('pid'));

        return view('front-end.working-shift.ajax-result')->with([
            'project' => $project,
        ]);
    }

    public function delete(Request $request) {
        $shift = WorkingShift::findOrFail($request->post('shift_id'));

        try {
            $shift->delete();

            Helper::addActivity('shift', $shift->shift_id, 'Shift Deleted!');
            return Helper::redirectBackWithNotification('success', 'Shift Deleted!');
        }
        catch (\Exception $exception) {
            return Helper::redirectBackWithException($exception);
        }
    }

}
