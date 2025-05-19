<?php

namespace App\Http\Controllers;

use App\Models\MasterGuru;
use App\Models\MasterKelas;
use App\Models\MasterSiswa;
use App\Models\MasterJadwal;
use Illuminate\Http\Request;
use App\Exports\JadwalExport;
use App\Imports\JadwalImport;
use App\Models\DetailJadwal;
use App\Models\MasterMateri;
use App\Models\MasterQuiz;

use Maatwebsite\Excel\Facades\Excel;

class MasterJadwalController extends Controller
{
    public function index()
    {
        $jadwals = MasterJadwal::all();
        $gurus = MasterGuru::all();
        $siswas = MasterSiswa::all();
        $kelas = MasterKelas::all();

        return view('master_jadwal.index', compact('jadwals', 'gurus', 'siswas', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string',
            'id_kelas' => 'required|exists:master_kelas,id_kelas',
            'nama_jadwal' => 'required|string|max:255',
            'sts' => 'required|boolean',
            'id_guru' => 'nullable|exists:master_guru,id_guru',
        ]);

        $jadwal = new MasterJadwal();
        $jadwal->hari = $request->hari;
        $jadwal->id_kelas = $request->id_kelas;
        $jadwal->nama_jadwal = $request->nama_jadwal;
        $jadwal->sts = $request->sts;
        $jadwal->type = $request->type ?? 0;
        $jadwal->id_guru = $request->id_guru;
        $jadwal->created_at = now();
        $jadwal->updated_at = now();

        $jadwal->save();

        return redirect()->route('master_jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = MasterJadwal::findOrFail($id);
        $gurus = MasterGuru::all();
        $siswas = MasterSiswa::all();
        $kelas = MasterKelas::all();

        return view('master_jadwal.edit', compact('jadwal', 'gurus', 'siswas', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required|string',
            'id_kelas' => 'required|exists:master_kelas,id_kelas',
            'nama_jadwal' => 'required|string|max:255',
            'sts' => 'required|boolean',
            'id_guru' => 'nullable|exists:master_guru,id_guru',
        ]);

        $jadwal = MasterJadwal::findOrFail($id);
        $jadwal->hari = $request->hari;
        $jadwal->id_kelas = $request->id_kelas;
        $jadwal->nama_jadwal = $request->nama_jadwal;
        $jadwal->sts = $request->sts;
        $jadwal->type = $request->type ?? 0;
        $jadwal->id_guru = $request->id_guru;
        $jadwal->updated_at = now();

        $jadwal->save();

        return redirect()->route('master_jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = MasterJadwal::findOrFail($id);

        // Hapus semua detail terkait sebelum menghapus jadwal utama
        DetailJadwal::where('id_jadwal', $id)->delete();

        // Hapus jadwal utama
        $jadwal->delete();

        return redirect()->route('master_jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }


    public function export()
    {
        return Excel::download(new JadwalExport, 'jadwal.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new JadwalImport, $request->file('file'));

        return redirect()->route('master_jadwal.index')->with('success', 'Jadwal berhasil diimpor.');
    }

    public function detail($id)
    {
        $id = base64_decode($id);
        $detail = DetailJadwal::where('id_jadwal', $id)->with('quiz', 'materi')->get();

        $materi = MasterMateri::all();
        $quiz = MasterQuiz::all();

        return view('master_jadwal.detail', compact('id', 'detail', 'materi', 'quiz'));
    }

    public function detailStore(Request $request, $id)
    {
        $request->validate([
            'jam_in' => 'required|date_format:H:i',
            'jam_out' => 'required|date_format:H:i',
        ]);

        $id = base64_decode($id);

        DetailJadwal::create([
            'id_jadwal' => $id,
            'id_materi' => $request->id_materi ?? 0,
            'id_quiz' => $request->id_quiz ?? 0,
            'jam_in' => $request->jam_in,
            'jam_out' => $request->jam_out,
            'sts' => 1,
        ]);


        return redirect()->route('master_jadwal.detail', base64_encode($id))->with('success', 'Detail Jadwal berhasil ditambahkan.');
    }

    public function detailEdit($id)
    {
        $id = base64_decode($id);
        $data = DetailJadwal::findOrFail($id);
        $materi = MasterMateri::all();
        $quiz = MasterQuiz::all();
        return view('master_jadwal.detail-edit', compact('data', 'materi', 'quiz'));
    }
    public function detailDestroy($id)
    {
        $id = base64_decode($id);
        $detailJadwal = DetailJadwal::findOrFail($id);
        $detailJadwal->delete();
        return redirect()->back()->with('success', 'Detail Jadwal berhasil dihapus.');
    }
    public function detailUpdate(Request $request, $id)
    {
        $request->validate([
            'jam_in' => 'required',
            'jam_out' => 'required',
            'sts' => 'required'
        ]);
        $id = base64_decode($id);
        $detailJadwal = DetailJadwal::findOrFail($id);
        $detailJadwal->update([
            'id_materi' => $request->type == 1 ? $request->id_materi : 0,
            'id_quiz' => $request->type == 2 ? $request->id_quiz : 0,
            'jam_in' => $request->jam_in,
            'jam_out' => $request->jam_out,
            'sts' => $request->sts
        ]);
        return redirect()->route('master_jadwal.detail', base64_encode($detailJadwal->id_jadwal))
            ->with('success', 'Detail Jadwal berhasil diperbarui.');
    }
}
