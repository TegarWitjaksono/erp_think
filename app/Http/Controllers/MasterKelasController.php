<?php

namespace App\Http\Controllers;

use App\Models\MasterKelas;
use App\Models\MasterJurusan;
use App\Models\MasterSiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterKelasController extends Controller
{
    public function index()
    {
        $kelas = MasterKelas::with('jurusan')->get(); // Mengambil kelas dengan relasi jurusan
        $jurusan = MasterJurusan::all(); // Mengambil semua jurusan
        return view('master_kelas.index', compact('kelas', 'jurusan')); // Mengirim data ke view
    }

    public function create()
    {
        $jurusan = MasterJurusan::all();
        return view('master_kelas.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'id_jurusan' => 'required|exists:master_jurusan,id_jurusan',
            'sts' => 'required|integer|in:0,1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoPath = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/kelas'), $fotoPath);
        }

        MasterKelas::create([
            'nama_kelas' => $request->nama_kelas,
            'id_jurusan' => $request->id_jurusan,
            'sts' => $request->sts,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('master_kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kelas = MasterKelas::findOrFail($id);
        $jurusan = MasterJurusan::all();
        return view('master_kelas.edit', compact('kelas', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'id_jurusan' => 'required|exists:master_jurusan,id_jurusan',
            'sts' => 'required|integer|in:0,1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        $kelas = MasterKelas::findOrFail($id);
        $fotoPath = $kelas->foto;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fotoPath && file_exists(public_path('uploads/kelas/' . $fotoPath))) {
                unlink(public_path('uploads/kelas/' . $fotoPath));
            }
            $file = $request->file('foto');
            $fotoPath = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/kelas'), $fotoPath);
        }

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'id_jurusan' => $request->id_jurusan,
            'sts' => $request->sts,
            'foto' => $fotoPath,

        ]);

        return redirect()->route('master_kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = MasterKelas::findOrFail($id);
        if ($kelas->foto && file_exists(public_path('uploads/kelas/' . $kelas->foto))) {
            unlink(public_path('uploads/kelas/' . $kelas->foto));
        }
        $kelas->delete();

        return redirect()->route('master_kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $kelas = MasterKelas::findOrFail($request->id);
            $kelas->sts = $request->status;
            $kelas->save();

            // Update status of all students in this class
            $siswaIds = MasterSiswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
            MasterSiswa::whereIn('id_siswa', $siswaIds)->update(['sts' => $request->status]);

            // Update status of corresponding users
            $emails = MasterSiswa::whereIn('id_siswa', $siswaIds)->pluck('email');
            User::whereIn('email', $emails)->update(['status' => $request->status]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateStatusBulk(Request $request)
    {
        try {
            DB::beginTransaction();

            // Update all classes
            MasterKelas::query()->update(['sts' => $request->status]);

            // Update all students' status
            MasterSiswa::query()->update(['sts' => $request->status]);

            // Update all student users' status
            User::where('role', 0)->update(['status' => $request->status]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
