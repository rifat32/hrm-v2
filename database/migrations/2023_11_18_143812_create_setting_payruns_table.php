<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingPayrunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_payruns', function (Blueprint $table) {
            $table->id();
            $table->enum('payrun_period', ['monthly', 'weekly']);
            $table->enum('consider_type', ['hour', 'daily_log','none']);
            $table->boolean('consider_overtime');

            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
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
        Schema::dropIfExists('setting_payruns');
    }
}
