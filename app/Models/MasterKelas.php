<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKelas extends Model
{
    use HasFactory;

    protected $table = 'master_kelas';
    protected $primaryKey = 'id_kelas';
    protected $fillable = ['nama_kelas', 'id_jurusan', 'sts', 'foto']; // Tambahkan 'foto' ke dalam $fillable

    public function materi()
    {
        return $this->hasMany(MasterMateri::class, 'id_kelas', 'id_kelas');
    }
    public function jurusan()
    {
        return $this->belongsTo(MasterJurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function siswa()
    {
        return $this->hasMany(MasterSiswa::class, 'id_kelas', 'id_kelas');
    }

    // Add this scope if you want to filter active/inactive classes
    public function scopeActive($query)
    {
        return $query->where('sts', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('sts', 0);
    }
}
