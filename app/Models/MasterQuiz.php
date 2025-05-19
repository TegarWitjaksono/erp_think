<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterQuiz extends Model
{
    use HasFactory;
    protected $table = 'master_quiz'; // Nama tabel
    protected $primaryKey = 'id_quiz'; // Primary key tabel


    protected $keyType = 'int';
    protected $fillable = [
        'typ',
        'nama_quiz',
        'desc',
        'sts',
        'durasi',
        'icon',
    ];
    public function mapSoal()
    {
        return $this->hasMany(MapSoal::class, 'id_quiz', 'id_quiz');
    }
}
