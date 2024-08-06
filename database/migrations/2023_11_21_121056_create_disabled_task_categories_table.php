<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledTaskCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_task_categories', function (Blueprint $table) {
            $table->id();





            $table->unsignedBigInteger("task_category_id");
            $table->foreign('task_category_id')->references('id')->on('task_categories')->onDelete('cascade');


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
        Schema::dropIfExists('disabled_task_categories');
    }
}
