<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    /**
     * The table associated with the Model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'project_id';

    /**
     * The staffs that assigned to the project.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_logs', 'pl_project_id', 'pl_user_id');
    }

    /**
     * Get the client that owns the Project.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'project_client_id', 'id');
    }

    public function shifts() {
        return $this->hasMany(WorkingShift::class, 'shift_project_id', 'project_id');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'attendance_project_id', 'project_id');
    }

    public function employees() {
        return $this->belongsToMany(User::class, 'project_logs', 'pl_project_id', 'pl_user_id');
    }

    public function projectLogs() {
        return $this->hasMany(ProjectLogs::class, 'pl_project_id', 'project_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'payment_for_project', 'project_id');
    }

    public function vendors() {
        $role_id = Role::whereRoleSlug('supplier')->firstOrFail()->role_id;
        return $this->employees()->where('role_id', '=', $role_id)->get();
    }




}
