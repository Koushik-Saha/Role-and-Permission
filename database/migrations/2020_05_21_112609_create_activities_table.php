<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('activity_id');
            $table->unsignedBigInteger('activity_of_user_id');
            $table->longText('activity_note');
            $table->unsignedBigInteger('activity_for_user_id')->nullable();
            $table->unsignedBigInteger('activity_project_id')->nullable();
            $table->unsignedBigInteger('activity_client_id')->nullable();
            $table->unsignedBigInteger('activity_vendor_id')->nullable();
            $table->unsignedBigInteger('activity_manpower_id')->nullable();
            $table->unsignedBigInteger('activity_administrator_id')->nullable();
            $table->unsignedBigInteger('activity_role_id')->nullable();
            $table->unsignedBigInteger('activity_shift_id')->nullable();
            $table->unsignedBigInteger('activity_attendance_id')->nullable();
            $table->unsignedBigInteger('activity_payment_id')->nullable();
            $table->unsignedBigInteger('activity_bank_id')->nullable();
            $table->unsignedBigInteger('activity_loan_id')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('activity_of_user_id')->references('id')->on('users');
//            $table->foreign('activity_project_id')->references('project_id')->on('projects');
            $table->foreign('activity_client_id')->references('id')->on('users');
            $table->foreign('activity_vendor_id')->references('id')->on('users');
            $table->foreign('activity_manpower_id')->references('id')->on('users');
            $table->foreign('activity_administrator_id')->references('id')->on('users');
//            $table->foreign('activity_attendance_id')->references('attendance_id')->on('attendances');
//            $table->foreign('activity_shift_id')->references('shift_id')->on('working_shifts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
