<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");


            $table->string('title');
            $table->text('description');
            // $table->text('hidden_note')->nullable();
            $table->json('history')->nullable();

            $table->unsignedBigInteger("created_by")->nullable();


            $table->unsignedBigInteger("updated_by")->nullable();
       
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
        Schema::dropIfExists('user_notes');
    }
}
