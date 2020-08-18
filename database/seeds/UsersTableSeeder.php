<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id'           => 1,
            'name'              => 'Admin',
            'email'             => 'admin@gmail.com',
            'username'          => 'Admin',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 2,
            'name'              => 'Office Manager',
            'email'             => 'manager@gmail.com',
            'username'          => 'office_manager',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 3,
            'name'              => 'Project Manager',
            'email'             => 'project_manager@email.com',
            'username'          => 'project_manager',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 4,
            'name'              => 'Accountant',
            'email'             => 'accountant@email.com',
            'username'          => 'accountant',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 5,
            'name'              => 'Mr. Client',
            'email'             => 'client@email.com',
            'username'          => 'client',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 6,
            'name'              => 'Mr. Labour',
            'email'             => 'labour@email.com',
            'username'          => 'labour',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 7,
            'name'              => 'Mr. Labour 01',
            'email'             => 'labour01@email.com',
            'username'          => 'labour01',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 8,
            'name'              => 'Mr. Labour 02',
            'email'             => 'labour02@email.com',
            'username'          => 'labour02',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 9,
            'name'              => 'Mr. Labour 03',
            'email'             => 'labour03@email.com',
            'username'          => 'labour03',
            'mobile'            => '01766777357',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'can_login'         => 1,
            'image'             => 'http://localhost:8000/storage/photos/1/Manager/Jayanti Image CV-3.png',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
    }
}
