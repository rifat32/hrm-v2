<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSocialSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("social_site_id")->nullable();
            $table->foreign('social_site_id')
                ->references('id')
                ->on('social_sites')
                ->onDelete('restrict');

            $table->unsignedBigInteger("user_id")->nullable();

            $table->string("profile_link");

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
        Schema::dropIfExists('user_social_sites');
    }
}
