<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledWorkLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_work_locations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("work_location_id");
            $table->foreign('work_location_id')->references('id')->on('work_locations')->onDelete('cascade');

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
        Schema::dropIfExists('disabled_work_locations');
    }
}
