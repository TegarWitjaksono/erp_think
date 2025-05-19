<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMateri extends Model
{
    use HasFactory;

    protected $table = 'master_materi';
    protected $primaryKey = 'id_materi';
    protected $fillable = [
        'judul',
        'deskripsi',
        'file_materi',
        'id_kategori',
        'id_kelas',
        'sts',
        'img',
        'durasi'
    ];

    public function kategori()
    {
        return $this->belongsTo(MasterKategori::class, 'id_kategori', 'id_kategori');
    }

    public function kelas()
    {
        return $this->belongsTo(MasterKelas::class, 'id_kelas', 'id_kelas');
    }

    public function soal()
    {
        return $this->hasMany(MasterSoal::class, 'id_materi', 'id_materi');
    }
}
