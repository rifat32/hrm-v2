<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledTerminationReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_termination_reasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("termination_reason_id");
            $table->foreign('termination_reason_id')->references('id')->on('termination_reasons')->onDelete('cascade');

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
        Schema::dropIfExists('disabled_termination_reasons');
    }
}
