<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\ProjectLogs;
use App\Models\Projects;
use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function administratorList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/administrator/administrator-list", 'name' => "Administrator"], ['name' => "Administrator Lists"]
        ];

        $roles = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->pluck('role_id')
            ->toArray();

        $user = User::whereIn('role_id', $roles)
            ->orderBy('name')
            ->get();

        return view('front-end.administrator.administrator-list')->with([
            'user' => $user,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function addAdministrator()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/administrator/add-administrator", 'name' => "Administrator"], ['name' => "Add Administrator"]
        ];

        $role = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->get();

        return view('front-end.administrator.add-administrator')->with([
            'breadcrumbs' => $breadcrumbs,
            'role' => $role,
        ]);
    }


    public function processAddAdministrator(Request $request)
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

        $administrator = new User();

        $administrator->role_id = $request->post('role_id');
        $administrator->name = $request->post('name');
        $administrator->fathers_name = $request->post('fathers_name');
        $administrator->email = $request->post('email');
        $administrator->username = $request->post('username');
        $administrator->mobile = $request->post('mobile');
        $administrator->address = $request->post('address');
        $administrator->email_verified_at = Carbon::now();
        $administrator->password = Hash::make($request->post('password'));
        $administrator->image = $request->post('image');
        $administrator->can_login = $request->post('can_login');
        $administrator->salary = $request->post('salary');
        $administrator->note = $request->post('note');
        $administrator->status = $request->post('status');
        $administrator->project_id = $request->post('project_id');
        $administrator->cover_image = $request->post('cover_image');
        $administrator->fb_url = $request->post('fb_url');
        $administrator->instagram_url = $request->post('instagram_url');

        $administrator->save();

        Helper::addActivity('administrator', $administrator->id, 'Administrator Created');

        return Helper::redirectBackWithNotification('success', 'Administrator Successfully Created!');

    }

    public function administratorDetails($id)
    {
        $pageConfigs = [
            'sidebarCollapsed' => true
        ];


        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/administrator/add-administrator", 'name' => "Administrator"], ['name' => "Add Administrator"]
        ];

        $client = User::findOrFail($id);

        return view('front-end.administrator.details-administrator')->with([
            'breadcrumbs' => $breadcrumbs,
            'client' => $client,
            'pageConfigs' => $pageConfigs,
        ]);
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/vendor/add-vendor", 'name' => "Vendor"], ['name' => "Add Vendor"]
        ];

        $administrator = User::findOrFail($id);

        $role = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->get();

        return view('front-end.administrator.edit-administrator')->with([
            'administrator' => $administrator,
            'breadcrumbs' => $breadcrumbs,
            'role' => $role,
        ]);
    }

    public function update(Request $request, $id)
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

        $administrator = User::findOrFail($id);

        $administrator->role_id = $request->post('role_id');
        $administrator->name = $request->post('name');
        $administrator->fathers_name = $request->post('fathers_name');
        $administrator->email = $request->post('email');
        $administrator->username = $request->post('username');
        $administrator->mobile = $request->post('mobile');
        $administrator->address = $request->post('address');
        $administrator->email_verified_at = Carbon::now();
        $administrator->password = Hash::make($request->post('password'));
        $administrator->image = $request->post('image');
        $administrator->can_login = $request->post('can_login');
        $administrator->salary = $request->post('salary');
        $administrator->note = $request->post('note');
        $administrator->status = $request->post('status');
        $administrator->cover_image = $request->post('cover_image');
        $administrator->fb_url = $request->post('fb_url');
        $administrator->instagram_url = $request->post('instagram_url');

        $administrator->save();

        Helper::addActivity('administrator', $administrator->id, 'Administrator Updated!');

        return Helper::redirectUrlWithNotification(route('administrator-details', ['id' => $administrator->id]),
            'success', 'Administrator Successfully Updated!');
    }
}
