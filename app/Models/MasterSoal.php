<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSoal extends Model
{
    use HasFactory;

    protected $table = 'master_soal';
    protected $primaryKey = 'id_soal';
    protected $fillable = [
        'soal',
        'id_materi',
        'id_kategori_soal',
        'id_kategori_jawaban',
        'pilihan_1',
        'pilihan_2',
        'pilihan_3',
        'pilihan_4',
        'jawaban',
        'sts',
        'bobot',
        'type'
    ];

    public function materi()
    {
        return $this->belongsTo(MasterMateri::class, 'id_materi', 'id_materi');
    }

    public function kategoriSoal()
    {
        return $this->belongsTo(MasterKategori::class, 'id_kategori_soal', 'id_kategori');
    }

    public function kategoriJawaban()
    {
        return $this->belongsTo(MasterKategori::class, 'id_kategori_jawaban', 'id_kategori');
    }
}
