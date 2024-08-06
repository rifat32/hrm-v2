<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['paid', 'unpaid'])->default("unpaid");
            $table->string('amount');

            $table->boolean('is_earning_enabled');


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
        Schema::dropIfExists('setting_leave_types');
    }
}
