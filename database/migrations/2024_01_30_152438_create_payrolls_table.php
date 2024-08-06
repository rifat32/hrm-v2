<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_name');


            $table->unsignedBigInteger("payrun_id")->nullable();
            $table->foreign('payrun_id')->references('id')->on('payruns')->onDelete('cascade');

            $table->unsignedBigInteger("user_id");

            $table->double('total_holiday_hours')->nullable();
            $table->double('total_paid_leave_hours')->nullable();
            $table->double('total_regular_attendance_hours')->nullable();
            $table->double('total_overtime_attendance_hours')->nullable();
            $table->double('regular_hours')->nullable();
            $table->double('overtime_hours')->nullable();
            $table->double('total_holiday_hours_salary')->nullable();
            $table->double('leave_hours_salary')->nullable();
            $table->double('regular_attendance_hours_salary')->nullable();
            $table->double('overtime_attendance_hours_salary')->nullable();
            $table->double('regular_hours_salary')->nullable();
            $table->double('overtime_hours_salary')->nullable();


            $table->enum('status', ['pending_approval', 'approved','rejected'])->default("pending_approval");










            $table->date('start_date');
            $table->date('end_date');

            $table->boolean("is_active")->default(true);
            $table->unsignedBigInteger("business_id");

            $table->unsignedBigInteger("created_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}
