<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'activities';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'activity_id';
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    const UPDATED_AT = false;

    public function attendance() {
        return $this->belongsTo(Attendance::class, 'activity_attendance_id', 'attendance_id');
    }

    public function activityBy() {
        return $this->belongsTo(User::class, 'activity_of_user_id', 'id');
    }
}
