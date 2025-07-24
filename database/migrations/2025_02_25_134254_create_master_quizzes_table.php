<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_quizzes', function (Blueprint $table) {
            $table->id();
            $table->integer('typ');
            $table->string('nama_quiz');
            $table->text('desc');
            $table->string('icon');
            $table->integer('sts');

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
        Schema::dropIfExists('master_quizzes');
    }
};
