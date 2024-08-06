<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPensionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_pension_histories', function (Blueprint $table) {
            $table->id();
            $table->boolean('pension_scheme_registered')->default(false);
            $table->string('pension_scheme_name')->nullable();
            $table->json('pension_scheme_letters')->nullable();


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
        Schema::dropIfExists('business_pension_histories');
    }
}
