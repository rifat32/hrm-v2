<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledJobPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_job_platforms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("job_platform_id");
            $table->foreign('job_platform_id')->references('id')->on('job_platforms')->onDelete('cascade');

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
        Schema::dropIfExists('disabled_job_platforms');
    }
}
