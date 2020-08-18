<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'attendances';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'attendance_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'attendance_user_id', 'id');
    }

    public function shift() {
        return $this->belongsTo(WorkingShift::class, 'attendance_shift_id', 'shift_id');
    }

    public function project() {
        return $this->belongsTo(Projects::class, 'attendance_project_id', 'project_id');
    }

    public function activity() {
        return $this->hasOne(Activity::class, 'activity_attendance_id', 'attendance_id');
    }
}
