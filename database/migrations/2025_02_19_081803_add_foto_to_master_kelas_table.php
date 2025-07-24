<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoToMasterKelasTable extends Migration
{
    public function up()
    {
        Schema::table('master_kelas', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('sts'); // Menambahkan kolom foto
        });
    }

    public function down()
    {
        Schema::table('master_kelas', function (Blueprint $table) {
            $table->dropColumn('foto'); // Menghapus kolom foto
        });
    }
}