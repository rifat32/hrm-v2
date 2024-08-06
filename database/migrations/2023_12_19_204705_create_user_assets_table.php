<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
        

            $table->unsignedBigInteger("business_id");



            $table->string("name");
            $table->string("code");
            $table->string("serial_number");
            $table->boolean("is_working");
            $table->enum('status', ['available', 'assigned', 'returned', 'damaged', 'lost', 'reserved', 'repair_waiting'])->default('available');
            $table->string("type");
            $table->string("image")->nullable();
            $table->date("date");
            $table->text("note");

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
        Schema::dropIfExists('user_assets');
    }
}
