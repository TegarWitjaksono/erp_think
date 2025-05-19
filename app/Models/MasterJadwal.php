<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJadwal extends Model
{
    use HasFactory;

    protected $table = 'master_jadwal';

    protected $primaryKey = 'id_jadwal'; // Menentukan primary key

    protected $fillable = [
        'hari',
        'id_kelas',
        'nama_jadwal',
        'sts',
        'type',
    ];

    public $timestamps = false;

    public function guru()
    {
        return $this->belongsTo(MasterGuru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(MasterSiswa::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(MasterKelas::class, 'id_kelas');
    }
    public function detailJadwal()
    {
        return $this->hasMany(DetailJadwal::class, 'id_jadwal', 'id_jadwal');
    }
}