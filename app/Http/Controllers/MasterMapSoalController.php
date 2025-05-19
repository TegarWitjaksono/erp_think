<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MapSoal;
use App\Models\MasterMateri;
use App\Models\MasterQuiz;
use App\Models\MasterSoal;
use Illuminate\Http\Request;

class MasterMapSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $soals = MasterSoal::all();
        $maps = MapSoal::with('soal', 'materi', 'quiz')->get();
        return view('map_soal.index', compact('maps', 'soals'));
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
        // Decode JSON ke dalam array
        $selectedIds = json_decode($request->selected_ids, true);

        if (!is_array($selectedIds) || empty($selectedIds)) {
            return redirect()->back()->withErrors("Harap pilih setidaknya satu soal.");
        }

        // Validasi input
        $request->validate([
            'selected_ids' => 'required',
        ]);

        // Mengambil ID materi dan quiz
        $idMateri = $request->id_materi ?? 0;
        $idQuiz = $request->id_quiz ?? 0;

        // Mengecek jika soal yang dipilih ada yang berbobot atau tidak
        $hasBobot = MasterSoal::whereIn('id_soal', $selectedIds)
            ->where('bobot', '!=', 0)
            ->exists();

        $hasTidakBobot = MasterSoal::whereIn('id_soal', $selectedIds)
            ->where('bobot', 0)
            ->exists();

        // Cek apakah ada soal berbobot yang dipilih dan materi sudah memilih soal tidak berbobot
        if ($hasBobot && $this->isMateriOrQuizAlreadyHasTidakBobot($idMateri, $idQuiz)) {
            return redirect()->back()->withErrors("Materi atau Quiz ini sudah memiliki soal tidak berbobot, tidak bisa memilih soal berbobot.");
        }

        // Cek apakah ada soal tidak berbobot yang dipilih dan materi sudah memilih soal berbobot
        if ($hasTidakBobot && $this->isMateriOrQuizAlreadyHasBobot($idMateri, $idQuiz)) {
            return redirect()->back()->withErrors("Materi atau Quiz ini sudah memiliki soal berbobot, tidak bisa memilih soal tidak berbobot.");
        }

        // Loop untuk menyimpan soal
        foreach ($selectedIds as $id_soal) {
            MapSoal::create([
                'id_soal' => $id_soal,
                'id_materi' => $idMateri,
                'id_quiz' => $idQuiz,
            ]);
        }

        return redirect()->route('master_map_soal.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    // Fungsi untuk mengecek apakah materi atau quiz sudah memiliki soal tidak berbobot
    private function isMateriOrQuizAlreadyHasTidakBobot($idMateri, $idQuiz)
    {
        return MapSoal::where('id_materi', $idMateri)
            ->whereHas('soal', function ($query) {
                $query->where('bobot', 0);
            })
            ->exists()
            || MapSoal::where('id_quiz', $idQuiz)
            ->whereHas('soal', function ($query) {
                $query->where('bobot', 0);
            })
            ->exists();
    }

    // Fungsi untuk mengecek apakah materi atau quiz sudah memiliki soal berbobot
    private function isMateriOrQuizAlreadyHasBobot($idMateri, $idQuiz)
    {
        return MapSoal::where('id_materi', $idMateri)
            ->whereHas('soal', function ($query) {
                $query->where('bobot', '!=', 0);
            })
            ->exists()
            || MapSoal::where('id_quiz', $idQuiz)
            ->whereHas('soal', function ($query) {
                $query->where('bobot', '!=', 0);
            })
            ->exists();
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
        // Ambil data map soal berdasarkan ID
        $map = MapSoal::findOrFail($id);

        // Ambil data soal untuk dropdown
        $soals = MasterSoal::all();

        // Ambil data materi dan quiz untuk dropdown
        $materi = MasterMateri::all();
        $quiz = MasterQuiz::all();

        return view('map_soal.edit', compact('map', 'soals', 'materi', 'quiz'));
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
            'id_soal' => 'required|exists:master_soal,id_soal'
        ]);

        $map = MapSoal::findOrFail($id);

        $map->update([
            'id_soal' => $request->id_soal,
            'id_materi' => $request->id_materi ?? 0, // Jika tidak ada, default 0
            'id_quiz' => $request->id_quiz ?? 0 // Jika tidak ada, default 0
        ]);


        return redirect()->route('master_map_soal.index')->with('success', 'Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $map = MapSoal::findOrFail($id);
        $map->delete();

        return redirect()->route('master_map_soal.index')->with('success', 'Data berhasil dihapus.');
    }

    public function get_data(Request $request)
    {
        $tipe = $request->query('tipe');

        if ($tipe == 'Materi') {
            $data = MasterMateri::all();
        } elseif ($tipe == 'Quiz') {
            $data = MasterQuiz::all();
        } else {
            return response()->json(['error' => 'Tipe tidak valid'], 400);
        }

        return response()->json($data);
    }
    public function filterSoalByBobot(Request $request)
    {
        $bobot = $request->input('bobot');
        if ($bobot == 0) {
            $soals = MasterSoal::Where('bobot', 0)->get();
        } else {
            $soals = MasterSoal::where('bobot', '!=', 0)->get();
        }


        return response()->json([
            'html' => view('map_soal.partials.soal_table', compact('soals'))->render()
        ]);
    }
}
