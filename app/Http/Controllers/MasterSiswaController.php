<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use App\Models\MasterJurusan;
use App\Models\MasterKelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class MasterSiswaController extends Controller
{
    public function index()
    {
        $siswa = MasterSiswa::with('jurusan', 'kelas')->get();
        $jurusan = MasterJurusan::all();
        $kelas = MasterKelas::all();
        return view('master_siswa.index', compact('siswa', 'jurusan', 'kelas'));
    }

    public function create() {}

    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string',
            'alamat_siswa' => 'required|string',
            'jenis_kelamin' => 'required',
            'nip' => 'required|string',
            'nik' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'id_jurusan' => 'required|exists:master_jurusan,id_jurusan',
            'email' => 'required|string',
            'id_kelas' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['sts'] = 1; // Set default status to active

            if ($request->hasFile('foto')) {
                $fileName = time() . '.' . $request->foto->extension();
                $request->foto->move(public_path('uploads/siswa'), $fileName);
                $data['foto'] = $fileName;
            }

            // Create student record
            $siswa = MasterSiswa::create($data);

            // Create user account with synchronized status
            $user = new User();
            $user->name = $request->nama_siswa;
            $user->email = $request->email;
            $user->password = Hash::make("12345678");
            $user->role = 0; // Student role
            $user->id_jurusan = $request->id_jurusan;
            $user->status = $siswa->sts;
            $user->nip = $request->nip; // Sync status with MasterSiswa
            $user->save();

            DB::commit();
            return redirect()->route('master_siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $siswa = MasterSiswa::findOrFail($id);
        $jurusan = MasterJurusan::all();
        $kelas = MasterKelas::all();
        return view('master_siswa.edit', compact('siswa', 'jurusan', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_siswa' => 'required|string',
            'alamat_siswa' => 'required|string',
            'jenis_kelamin' => 'required',
            'nip' => 'required|string',
            'nik' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_jurusan' => 'required|exists:master_jurusan,id_jurusan',
            'email' => 'required|email',
            'id_kelas' => 'required|exists:master_kelas,id_kelas'
        ]);

        $siswa = MasterSiswa::findOrFail($id);
        $data = $request->except(['_token', '_method']);

        if ($request->hasFile('foto')) {
            $fileName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads/siswa'), $fileName);
            $data['foto'] = $fileName;
        }

        $siswa->update($data);

        return redirect()->route('master_siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function updateStatus(Request $request)
    {
        $siswa = MasterSiswa::findOrFail($request->id);
        $siswa->sts = $request->status;
        $siswa->save();

        // Update user status
        $user = User::where('email', $siswa->email)->first();
        if ($user) {
            $user->status = $request->status;
            $user->save();
        }

        return response()->json(['success' => true]);
    }

    public function updateStatusBulk(Request $request)
    {
        try {
            DB::beginTransaction();

            // Update all selected students
            MasterSiswa::whereIn('id_siswa', $request->ids)
                ->update(['sts' => $request->status]);

            // Get all affected emails
            $emails = MasterSiswa::whereIn('id_siswa', $request->ids)
                ->pluck('email');

            // Update corresponding users
            User::whereIn('email', $emails)
                ->update(['status' => $request->status]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $siswa = MasterSiswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('master_siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
    // Add this new method to update all statuses
    public function updateStatusAll(Request $request)
    {
        try {
            DB::beginTransaction();

            $status = $request->status;

            // Update all records in the database
            MasterSiswa::query()->update(['sts' => $status]);

            // Get all student emails to update corresponding user accounts
            $emails = MasterSiswa::whereNotNull('email')->pluck('email')->toArray();

            // Update related user accounts with the same status
            if (!empty($emails)) {
                User::whereIn('email', $emails)
                    ->where('role', 0) // Only update student users (role 0)
                    ->update(['status' => $status]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status semua siswa berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
