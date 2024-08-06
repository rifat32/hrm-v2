<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeRotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_rotas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();





            $table->unsignedBigInteger("department_id")->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->unsignedBigInteger("user_id")->nullable();







            $table->boolean("is_default")->default(false);

            $table->boolean("is_active")->default(true);










            $table->unsignedBigInteger("business_id")->nullable();


            $table->unsignedBigInteger("created_by")->nullable();
    

            $table->softDeletes();
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
        Schema::dropIfExists('employee_rotas');
    }
}
