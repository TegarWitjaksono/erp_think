<?php

namespace App\Http\Controllers;

use App\Models\MapSoal;
use App\Models\TempUjian;
use App\Models\MasterSoal;
use App\Models\TransNilai;
use App\Models\MasterJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TempUjianController extends Controller
{
    /**
     * Store a temporary answer in the temp_ujian table
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function simpanJawabanSementara(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'id_soal' => 'required|integer',
                'id_siswa' => 'required|integer',
                'pilihan' => 'required|integer|min:1|max:4',
            ]);

            // Determine if the ID is for a quiz or materi
            $id_quiz = $request->filled('id_quiz') && $request->id_quiz !== 'null' ? $request->id_quiz : null;
            $id_materi = $request->filled('id_materi') && $request->id_materi !== 'null' ? $request->id_materi : null;

            if (!$id_quiz && !$id_materi) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Quiz atau ID Materi harus disertakan'
                ], 400);
            }

            // Get the correct answer (kunci_jawaban) from master_soal
            $soal = MasterSoal::find($request->id_soal);
            if (!$soal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Soal tidak ditemukan'
                ], 404);
            }

            $kunci_jawaban = $soal->jawaban;

            // Check if a temporary answer already exists for this question
            $tempUjian = TempUjian::where('id_soal', $request->id_soal)
                ->where('id_siswa', $request->id_siswa)
                ->where(function ($query) use ($id_quiz, $id_materi) {
                    if ($id_quiz) {
                        $query->where('id_quiz', $id_quiz);
                    }
                    if ($id_materi) {
                        $query->orWhere('id_materi', $id_materi);
                    }
                })
                ->first();

            if ($tempUjian) {
                // Update existing temporary answer
                $tempUjian->pilihan = $request->pilihan;
                $tempUjian->kunci_jawaban = $kunci_jawaban;
                $tempUjian->updated_at = now();
                $tempUjian->save();
            } else {
                // Create new temporary answer
                $tempUjian = new TempUjian();
                $tempUjian->id_materi = $id_materi;
                $tempUjian->id_quiz = $id_quiz;
                $tempUjian->id_soal = $request->id_soal;
                $tempUjian->id_siswa = $request->id_siswa;
                $tempUjian->pilihan = $request->pilihan;
                $tempUjian->kunci_jawaban = $kunci_jawaban;
                $tempUjian->created_at = now();
                $tempUjian->updated_at = now();
                $tempUjian->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan sementara',
                'data' => $tempUjian
            ]);
        } catch (\Throwable  $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jawaban sementara',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get temporary answers for a specific student and exam
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJawabanSementara(Request $request)
    {
        try {
            $request->validate([
                'id_materi' => 'required|integer',
                'id_siswa' => 'required|integer',
            ]);

            $tempAnswers = TempUjian::where('id_materi', $request->id_materi)
                ->where('id_siswa', $request->id_siswa)
                ->get()
                ->keyBy('id_soal')
                ->map(function ($item) {
                    return $item->pilihan;
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $tempAnswers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan jawaban sementara',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process results after exam completion
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selesaiUjian(Request $request)
    {
        try {
            $request->validate([
                'id_materi' => 'required|integer',
                'id_quiz' => 'required|integer',
                'id_siswa' => 'required|integer',
            ]);

            // Mulai transaksi database
            DB::beginTransaction();

            // Ambil semua jawaban sementara siswa
            $tempAnswers = DB::table('temp_ujian')
                ->where('id_materi', $request->id_materi)
                ->where('id_siswa', $request->id_siswa)
                ->where('id_quiz', $request->id_quiz)
                ->get();

            $id_materi = $request->id_materi;
            $id_quiz = $request->id_quiz;
            $totalQuestions = MapSoal::where(function ($query) use ($id_materi, $id_quiz) {
                if ($id_materi) {
                    $query->where('id_materi', $id_materi);
                }
                if ($id_quiz) {
                    $query->orWhere('id_quiz', $id_quiz);
                }
            })->count();

            $answeredQuestions = $tempAnswers->count();
            $correctAnswers = 0;

            foreach ($tempAnswers as $answer) {
                if ($answer->pilihan == $answer->kunci_jawaban) {
                    $correctAnswers++;
                }
            }

            // Hitung skor (persentase)
            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

            // Ambil id_kelas dari master_materi

            // Ambil data berdasarkan id_quiz atau id_materi di DetailJadwal
            $today = date('l'); // Nama hari dalam bahasa Inggris
            $hariIndo = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            $hariSekarang = $hariIndo[$today] ?? $today;

            $jadwal = MasterJadwal::whereHas('detailJadwal', function ($query) use ($request) {
                if (!empty($request->id_quiz)) {
                    $query->where('id_quiz', $request->id_quiz);
                }
                if (!empty($request->id_materi)) {
                    $query->orWhere('id_materi', $request->id_materi);
                }
            })
                ->where('hari', $hariSekarang)
                ->where('sts', 1)
                ->first();

            $id_kelas = $jadwal->id_kelas ?? null;

            // Simpan data ke trans_nilai
            $idTransNilai = DB::table('trans_nilai')->insertGetId([
                'id_siswa' => $request->id_siswa,
                'id_materi' => $request->id_materi,
                'id_quiz' => $request->id_quiz,
                'id_kelas' => $id_kelas,
                'jum_soal' => $totalQuestions,
                'benar' => $correctAnswers,
                'salah' => $answeredQuestions - $correctAnswers,
                'score' => $score,
                'created_at' => now()
            ]);

            // Simpan detail jawaban ke detail_nilai
            foreach ($tempAnswers as $answer) {
                DB::table('detail_nilai')->insert([
                    'id_trans_nilai' => $idTransNilai,
                    'id_soal' => $answer->id_soal,
                    'pilihan' => $answer->pilihan,
                    'jawaban' => $answer->kunci_jawaban,
                    'created_at' => now()
                ]);
            }


            // Commit transaksi setelah semua operasi berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ujian berhasil diselesaikan',
                'data' => [
                    'total_questions' => $totalQuestions,
                    'answered_questions' => $answeredQuestions,
                    'correct_answers' => $correctAnswers,
                    'score' => $score,
                    'result_id' => $idTransNilai
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan ujian',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get detailed exam results
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExamResults(Request $request)
    {
        try {
            $request->validate([
                'id_materi' => 'required|integer',
                'id_siswa' => 'required|integer',
            ]);

            // Get all temporary answers for this student and material
            $tempAnswers = TempUjian::where('id_materi', $request->id_materi)
                ->where('id_siswa', $request->id_siswa)
                ->get();

            $results = [];
            foreach ($tempAnswers as $answer) {
                $soal = MasterSoal::where('id_soal', $answer->id_soal)->first();
                if ($soal) {
                    $results[] = [
                        'id_soal' => $answer->id_soal,
                        'soal' => $soal->soal,
                        'pilihan_1' => $soal->pilihan_1,
                        'pilihan_2' => $soal->pilihan_2,
                        'pilihan_3' => $soal->pilihan_3,
                        'pilihan_4' => $soal->pilihan_4,
                        'jawaban_benar' => $soal->jawaban,
                        'jawaban_siswa' => $answer->pilihan,
                        'is_correct' => $answer->pilihan == $answer->kunci_jawaban
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan hasil ujian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exam results summary
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExamResultsSummary(Request $request)
    {
        try {
            $request->validate([
                'id_materi' => 'required|integer',
                'id_siswa' => 'required|integer',
            ]);

            $transNilai = TransNilai::where('id_materi', $request->id_materi)
                ->where('id_siswa', $request->id_siswa)
                ->latest()
                ->first();

            if (!$transNilai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hasil ujian tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_questions' => $transNilai->jum_soal,
                    'correct_answers' => $transNilai->benar,
                    'incorrect_answers' => $transNilai->salah,
                    'score' => $transNilai->score
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan ringkasan hasil ujian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show exam results page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showExamResults(Request $request)
    {
        $id_materi = $request->id_materi;
        $id_siswa = $request->id_siswa;

        $transNilai = TransNilai::where('id_materi', $id_materi)
            ->where('id_siswa', $id_siswa)
            ->latest()
            ->first();

        if (!$transNilai) {
            // Jika hasil ujian tidak ditemukan, tetap kembalikan view dengan pesan kesalahan
            return view('kelas.hasil_ujian', [
                'result' => null,
                'detailedResults' => collect(),
                'materi' => DB::table('master_materi')->where('id_materi', $id_materi)->first(),
                'error' => 'Hasil ujian tidak ditemukan'
            ]);
        }

        $tempAnswers = TempUjian::where('id_materi', $id_materi)
            ->where('id_siswa', $id_siswa)
            ->get();

        $detailedResults = collect();
        foreach ($tempAnswers as $answer) {
            $soal = MasterSoal::where('id_soal', $answer->id_soal)->first();
            if ($soal) {
                $detailedResults->push([
                    'id_soal' => $answer->id_soal,
                    'soal' => $soal->soal,
                    'pilihan_1' => $soal->pilihan_1,
                    'pilihan_2' => $soal->pilihan_2,
                    'pilihan_3' => $soal->pilihan_3,
                    'pilihan_4' => $soal->pilihan_4,
                    'jawaban_benar' => $soal->jawaban,
                    'jawaban_siswa' => $answer->pilihan,
                    'is_correct' => $answer->pilihan == $answer->kunci_jawaban
                ]);
            }
        }

        return view('kelas.hasil_ujian', [
            'result' => $transNilai,
            'detailedResults' => $detailedResults,
            'materi' => DB::table('master_materi')->where('id_materi', $id_materi)->first(),
            'error' => null
        ]);
    }
}
