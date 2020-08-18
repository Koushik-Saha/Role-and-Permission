<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('name');
            $table->string('fathers_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('mobile')->nullable();
            $table->string('status')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('fb_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->boolean('can_login')->default(false);
            $table->string('section')->nullable();
            $table->float('salary', 10, 2)->nullable();
            $table->rememberToken();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('role_id')->on('roles');
//            $table->foreign('project_id')->references('project_id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
