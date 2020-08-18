<?php

namespace App\Models;

use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Sluggable;

    protected $table = 'roles';

    protected $primaryKey = 'role_id';

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }


    public function sluggable()
    {
        return [
            'role_slug' => [
                'source' => 'role_name'
            ]
        ];
    }
}
