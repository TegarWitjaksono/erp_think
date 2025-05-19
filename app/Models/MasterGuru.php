<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterGuru extends Model
{
    use HasFactory;

    protected $table = 'master_guru';

    protected $primaryKey = 'id_guru'; // Menentukan primary key

    protected $fillable = [
        'nama_guru',
        'alamat_guru',
        'jenis_kelamin',
        'nip',
        'nik',
        'foto',
        'email'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
}
