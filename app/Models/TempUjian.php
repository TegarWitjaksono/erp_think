<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempUjian extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'temp_ujian';

    /**
     * Primary key dari tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_temp_ujian';

    /**
     * Auto-increment untuk primary key.
     * Jika id_temp_ujian adalah UUID atau string, ubah menjadi false dan ubah keyType ke 'string'.
     *
     * @var bool
     */
    public $incrementing = true; // Jika bukan UUID, tetap auto-increment

    /**
     * Menentukan tipe primary key.
     *
     * @var string
     */
    protected $keyType = 'int'; // Gantilah ke 'string' jika menggunakan UUID

    /**
     * Laravel akan mengelola timestamps otomatis.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Daftar kolom yang boleh diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'id_materi',
        'id_quiz',
        'id_soal',
        'id_siswa',
        'pilihan',
        'kunci_jawaban',
    ];

    /**
     * Tipe data atribut yang dikonversi secara otomatis.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel MasterMateri.
     */
    public function materi()
    {
        return $this->belongsTo(MasterMateri::class, 'id_materi', 'id_materi');
    }

    /**
     * Relasi ke tabel MasterQuiz.
     */
    public function quiz()
    {
        return $this->belongsTo(MasterQuiz::class, 'id_quiz', 'id_quiz');
    }

    /**
     * Relasi ke tabel MasterSoal.
     */
    public function soal()
    {
        return $this->belongsTo(MasterSoal::class, 'id_soal', 'id_soal');
    }

    /**
     * Relasi ke tabel MasterSiswa.
     */
    public function siswa()
    {
        return $this->belongsTo(MasterSiswa::class, 'id_siswa', 'id_siswa');
    }
}
