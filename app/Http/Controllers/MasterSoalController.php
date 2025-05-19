<?php

namespace App\Http\Controllers;

use App\Models\MasterSoal;
use App\Models\MasterMateri;
use Illuminate\Http\Request;
use App\Models\MasterKategori;
use Illuminate\Support\Facades\DB;

class MasterSoalController extends Controller
{
    public function index()
    {
        $soal = MasterSoal::with('materi', 'kategoriSoal', 'kategoriJawaban')->get();
        $materi = MasterMateri::all();
        $kategori = MasterKategori::all();
        return view('soal.index', compact('soal', 'materi', 'kategori'));
    }

    public function store(Request $request)
    {
        // Existing validation rules
        $request->validate([
            // 'id_materi' => 'required|exists:master_materi,id_materi',
            'id_kategori_soal' => 'required|exists:master_kategori,id_kategori',
            'id_kategori_jawaban' => 'required|exists:master_kategori,id_kategori',
            'sts' => 'required|integer|in:0,1',
            'bobot' => 'required|integer',
            'jawaban' => 'required|integer',
        ]);

        // Get kategori type
        $kategoriSoal = DB::table('master_kategori')
            ->where('id_kategori', $request->id_kategori_soal)
            ->value('nama_kategori');

        // Add conditional validation based on kategori
        if (in_array($kategoriSoal, ['document', 'audio'])) {
            $request->validate([
                'soal' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,mp3,wav,ogg|max:10240'
            ]);
        } elseif ($kategoriSoal === 'foto') {
            $request->validate([
                'soal' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048'
            ]);
        } elseif ($kategoriSoal === 'video') {
            $request->validate([
                'soal' => 'required|url'
            ]);
        } else {
            $request->validate([
                'soal' => 'required|string'
            ]);
        }

        $kategoriJawaban = DB::table('master_kategori')->where('id_kategori', $request->id_kategori_jawaban)->value('nama_kategori');

        // Valid file extensions
        $validExtensions = [
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            'audio' => ['mp3', 'wav', 'ogg'],
            'foto' => ['jpg', 'jpeg', 'png', 'gif']
        ];

        // Handle soal based on category
        if ($kategoriSoal == 'foto' && $request->hasFile('soal')) {
            $soalFile = $request->file('soal');
            $soalFileName = time() . '.' . $soalFile->getClientOriginalExtension();
            $soalFile->move(public_path('uploads/soal'), $soalFileName);
            $soal = 'uploads/soal/' . $soalFileName;
        } elseif ($kategoriSoal == 'document' && $request->hasFile('soal')) {
            $soalFile = $request->file('soal');
            $extension = $soalFile->getClientOriginalExtension();
            if (in_array($extension, $validExtensions['document'])) {
                $soalFileName = time() . '.' . $extension;
                $soalFile->move(public_path('uploads/dokumen'), $soalFileName);
                $soal = 'uploads/dokumen/' . $soalFileName;
            }
        } elseif ($kategoriSoal == 'audio' && $request->hasFile('soal')) {
            $soalFile = $request->file('soal');
            $extension = $soalFile->getClientOriginalExtension();
            if (in_array($extension, $validExtensions['audio'])) {
                $soalFileName = time() . '.' . $extension;
                $soalFile->move(public_path('uploads/audio'), $soalFileName);
                $soal = 'uploads/audio/' . $soalFileName;
            }
        } elseif ($kategoriSoal == 'video') {
            $soal = $request->soal;
        } else {
            $soal = $request->soal;
        }

        // Handle pilihan (answers) based on category
        $pilihan = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("pilihan_{$i}")) {
                $pilihanFile = $request->file("pilihan_{$i}");
                $extension = $pilihanFile->getClientOriginalExtension();
                $pilihanFileName = time() . "_{$i}." . $extension;

                if ($kategoriJawaban == 'foto' && in_array($extension, $validExtensions['foto'])) {
                    $pilihanFile->move(public_path('uploads/jawaban'), $pilihanFileName);
                    $pilihan[$i] = 'uploads/jawaban/' . $pilihanFileName;
                } elseif ($kategoriJawaban == 'document' && in_array($extension, $validExtensions['document'])) {
                    $pilihanFile->move(public_path('uploads/dokumen'), $pilihanFileName);
                    $pilihan[$i] = 'uploads/dokumen/' . $pilihanFileName;
                } elseif ($kategoriJawaban == 'audio' && in_array($extension, $validExtensions['audio'])) {
                    $pilihanFile->move(public_path('uploads/audio'), $pilihanFileName);
                    $pilihan[$i] = 'uploads/audio/' . $pilihanFileName;
                }
            } elseif ($kategoriJawaban == 'video') {
                $request->validate(["pilihan_{$i}" => 'required|url']);
                $pilihan[$i] = $request->input("pilihan_{$i}");
            } else {
                $request->validate(["pilihan_{$i}" => 'required|string']);
                $pilihan[$i] = $request->input("pilihan_{$i}");
            }
        }

        // Save to database
        MasterSoal::create([
            'id_materi' => "0",
            'id_kategori_soal' => $request->id_kategori_soal,
            'id_kategori_jawaban' => $request->id_kategori_jawaban,
            'soal' => $soal,
            'pilihan_1' => $pilihan[1],
            'pilihan_2' => $pilihan[2],
            'pilihan_3' => $pilihan[3],
            'pilihan_4' => $pilihan[4],
            'sts' => $request->sts,
            'bobot' => $request->bobot,
            'jawaban' => $request->jawaban,
            'type' => $request->type
        ]);

        return redirect()->route('master_soal.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $soal = MasterSoal::findOrFail($id);
        $materi = MasterMateri::all();
        $kategori = MasterKategori::all();
        return view('soal.edit', compact('soal', 'materi', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        // Existing validation rules
        $request->validate([
            'id_materi' => 'required|exists:master_materi,id_materi',
            'id_kategori_soal' => 'required|exists:master_kategori,id_kategori',
            'id_kategori_jawaban' => 'required|exists:master_kategori,id_kategori',
            'sts' => 'required|integer|in:0,1',
            'bobot' => 'required|integer',
            'jawaban' => 'required|integer',
        ]);

        // Get kategori type
        $kategoriSoal = DB::table('master_kategori')
            ->where('id_kategori', $request->id_kategori_soal)
            ->value('nama_kategori');

        // Add conditional validation if file is being uploaded
        if ($request->hasFile('soal')) {
            if (in_array($kategoriSoal, ['document', 'audio'])) {
                $request->validate([
                    'soal' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,mp3,wav,ogg|max:10240'
                ]);
            } elseif ($kategoriSoal === 'foto') {
                $request->validate([
                    'soal' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048'
                ]);
            }
        } elseif ($kategoriSoal === 'video') {
            $request->validate([
                'soal' => 'required|url'
            ]);
        } elseif ($kategoriSoal === 'text') {
            $request->validate([
                'soal' => 'required|string'
            ]);
        }

        $soal = MasterSoal::findOrFail($id);
        $kategoriJawaban = DB::table('master_kategori')->where('id_kategori', $request->id_kategori_jawaban)->value('nama_kategori');

        $validExtensions = [
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            'audio' => ['mp3', 'wav', 'ogg'],
            'foto' => ['jpg', 'jpeg', 'png', 'gif']
        ];

        // Update soal based on category
        if ($request->hasFile('soal')) {
            $soalFile = $request->file('soal');
            $extension = $soalFile->getClientOriginalExtension();
            $soalFileName = time() . '.' . $extension;

            if ($kategoriSoal == 'foto' && in_array($extension, $validExtensions['foto'])) {
                $soalFile->move(public_path('uploads/soal'), $soalFileName);
                $soal->soal = 'uploads/soal/' . $soalFileName;
            } elseif ($kategoriSoal == 'document' && in_array($extension, $validExtensions['document'])) {
                $soalFile->move(public_path('uploads/dokumen'), $soalFileName);
                $soal->soal = 'uploads/dokumen/' . $soalFileName;
            } elseif ($kategoriSoal == 'audio' && in_array($extension, $validExtensions['audio'])) {
                $soalFile->move(public_path('uploads/audio'), $soalFileName);
                $soal->soal = 'uploads/audio/' . $soalFileName;
            }
        } elseif ($kategoriSoal == 'video') {
            $soal->soal = $request->soal;
        } elseif ($kategoriSoal == 'text') {
            $soal->soal = $request->soal;
        }

        // Update pilihan answers
        for ($i = 1; $i <= 4; $i++) {
            $pilihanField = "pilihan_{$i}";

            if ($request->hasFile($pilihanField)) {
                $pilihanFile = $request->file($pilihanField);
                $extension = $pilihanFile->getClientOriginalExtension();
                $pilihanFileName = time() . "_{$i}." . $extension;

                if ($kategoriJawaban == 'foto' && in_array($extension, $validExtensions['foto'])) {
                    $pilihanFile->move(public_path('uploads/jawaban'), $pilihanFileName);
                    $soal->$pilihanField = 'uploads/jawaban/' . $pilihanFileName;
                } elseif ($kategoriJawaban == 'document' && in_array($extension, $validExtensions['document'])) {
                    $pilihanFile->move(public_path('uploads/dokumen'), $pilihanFileName);
                    $soal->$pilihanField = 'uploads/dokumen/' . $pilihanFileName;
                } elseif ($kategoriJawaban == 'audio' && in_array($extension, $validExtensions['audio'])) {
                    $pilihanFile->move(public_path('uploads/audio'), $pilihanFileName);
                    $soal->$pilihanField = 'uploads/audio/' . $pilihanFileName;
                }
            } elseif ($kategoriJawaban == 'video' || $kategoriJawaban == 'text') {
                $soal->$pilihanField = $request->$pilihanField;
            }
        }

        // Update other fields
        $soal->id_materi = $request->id_materi;
        $soal->id_kategori_soal = $request->id_kategori_soal;
        $soal->id_kategori_jawaban = $request->id_kategori_jawaban;
        $soal->sts = $request->sts;
        $soal->bobot = $request->bobot;
        $soal->jawaban = $request->jawaban;
        $soal->type = $request->type;
        $soal->save();

        return redirect()->route('master_soal.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $soal = MasterSoal::findOrFail($id);
        $soal->delete();

        return redirect()->route('master_soal.index')->with('success', 'Soal berhasil dihapus.');
    }

    public function mulaiUjian()
    {
        $soal = MasterSoal::with('materi', 'kategoriSoal', 'kategoriJawaban')->get();
        return view('kelas.mulaiujian', compact('soal'));
    }
}
