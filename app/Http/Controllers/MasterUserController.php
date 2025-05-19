<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterGuru;

use App\Models\MasterKelas;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use App\Models\MasterJurusan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MasterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $jurusan = MasterJurusan::all();
        $users = User::with('jurusan')->get();
        $kelas = MasterKelas::all();
        return view('master_users.index', compact('users', 'jurusan', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:0,1,2',
            'nip' => 'required|string',
        ]);

        DB::beginTransaction();
        if ($request->role == 0) {
            MasterSiswa::create([
                'nama_siswa' => $request->name,
                'alamat_siswa' => '-',
                'jenis_kelamin' => '-',
                'nip' => $request->nip,
                'nik' => '0',
                'foto' => '0',
                'id_jurusan' => $request->id_jurusan ?? 0, // Default 0 jika tidak ada request
                'id_kelas' => $request->id_kelas,
                'email' => $request->email
            ]);
        } else if ($request->role == 1) {
            MasterGuru::create([
                'nama_guru' => $request->name,
                'alamat_guru' => '-',
                'jenis_kelamin' => '-',
                'nip' => $request->nip,
                'nik' => '0',
                'foto' => '0',
                'email' => $request->email,
                'id_jurusan' => 0 // Default 0 untuk guru
            ]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nip' => $request->nip,
            'id_jurusan' => $request->id_jurusan ?? 0 // Default 0 jika tidak ada request
        ]);

        DB::commit();

        return redirect()->route('master_user.index')->with('success', 'Berhasil menambahkan User baru.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with(['masterSiswa.kelas'])->findOrFail($id);
        $kelas = MasterKelas::all();
        $jurusan = MasterJurusan::all();
        return view('master_users.edit', compact('user', 'jurusan', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => "required|string|email|unique:users,email,{$id}",
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:0,1,2',
            'nip' => 'required|string', // Tambahkan validasi NIP
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $oldRole = $user->role;

            $data = $request->only(['name', 'email', 'role', 'nip']); // Tambahkan nip
            $data['id_jurusan'] = $request->id_jurusan ?? 0;

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Update NIP di tabel Users
            $user->nip = $request->nip;

            // Jika role berubah dari Guru ke Siswa
            if ($oldRole == 1 && $request->role == 0) {
                MasterGuru::where('email', $user->email)->delete();
                
                MasterSiswa::create([
                    'nama_siswa' => $request->name,
                    'alamat_siswa' => '-',
                    'jenis_kelamin' => '-',
                    'nip' => $request->nip, // Set NIP
                    'nik' => '0',
                    'foto' => '0',
                    'id_jurusan' => $request->id_jurusan ?? 0,
                    'id_kelas' => $request->id_kelas ?? 0,
                    'email' => $request->email
                ]);
            }
            // Jika role berubah dari Siswa ke Guru
            else if ($oldRole == 0 && $request->role == 1) {
                MasterSiswa::where('email', $user->email)->delete();
                
                MasterGuru::create([
                    'nama_guru' => $request->name,
                    'alamat_guru' => '-',
                    'jenis_kelamin' => '-',
                    'nip' => $request->nip, // Set NIP
                    'nik' => '0',
                    'foto' => '0',
                    'email' => $request->email,
                    'id_jurusan' => 0
                ]);
            }
            // Jika tetap sebagai siswa
            else if ($oldRole == 0 && $request->role == 0) {
                MasterSiswa::where('email', $user->email)->update([
                    'nama_siswa' => $request->name,
                    'email' => $request->email,
                    'nip' => $request->nip, // Update NIP
                    'id_jurusan' => $request->id_jurusan ?? 0,
                    'id_kelas' => $request->id_kelas ?? 0,
                ]);
            }
            // Jika tetap sebagai guru
            else if ($oldRole == 1 && $request->role == 1) {
                MasterGuru::where('email', $user->email)->update([
                    'nama_guru' => $request->name,
                    'email' => $request->email,
                    'nip' => $request->nip // Update NIP
                ]);
            }

            // Update data di tabel Users
            $user->update($data);

            DB::commit();
            return redirect()->route('master_user.index')->with('success', 'Berhasil Mengubah Data User.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master_user.index')->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('master_user.index')->with('success', 'Berhasil menghapus User.');
    }

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $siswa = MasterSiswa::findOrFail($request->id);
            $siswa->sts = $request->status;
            $siswa->save();

            // Update user status
            if ($siswa->email) {
                User::where('email', $siswa->email)->update(['status' => $request->status]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatusBulk(Request $request)
    {
        try {
            DB::beginTransaction();

            // Update student status
            MasterSiswa::whereIn('id_siswa', $request->ids)->update(['sts' => $request->status]);

            // Get emails of affected students
            $emails = MasterSiswa::whereIn('id_siswa', $request->ids)
                ->whereNotNull('email')
                ->pluck('email');

            // Update user status
            if ($emails->count() > 0) {
                User::whereIn('email', $emails)->update(['status' => $request->status]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Reset user password to default (12345678)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            
            // Set default password
            $defaultPassword = '12345678';
            $user->password = Hash::make($defaultPassword);
            $user->first_login = 1; // Require password change on next login
            $user->save();

            DB::commit();

            return redirect()->route('master_user.index')
                ->with('success', "Password untuk {$user->name} berhasil direset ke default (12345678)");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master_user.index')
                ->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
}
