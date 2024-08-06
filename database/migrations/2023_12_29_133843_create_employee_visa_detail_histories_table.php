<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeVisaDetailHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_visa_detail_histories', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("business_id");





            $table->string("BRP_number");
            $table->date("visa_issue_date");
            $table->date("visa_expiry_date");
            $table->string("place_of_issue");
            $table->json("visa_docs");

            $table->date("from_date");
            $table->date("to_date")->nullable();


            $table->boolean("is_manual")->default(0);

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
        Schema::dropIfExists('employee_visa_detail_histories');
    }
}
