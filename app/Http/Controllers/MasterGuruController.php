<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\MasterGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MasterGuruController extends Controller
{
    public function index()
    {
        $gurus = MasterGuru::all();
        return view('master_guru.index', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'alamat_guru' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|string|max:255'
        ]);

        $guru = new MasterGuru();
        $guru->nama_guru = $request->nama_guru;
        $guru->alamat_guru = $request->alamat_guru;
        $guru->jenis_kelamin = $request->jenis_kelamin;
        $guru->nip = $request->nip;
        $guru->nik = $request->nik;
        $guru->email = $request->email;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/guru'), $filename);
            $guru->foto = $filename;
        }

        $guru->save();

        $user = new User();
        $user->name = $request->nama_guru;
        $user->email = $request->email;
        $user->password = Hash::make("12345678"); // Default password is the name of the guru
        $user->role = 1; // Assuming 1 is the role for Guru
        $user->nip = $request->nip;
        $user->save();

        return redirect()->route('master_guru.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $guru = MasterGuru::findOrFail($id);
        return view('master_guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'alamat_guru' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'nip' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $guru = MasterGuru::findOrFail($id);
        $guru->nama_guru = $request->nama_guru;
        $guru->alamat_guru = $request->alamat_guru;
        $guru->jenis_kelamin = $request->jenis_kelamin;
        $guru->nip = $request->nip;
        $guru->nik = $request->nik;
        $guru->email = $request->email;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/guru'), $filename);
            $guru->foto = $filename;
        }

        $guru->save();

        // Cek apakah email berubah
        // if ($guru->email !== $request->email) {
        //     $user = User::where('email', $guru->email)->first();
        //     if ($user) {
        //         $user->update(['email' => $request->email]);
        //     }
        // }

        return redirect()->route('master_guru.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = MasterGuru::findOrFail($id);
        $user = User::where('email', $guru->email)->first();

        if ($user) {
            $user->delete();
        }

        $guru->delete();

        return redirect()->route('master_guru.index')->with('success', 'Data Guru berhasil dihapus.');
    }

    public function jadwalGuru()
    {
        $email = Auth::user()->email;


        $data = DB::table('detail_jadwal')
            ->join('master_jadwal', 'detail_jadwal.id_jadwal', '=', 'master_jadwal.id_jadwal')
            ->join('master_guru', 'master_jadwal.id_guru', '=', 'master_guru.id_guru')
            ->where('master_guru.email', $email)
            ->select(
                'master_jadwal.id_jadwal',
                'master_jadwal.hari',
                'master_jadwal.nama_jadwal',
                DB::raw('MIN(detail_jadwal.jam_in) as jam_in'),
                DB::raw('MIN(detail_jadwal.jam_out) as jam_out')
            )
            ->groupBy('master_jadwal.id_jadwal', 'master_jadwal.hari', 'master_jadwal.nama_jadwal')
            ->get();
        return view('master_guru.jadwal', compact('data'));
    }
}
