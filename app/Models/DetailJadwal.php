<?php

namespace App\Models;

use App\Models\MasterQuiz;
use App\Models\MasterMateri;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailJadwal extends Model
{
    use HasFactory;
    protected $table = 'detail_jadwal';

    protected $primaryKey = 'id_detail_jadwal'; // Menentukan primary key

    protected $fillable = [
        'id_jadwal',
        'id_materi',
        'id_quiz',
        'jam_in',
        'jam_out',
        'sts'
    ];
    public function materi()
    {
        return $this->belongsTo(MasterMateri::class, 'id_materi', 'id_materi');
    }
    public function quiz()
    {
        return $this->belongsTo(MasterQuiz::class, 'id_quiz', 'id_quiz');
    }
    public function jadwal()
    {
        return $this->belongsTo(MasterJadwal::class, 'id_jadwal', 'id_jadwal');
    }
}
