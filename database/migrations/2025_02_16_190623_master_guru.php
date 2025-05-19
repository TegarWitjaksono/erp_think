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
        Schema::create('master_guru', function (Blueprint $table) {
            $table->id('id_guru');
            $table->string('nama_guru');
            $table->text('alamat_guru');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('nip')->unique();
            $table->string('nik')->unique();

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
        Schema::dropIfExists('master_guru');
    }
};
