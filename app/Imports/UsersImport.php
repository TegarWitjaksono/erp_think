<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'role' => $row['role'],
            'id_jurusan' => $row['id_jurusan'],
            'id_kelas' => $row['id_kelas'],
            'password' => Hash::make('password'), // Set a default password
        ]);
    }
}
