<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserJobHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_job_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");

            $table->string('company_name');
            $table->string('country');
            $table->string('job_title');
            $table->date('employment_start_date');
            $table->date('employment_end_date')->nullable();
            $table->text('responsibilities')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('contact_information')->nullable();

            $table->string('work_location')->nullable();
            $table->text('achievements')->nullable();

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
        Schema::dropIfExists('user_job_histories');
    }
}
