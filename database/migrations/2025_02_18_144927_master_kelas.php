<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('master_kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 100);
            $table->integer('sts')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_kelas');
    }
};
