<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledEmploymentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_employment_statuses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("employment_status_id");
            $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->onDelete('cascade');

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
        Schema::dropIfExists('disabled_employment_statuses');
    }
}
