<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizUserResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_user_result', function (Blueprint $table) {
            $table->integer('quiz_id');
            $table->foreign('quiz_id')->references('id')->on('quizzes');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_user_result');
    }
}
