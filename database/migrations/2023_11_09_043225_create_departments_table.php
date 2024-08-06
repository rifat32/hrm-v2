<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string("name");

            $table->text("description")->nullable();
            $table->boolean("is_active")->default(true);

            $table->unsignedBigInteger("manager_id")->nullable();
      
            $table->unsignedBigInteger("parent_id")->nullable(true);
            $table->foreign('parent_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedBigInteger("business_id");


            $table->unsignedBigInteger("work_location_id")->nullable();
            $table->foreign('work_location_id')->references('id')->on('work_locations')->onDelete('set null');

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
        Schema::dropIfExists('departments');
    }
}
