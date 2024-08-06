<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_designations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("designation_id");
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');

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
        Schema::dropIfExists('disabled_designations');
    }
}
