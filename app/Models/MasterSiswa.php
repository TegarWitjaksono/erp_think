<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSiswa extends Model
{
    use HasFactory;
    protected $table = 'master_siswa'; // Nama tabel
    protected $primaryKey = 'id_siswa'; // Primary key tabel


    protected $keyType = 'int';
    protected $fillable = [
        'nama_siswa',
        'alamat_siswa',
        'jenis_kelamin',
        'nip',
        'nik',
        'foto',
        'id_jurusan',
        'email',
        'id_kelas',
        'sts' // 1 = active, 0 = disabled
    ];

    public function jurusan()
    {
        return $this->belongsTo(MasterJurusan::class, 'id_jurusan', 'id_jurusan');
    }
    public function kelas()
    {
        return $this->belongsTo(MasterKelas::class, 'id_kelas', 'id_kelas');
    }
}
