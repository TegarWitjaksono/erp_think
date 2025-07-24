<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_materi', function (Blueprint $table) {
            $table->id('id_materi'); // AUTO_INCREMENT dan PRIMARY KEY
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('id_kelas'); // Foreign Key ke master_kelas
            $table->integer('sts')->default(1);
            $table->timestamps(); // created_at & updated_at


            // Relasi ke tabel master_kelas
            $table->foreign('id_kelas')->references('id_kelas')->on('master_kelas')->onDelete('cascade');
        });
    }

    /**
     * Rollback migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_materi');
    }
};
