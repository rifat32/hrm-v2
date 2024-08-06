<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeRightToWorkHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_right_to_work_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
       
            $table->unsignedBigInteger("business_id");


            $table->string('right_to_work_code');
            $table->date('right_to_work_check_date');
            $table->date('right_to_work_expiry_date');
            $table->json('right_to_work_docs');


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
        Schema::dropIfExists('employee_right_to_work_histories');
    }
}
