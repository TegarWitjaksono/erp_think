<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterKelas;
use App\Models\MasterKategori;
use App\Models\MasterMateri;
use Illuminate\Http\Request;

class MasterMateriController extends Controller
{
    public function index()
    {
        $materi = MasterMateri::with('kelas', 'kategori')->get();
        $kelas = MasterKelas::all();
        $kategori = MasterKategori::all();
        return view('master_materi.index', compact('materi', 'kelas', 'kategori'));
    }

    public function create()
    {
        $kelas = MasterKelas::all();
        $kategori = MasterKategori::all();
        return view('master_materi.create', compact('kelas', 'kategori'));
    }

    public function store(Request $request)
    {
        $kategori = MasterKategori::find($request->id_kategori)->nama_kategori;

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|exists:master_kategori,id_kategori',
            'id_kelas' => 'required|exists:master_kelas,id_kelas',
            'sts' => 'required|integer|in:0,1',
            'img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'file_materi' => 'nullable',
            'durasi' => 'required|integer'
        ]);

        // Simpan Logo
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $imgLogo = time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads/logo'), $imgLogo);
        }

        // Menyimpan file_materi berdasarkan kategori
        $fileMateri = null;
        if ($kategori === "foto" && $request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $fileMateri = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/materi'), $fileMateri);
        } elseif ($kategori === "text") {
            $fileMateri = $request->file_materi;
        } elseif ($kategori === "video") {
            $fileMateri = $request->file_materi;
        } elseif ($kategori === "link") {
            $fileMateri = $request->file_materi;
        } elseif ($request->hasFile('file_materi') && $kategori === "document") {
            $file = $request->file('file_materi');
            $fileMateri = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dokumen'), $fileMateri);
        } elseif ($request->hasFile('file_materi') && $kategori === "audio") { // Add this block
            $file = $request->file('file_materi');
            $fileMateri = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/audio'), $fileMateri);
        }

        // Simpan ke Database
        MasterMateri::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'id_kategori' => $request->id_kategori,
            'id_kelas' => $request->id_kelas,
            'sts' => $request->sts,
            'img' => $imgLogo,
            'file_materi' => $fileMateri,
            'durasi' => $request->durasi
        ]);

        return redirect()->route('master_materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }



    public function edit($id)
    {
        $materi = MasterMateri::findOrFail($id);
        $kelas = MasterKelas::all();
        $kategori = MasterKategori::all();
        return view('master_materi.edit', compact('materi', 'kelas', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $materi = MasterMateri::findOrFail($id);
        $kategori = MasterKategori::find($request->id_kategori)->nama_kategori;

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|exists:master_kategori,id_kategori',
            'id_kelas' => 'required|exists:master_kelas,id_kelas',
            'sts' => 'required|integer|in:0,1',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_materi' => 'nullable',
            'durasi' => 'required|integer'
        ]);

        // Update Logo jika ada file baru
        if ($request->hasFile('img')) {
            // Hapus gambar lama
            if ($materi->img && file_exists(public_path('uploads/logo/' . $materi->img))) {
                unlink(public_path('uploads/logo/' . $materi->img));
            }

            // Simpan gambar baru
            $img = $request->file('img');
            $imgLogo = time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads/logo'), $imgLogo);
        } else {
            $imgLogo = $materi->img;
        }


        $fileMateri = $materi->file_materi;
        if ($kategori === "foto" && $request->hasFile('file_materi')) {

            if ($materi->file_materi && file_exists(public_path('uploads/materi/' . $materi->file_materi))) {
                unlink(public_path('uploads/materi/' . $materi->file_materi));
            }


            $file = $request->file('file_materi');
            $fileMateri = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/materi'), $fileMateri);
        } elseif ($kategori === "text") {
            $fileMateri = $request->file_materi;
        } elseif ($kategori === "video") {
            $fileMateri = $request->file_materi;
        } elseif ($kategori === "link") {
            $fileMateri = $request->file_materi;
        } elseif ($request->hasFile('file_materi') && $kategori === "document") {
            if ($materi->file_materi && file_exists(public_path('uploads/dokumen/' . $materi->file_materi))) {
                unlink(public_path('uploads/dokumen/' . $materi->file_materi));
            }

            $file = $request->file('file_materi');
            $fileMateri = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dokumen'), $fileMateri);
        } elseif ($request->hasFile('file_materi') && $kategori === "audio") { // Add this block
            if ($materi->file_materi && file_exists(public_path('uploads/audio/' . $materi->file_materi))) {
                unlink(public_path('uploads/audio/' . $materi->file_materi));
            }
            $file = $request->file('file_materi');
            $fileMateri = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/audio'), $fileMateri);
        }

        $materi->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'id_kategori' => $request->id_kategori,
            'id_kelas' => $request->id_kelas,
            'sts' => $request->sts,
            'img' => $imgLogo,
            'file_materi' => $fileMateri,
            'durasi' => $request->durasi
        ]);

        return redirect()->route('master_materi.index')->with('success', 'Materi berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $materi = MasterMateri::findOrFail($id);

        $materi->delete();

        return redirect()->route('master_materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
