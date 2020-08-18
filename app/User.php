<?php

namespace App;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\BankAccount;
use App\Models\ItemLog;
use App\Models\Payment;
use App\Models\Projects;
use App\Models\PurchaseItem;
use App\Models\Role;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use phpDocumentor\Reflection\Project;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    protected $table = 'users';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function isAdmin() {
        return $this->role->role_slug === 'administrator';
    }

    public function isManager() {
        return $this->role->role_slug === 'manager';
    }

    public function isProjectManager() {
        return $this->role->role_slug === 'project_manager';
    }

    public function isAccountant() {
        return $this->role->role_slug === 'accountant';
    }

    public function isClient() {
        return $this->role->role_slug === 'accountant';
    }

    public function isLabour() {
        return $this->role->role_slug === 'accountant';
    }


    public function activities()
    {
        return $this->hasMany(Activity::class, 'activity_of_user_id', 'id');
    }

    /**
     * Get the projects for the client user.
     */
    public function clientProjects()
    {
        return $this->hasMany(Projects::class, 'project_client_id', 'id');
    }

    /**
     * The projects that assigned to the user.
     */
    public function projects()
    {
        return $this->belongsToMany(Projects::class, 'project_logs', 'pl_user_id', 'pl_project_id');
    }

    public function vendorProjects()
    {
        return $this->belongsToMany(Projects::class, 'project_logs', 'il_vendor_id', 'il_project_id');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'attendance_user_id', 'id');
    }

    public function banks() {
        return $this->hasMany(BankAccount::class, 'bank_user_id', 'id');
    }

    public function clientPayments() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }

    public function vendorPayments() {
        return $this->hasMany(Payment::class, 'payment_to_user', 'id');
    }

    public function staffPayments() {
        return $this->hasMany(Payment::class, 'payment_to_user', 'id');
    }

    public function managerPayments() {
        return $this->hasMany(Payment::class, 'payment_to_user', 'id');
    }

    public function expenses() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }

    /*public function managerRefunds() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }*/

    public function payments() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }

    public function paymentToUser() {
        return $this->hasMany(Payment::class, 'payment_to_user', 'id');
    }

    public function addedBy() {
        $activity = Activity::whereIn('activity_note', ['Administrator Created', 'Staff Created', 'Vendor Created', 'Client Created'])
            ->where('activity_for_user_id', '=', $this->id)
            ->first();
        return ($activity) ? $activity->activityBy : null;
    }



}
