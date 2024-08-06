<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskCommentMentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_comment_mentions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("comment_id");
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');



            $table->unsignedBigInteger("user_id");




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
        Schema::dropIfExists('task_comment_mentions');
    }
}
