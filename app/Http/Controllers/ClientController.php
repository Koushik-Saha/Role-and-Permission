<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\ProjectLogs;
use App\Models\Projects;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function clientList()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/client/add-client", 'name' => "Client"], ['name' => "Client Lists"]
        ];

        $user = User::where('role_id', '=', '5')->get();

        return view('front-end.client.client-list')->with([
            'user' => $user,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function addClient()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/client/add-client", 'name' => "Client"], ['name' => "Add Client"]
        ];

        return view('front-end.client.add-client')->with([
            'breadcrumbs' => $breadcrumbs,
        ]);
    }


    public function processAddClient(Request $request)
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

        $client = new User();

        $client->role_id = $request->post('role_id');
        $client->name = $request->post('name');
        $client->fathers_name = $request->post('fathers_name');
        $client->email = $request->post('email');
        $client->username = $request->post('username');
        $client->mobile = $request->post('mobile');
        $client->address = $request->post('address');
        $client->email_verified_at = Carbon::now();
        $client->password = Hash::make($request->post('password'));
        $client->image = $request->post('image');
        $client->can_login = $request->post('can_login');
        $client->salary = $request->post('salary');
        $client->note = $request->post('note');
        $client->status = $request->post('status');
        $client->cover_image = $request->post('cover_image');
        $client->fb_url = $request->post('fb_url');
        $client->instagram_url = $request->post('instagram_url');

        $client->save();

        Helper::addActivity('client', $client->id, 'Client Created');

        return Helper::redirectBackWithNotification('success', 'Client Successfully Created!');

    }

    public function clientDetails($id)
    {
        $pageConfigs = [
            'sidebarCollapsed' => true
        ];


        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/client/add-client", 'name' => "Client"], ['name' => "Add Client"]
        ];

        $client = User::findOrFail($id);

        return view('front-end.client.details-client')->with([
            'breadcrumbs' => $breadcrumbs,
            'client' => $client,
            'pageConfigs' => $pageConfigs,
        ]);
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/client/add-client", 'name' => "Client"], ['name' => "Add Client"]
        ];

        $client = User::findOrFail($id);

        return view('front-end.client.edit-client')->with([
            'client' => $client,
            'breadcrumbs' => $breadcrumbs,
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

        $client = User::findOrFail($id);

        $client->role_id = $request->post('role_id');
        $client->name = $request->post('name');
        $client->fathers_name = $request->post('fathers_name');
        $client->email = $request->post('email');
        $client->username = $request->post('username');
        $client->mobile = $request->post('mobile');
        $client->address = $request->post('address');
        $client->email_verified_at = Carbon::now();
        $client->password = Hash::make($request->post('password'));
        $client->image = $request->post('image');
        $client->can_login = $request->post('can_login');
        $client->salary = $request->post('salary');
        $client->note = $request->post('note');
        $client->status = $request->post('status');
        $client->cover_image = $request->post('cover_image');
        $client->fb_url = $request->post('fb_url');
        $client->instagram_url = $request->post('instagram_url');

        $client->save();

        Helper::addActivity('client', $client->id, 'Client Updated!');

        return Helper::redirectUrlWithNotification(route('client-details', ['id' => $client->id]),
            'success', 'Client Successfully Updated!');
    }

}
