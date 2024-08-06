<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("leave_id")->nullable();
            $table->unsignedBigInteger("actor_id")->nullable();

            $table->string('action');
            $table->boolean('is_approved')->nullable();
            $table->date('leave_created_at');
            $table->date('leave_updated_at');


            $table->enum('leave_duration', ['single_day', 'multiple_day', 'half_day','hours']);
            $table->enum('day_type', ['first_half', 'last_half'])->nullable();
            $table->unsignedBigInteger("leave_type_id");
            $table->foreign('leave_type_id')->references('id')->on('setting_leave_types')->onDelete('restrict');
            $table->unsignedBigInteger("user_id");

            $table->date('date')->nullable();
            $table->string('note');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->double('hourly_rate');


            $table->enum('status', ['pending_approval','in_progress', 'approved','rejected'])->default("pending_approval");

            $table->json('attachments')->nullable();

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
        Schema::dropIfExists('leave_histories');
    }
}
