<?php

namespace App\Http\Controllers;

use App\Exports\DetailNilaiAllExport;
use Illuminate\Http\Request;
use App\Exports\DetailNilaiExport;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanNilaiExport;
use App\Http\Controllers\Controller;
use App\Models\MasterMateri;
use App\Models\MasterQuiz;
use Maatwebsite\Excel\Facades\Excel;

class LaporanNilaiController extends Controller
{
    public function index(Request $request)
    {
        $id_kelas = $request->has('id_kelas') ? base64_decode($request->input('id_kelas')) : null;
        $id = $request->input('id_materi') ? base64_decode($request->input('id_materi')) : ($request->input('id_quiz') ? base64_decode($request->input('id_quiz')) : null);


        $results = collect(); // Kosongkan hasil awal
        if ($id_kelas || $id) {
            $query = DB::table('trans_nilai')
                ->join('master_siswa', 'trans_nilai.id_siswa', '=', 'master_siswa.id_siswa')
                ->join('master_kelas', 'trans_nilai.id_kelas', '=', 'master_kelas.id_kelas')
                ->leftJoin('master_materi', 'trans_nilai.id_materi', '=', 'master_materi.id_materi')
                ->leftJoin('master_quiz', 'trans_nilai.id_quiz', '=', 'master_quiz.id_quiz')
                ->select(
                    'trans_nilai.*',
                    'master_siswa.nama_siswa',
                    'master_kelas.nama_kelas',
                    'master_materi.judul',
                    'master_quiz.nama_quiz',
                    DB::raw("
                        CASE 
                            WHEN master_materi.id_materi IS NOT NULL THEN 'Materi'
                            WHEN master_quiz.id_quiz IS NOT NULL THEN 'Quiz'
                            ELSE 'Tidak Diketahui'
                        END AS tipe
                    ")
                );

            // Terapkan filter
            if ($id_kelas) {
                $query->where('trans_nilai.id_kelas', $id_kelas);
            }

            if ($id) {
                $query->where(function ($q) use ($id) {
                    $q->where('trans_nilai.id_materi', $id)
                        ->orWhere('trans_nilai.id_quiz', $id);
                });
            }

            $results = $query->get();
        }

        // Ambil daftar siswa dan materi/quiz untuk dropdown filter
        $kelasList = DB::table('master_kelas')->select('id_kelas', 'nama_kelas')->get();
        $materiList = MasterMateri::all('id_materi', 'judul');
        $quizList = MasterQuiz::all('id_quiz', 'nama_quiz');

        return view('laporan_nilai.index', compact('results', 'kelasList', 'materiList', 'quizList'));
    }




    public function detail($id)
    {
        $id = base64_decode($id);

        // Ambil detail nilai
        $detail = DB::table('detail_nilai')
            ->join('trans_nilai', 'trans_nilai.id_trans', '=', 'detail_nilai.id_trans_nilai')
            ->join('master_soal', 'master_soal.id_soal', '=', 'detail_nilai.id_soal')
            ->select(
                'detail_nilai.*',
                'trans_nilai.benar',
                'trans_nilai.salah',
                'master_soal.soal',
                'master_soal.pilihan_1',
                'master_soal.pilihan_2',
                'master_soal.pilihan_3',
                'master_soal.pilihan_4',
                'master_soal.jawaban'
            )
            ->where('detail_nilai.id_trans_nilai', $id)
            ->get();

        return view('laporan_nilai.detail', compact('detail'));
    }

    public function detailAll(Request $request)
    {
        $id_kelas = base64_decode($request->id_kelas);
        $id_quiz = $request->id_quiz ? base64_decode($request->id_quiz) : null;
        $id_materi = $request->id_materi ? base64_decode($request->id_materi) : null;

        $results = DB::table('trans_nilai')
            ->join('master_siswa', 'trans_nilai.id_siswa', '=', 'master_siswa.id_siswa')
            ->join('master_kelas', 'trans_nilai.id_kelas', '=', 'master_kelas.id_kelas')
            ->leftJoin('master_materi', 'trans_nilai.id_materi', '=', 'master_materi.id_materi')
            ->leftJoin('master_quiz', 'trans_nilai.id_quiz', '=', 'master_quiz.id_quiz')
            ->leftJoin('detail_nilai', 'trans_nilai.id_trans', '=', 'detail_nilai.id_trans_nilai')
            ->leftJoin('master_soal', 'detail_nilai.id_soal', '=', 'master_soal.id_soal') // Relasi ke master_soal
            ->select(
                'trans_nilai.id_trans',
                'master_siswa.nama_siswa',
                'master_kelas.nama_kelas',
                'master_materi.judul as materi',
                'master_quiz.nama_quiz as quiz',
                'detail_nilai.*',
                'trans_nilai.benar',
                'trans_nilai.salah',
                'master_soal.soal',
                'master_soal.pilihan_1',
                'master_soal.pilihan_2',
                'master_soal.pilihan_3',
                'master_soal.pilihan_4',
                'master_soal.jawaban',
                DB::raw("CASE 
                 WHEN master_materi.id_materi IS NOT NULL THEN 'Materi'
                 WHEN master_quiz.id_quiz IS NOT NULL THEN 'Quiz'
                 ELSE 'Tidak Diketahui'
             END AS tipe")
            )
            ->where('trans_nilai.id_kelas', $id_kelas) // Filter berdasarkan kelas
            ->when($id_quiz, function ($query, $id_quiz) {
                return $query->where('trans_nilai.id_quiz', $id_quiz);
            })
            ->when($id_materi, function ($query, $id_materi) {
                return $query->where('trans_nilai.id_materi', $id_materi);
            })
            ->get();

        return view('laporan_nilai.detail_all', compact('results'));
    }



    public function export(Request $request)
    {
        $id_kelas = $request->input('id_kelas');
        $id = $request->input('id_materi') ? base64_decode($request->input('id_materi')) : ($request->input('id_quiz') ? base64_decode($request->input('id_quiz')) : null);


        return Excel::download(new LaporanNilaiExport($id_kelas, $id), 'laporan_nilai.xlsx');
    }

    public function exportDetail($id)
    {
        return Excel::download(new DetailNilaiExport($id), 'detail_nilai.xlsx');
    }
    public function exportAllDetail(Request $request)
    {
        $id_kelas = $request->query('id_kelas');
        $id_quiz = $request->query('id_quiz');
        $id_materi = $request->query('id_materi');

        // $id = [
        //     'id_kelas' => $id_kelas,
        //     'id_quiz' => $id_quiz,
        //     'id_materi' => $id_materi
        // ];

        // dd($id);
        return Excel::download(new DetailNilaiAllExport($id_kelas, $id_quiz, $id_materi), 'detail_kelas.xlsx');
    }
}
