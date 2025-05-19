<?php

namespace App\Http\Controllers;

use App\Models\MasterJurusan;
use Illuminate\Http\Request;

class MasterJurusanController extends Controller
{
    public function index()
    {
        $jurusan = MasterJurusan::all();
        return view('master_jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('master_jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
        ]);

        MasterJurusan::create($request->all());

        return redirect()->route('master_jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jurusan = MasterJurusan::findOrFail($id);
        return view('master_jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
        ]);

        $jurusan = MasterJurusan::findOrFail($id);
        $jurusan->update($request->all());

        return redirect()->route('master_jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurusan = MasterJurusan::findOrFail($id);
        $jurusan->delete();

        return redirect()->route('master_jurusan.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}