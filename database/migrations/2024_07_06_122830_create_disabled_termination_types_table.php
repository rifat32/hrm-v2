<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledTerminationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_termination_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("termination_type_id");
            $table->foreign('termination_type_id')->references('id')->on('termination_types')->onDelete('cascade');

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
        Schema::dropIfExists('disabled_termination_types');
    }
}
