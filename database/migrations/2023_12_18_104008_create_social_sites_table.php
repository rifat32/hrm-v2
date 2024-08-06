<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon');
            $table->string('link');

            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);

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
        Schema::dropIfExists('social_sites');
    }
}
