<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledRecruitmentProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_recruitment_processes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("recruitment_process_id");
            $table->foreign('recruitment_process_id')->references('id')->on('recruitment_processes')->onDelete('cascade');

            $table->unsignedBigInteger("business_id")->nullable();



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
        Schema::dropIfExists('disabled_recruitment_processes');
    }
}
