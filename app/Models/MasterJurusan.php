<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJurusan extends Model
{
    use HasFactory;

    protected $table = 'master_jurusan';
    protected $primaryKey = 'id_jurusan';
    protected $fillable = ['nama_jurusan'];
}