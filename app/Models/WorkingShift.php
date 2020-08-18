<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingShift extends Model
{
    protected $table = 'working_shifts';

    protected $primaryKey = 'shift_id';

    public $timestamps = false;
}
