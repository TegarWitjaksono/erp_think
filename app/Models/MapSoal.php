<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapSoal extends Model
{
    use HasFactory;

    protected $table = 'map_soal';

    protected $primaryKey = 'id_map'; // Menentukan primary key

    protected $fillable = [
        'id_soal',
        'id_materi',
        'id_quiz',
    ];

    public function soal()
    {
        return $this->belongsTo(MasterSoal::class, 'id_soal', 'id_soal');
    }
    public function materi()
    {
        return $this->belongsTo(MasterMateri::class, 'id_materi', 'id_materi');
    }
    public function quiz()
    {
        return $this->belongsTo(MasterQuiz::class, 'id_quiz', 'id_quiz');
    }
}
