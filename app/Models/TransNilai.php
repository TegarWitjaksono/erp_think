<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransNilai extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'trans_nilai';

    /**
     * Primary key dari tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_trans';

    /**
     * Auto-increment untuk primary key.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Menentukan tipe primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Laravel akan mengelola timestamps otomatis.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Daftar kolom yang boleh diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'id_siswa',
        'id_materi',
        'id_kelas',
        'jum_soal',
        'benar',
        'salah',
        'score',
        'id_quiz'
    ];

    /**
     * Tipe data atribut yang dikonversi secara otomatis.
     *
     * @var array
     */
    protected $casts = [
        // Timestamps are disabled, so no need to cast them
    ];

    /**
     * Relasi ke tabel MasterSiswa.
     */
    public function siswa()
    {
        return $this->belongsTo(MasterSiswa::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Relasi ke tabel MasterMateri.
     */
    public function materi()
    {
        return $this->belongsTo(MasterMateri::class, 'id_materi', 'id_materi');
    }

    /**
     * Relasi ke tabel MasterKelas.
     */
    public function kelas()
    {
        return $this->belongsTo(MasterKelas::class, 'id_kelas', 'id_kelas');
    }
}
