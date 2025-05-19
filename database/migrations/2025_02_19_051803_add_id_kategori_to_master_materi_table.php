<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdKategoriToMasterMateriTable extends Migration
{
    public function up()
    {
        Schema::table('master_materi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kategori')->after('id_materi'); // Menambahkan kolom id_kategori
            $table->foreign('id_kategori')->references('id_kategori')->on('master_kategori')->onDelete('cascade'); // Menambahkan foreign key
        });
    }

    public function down()
    {
        Schema::table('master_materi', function (Blueprint $table) {
            $table->dropForeign(['id_kategori']); // Menghapus foreign key
            $table->dropColumn('id_kategori'); // Menghapus kolom id_kategori
        });
    }
}
