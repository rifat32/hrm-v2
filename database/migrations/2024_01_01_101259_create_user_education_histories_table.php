<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEducationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_education_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("user_id");







            $table->string('degree');
            $table->string('major');
            $table->string('school_name');
            $table->date('graduation_date');
            $table->date('start_date');
            $table->text('achievements')->nullable();
            $table->text('description')->nullable();
            $table->string("address")->nullable();
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->string("postcode")->nullable();
            $table->boolean('is_current')->default(false);
            $table->json('attachments')->nullable();




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
        Schema::dropIfExists('user_education_histories');
    }
}
