<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("bank_id");
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

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
        Schema::dropIfExists('disabled_banks');
    }
}
