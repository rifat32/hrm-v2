<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->text("description")->nullable();
            $table->date("start_date");
            $table->date("end_date")->nullable();
            $table->enum('status', ['pending','in_progress', 'completed']);

            $table->boolean("is_default")->default(false);


            $table->boolean("is_active")->default(true);
            $table->unsignedBigInteger("business_id");


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
        Schema::dropIfExists('projects');
    }
}
