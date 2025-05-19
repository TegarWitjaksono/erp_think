<?php

namespace App\Http\Controllers;

use App\Models\MapSoal;
use App\Models\TempUjian;
use App\Models\MasterQuiz;
use App\Models\MasterSoal;
use App\Models\MasterKelas;
use App\Models\TransNilai;
use App\Models\DetailJadwal;
use App\Models\MasterJadwal;
use App\Models\MasterMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FdController extends Controller
{
    public function bfrlogin()
    {
        return view('depan.before_login');
    }

    public function afterlogin()
    {
        $email = Auth::user()->email;
        $student = DB::table('master_siswa')->where('email', $email)->first();
        $daftar_hari = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );
        $date = date('Y-m-d');
        $namahari = date('l', strtotime($date));
        $hari_indonesia = $daftar_hari[$namahari];

        // Get jadwal based on student's class and current day
        $jadwal = DB::table('master_jadwal')
            ->join('master_kelas', 'master_jadwal.id_kelas', '=', 'master_kelas.id_kelas')
            ->leftJoin('detail_jadwal', 'master_jadwal.id_jadwal', '=', 'detail_jadwal.id_jadwal')
            ->leftJoin('master_materi', 'detail_jadwal.id_materi', '=', 'master_materi.id_materi')
            ->where('master_jadwal.id_kelas', $student->id_kelas)
            ->where('master_jadwal.hari', $hari_indonesia)
            ->where('master_jadwal.sts', '1')
            ->where(function ($query) {
                $query->where('detail_jadwal.sts', '1')
                    ->orWhereNull('detail_jadwal.sts');
            })
            ->select(
                'master_materi.id_materi',
                'master_materi.judul',
                'detail_jadwal.jam_in',
                'detail_jadwal.jam_out',
                'master_jadwal.nama_jadwal',
                'master_kelas.nama_kelas',
                'master_jadwal.id_jadwal'
            )
            ->get();

        return view('depan.after_login', ['jadwal' => $jadwal]);
    }

    public function kelasDetail($id)
    {
        $id = base64_decode($id); // Get materi data
        $data = DetailJadwal::with('quiz', 'materi.kategori', 'jadwal')
            ->where('id_jadwal', $id)
            ->get();
        // Get jadwal data using Eloquent relationships
        $daftar_hari = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );
        $date = date('Y-m-d');
        $namahari = date('l', strtotime($date));
        $hari_indonesia = $daftar_hari[$namahari];

        // Use Eloquent with eager loading
        $jadwal = MasterJadwal::with(['detailJadwal.materi'])
            ->where('id_kelas', $id)
            ->where('hari', $hari_indonesia)
            ->where('sts', 1)
            ->get();


        return view('kelas.detail', compact('data', 'jadwal'));
    }

    public function mulaiUjian($id)
    {
        $id = base64_decode($id);
        \Log::info('Starting mulaiUjian with ID: ' . $id);

        // Get student data
        $email = Auth::user()->email;
        $student = DB::table('master_siswa')->where('email', $email)->first();
        $id_siswa = $student ? $student->id_siswa : null;
        $id_kelas = $student ? $student->id_kelas : null;

        // Cek apakah ID adalah materi atau quiz untuk mengambil data tambahan
        $materi = MasterMateri::with(['kelas'])->where('id_materi', $id)->first();
        $quiz = MasterQuiz::where('id_quiz', $id)->first();

        $id_materi = $materi ? $materi->id_materi : null;
        $id_quiz = $quiz ? $quiz->id_quiz : null;

        // Cek apakah siswa sudah pernah mengerjakan ujian ini
        $sudahMengerjakan = TransNilai::where('id_siswa', $id_siswa)
            ->where(function ($query) use ($id_materi, $id_quiz) {
                if ($id_materi) {
                    $query->where('id_materi', $id_materi);
                }
                if ($id_quiz) {
                    $query->orWhere('id_quiz', $id_quiz);
                }
            })
            ->exists();

        if ($sudahMengerjakan) {
            return redirect()->back()->with('ujian_error', 'Anda sudah pernah mengerjakan ujian ini dan tidak dapat mengerjakannya kembali.');
        }

        // Cek apakah ID adalah id_quiz atau id_materi
        $mapSoal = MapSoal::where('id_materi', $id)->orWhere('id_quiz', $id)->get();

        if ($mapSoal->isNotEmpty()) {
            $soalIds = $mapSoal->pluck('id_soal')->toArray();
            \Log::info('Question IDs from MapSoal: ' . json_encode($soalIds));

            $soal = MasterSoal::whereIn('id_soal', $soalIds)
                ->where('sts', 1)
                ->with(['kategoriSoal', 'kategoriJawaban'])
                ->get();

            // Acak urutan soal menggunakan collection shuffle
            $soal = $soal->shuffle();

            // Konversi kembali ke collection setelah pengacakan
            $soal = collect($soal->values()->all());

            \Log::info('Questions shuffled. New order IDs: ' . json_encode($soal->pluck('id_soal')->toArray()));
        } else {
            \Log::warning('No questions found in MapSoal for ID: ' . $id);
            return redirect()->back()->with('error', 'Tidak ada soal yang tersedia untuk ID ini.');
        }

        $durasi = $quiz ? $quiz->durasi : ($materi ? $materi->durasi : 30);

        // Cek jawaban sementara yang sudah tersimpan
        $temp_jawaban = TempUjian::where(function ($query) use ($id_materi, $id_quiz) {
            if ($id_materi) {
                $query->where('id_materi', $id_materi);
            }
            if ($id_quiz) {
                $query->orWhere('id_quiz', $id_quiz);
            }
        })
            ->where('id_siswa', $id_siswa)
            ->pluck('pilihan', 'id_soal')
            ->toArray();

        return view('kelas.mulaiujian', compact('soal', 'materi', 'quiz', 'id_materi', 'id_quiz', 'id_siswa', 'durasi', 'temp_jawaban'));
    }



    public function setFromDetail(Request $request)
    {
        // Set session flag to indicate we're coming from detail page
        session(['from_detail' => true]);

        return response()->json(['success' => true]);
    }

    public function simpanJawabanSementara(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'id_soal' => 'required|integer',
                'id_siswa' => 'required|integer',
                'pilihan' => 'required|integer|min:1|max:4',
            ]);

            // Get id_quiz or set default to 0 if it's a materi
            $id_quiz = $request->filled('id_quiz') ? $request->id_quiz : 0;

            // Get id_materi or set default to 0 if it's a quiz
            $id_materi = $request->filled('id_materi') ? $request->id_materi : 0;

            // Get the correct answer (kunci_jawaban) from master_soal
            $soal = DB::table('master_soal')->where('id_soal', $request->id_soal)->first();
            if (!$soal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Soal tidak ditemukan'
                ], 404);
            }
            $kunci_jawaban = $soal->jawaban;

            // Check if a temporary answer already exists
            $tempUjian = TempUjian::where('id_soal', $request->id_soal)
                ->where('id_siswa', $request->id_siswa)
                ->where('id_materi', $id_materi)
                ->where('id_quiz', $id_quiz)
                ->first();

            if ($tempUjian) {
                // Update existing record
                $tempUjian->pilihan = $request->pilihan;
                $tempUjian->kunci_jawaban = $kunci_jawaban;
                $tempUjian->updated_at = now();
                $tempUjian->save();
            } else {
                // Create new record
                $tempUjian = new TempUjian();
                $tempUjian->id_materi = $id_materi;
                $tempUjian->id_quiz = $id_quiz;
                $tempUjian->id_soal = $request->id_soal;
                $tempUjian->id_siswa = $request->id_siswa;
                $tempUjian->pilihan = $request->pilihan;
                $tempUjian->kunci_jawaban = $kunci_jawaban;
                $tempUjian->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan sementara',
                'data' => $tempUjian
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jawaban sementara',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function selesaiUjian(Request $request)
    {
        try {
            $request->validate([
                'id_siswa' => 'required|integer',
            ]);

            $id_siswa = $request->id_siswa;
            $id_materi = $request->filled('id_materi') ? $request->id_materi : 0;
            $id_quiz = $request->filled('id_quiz') ? $request->id_quiz : 0;

            // Check if student has already completed this exam
            $existingResult = TransNilai::where('id_siswa', $id_siswa)
                ->where(function ($query) use ($id_materi, $id_quiz) {
                    if ($id_materi) {
                        $query->where('id_materi', $id_materi);
                    }
                    if ($id_quiz) {
                        $query->orWhere('id_quiz', $id_quiz);
                    }
                })
                ->first();

            if ($existingResult) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah pernah mengerjakan ujian ini'
                ], 400);
            }

            // Retrieve all temporary answers for this student and quiz/materi
            $temp_jawaban = TempUjian::where('id_siswa', $id_siswa)
                ->where(function ($query) use ($id_materi, $id_quiz) {
                    if ($id_materi) {
                        $query->where('id_materi', $id_materi);
                    }
                    if ($id_quiz) {
                        $query->orWhere('id_quiz', $id_quiz);
                    }
                })
                ->get();

            // Calculate score
            $totalSoal = $temp_jawaban->count();
            $benar = 0;
            $salah = 0;

            foreach ($temp_jawaban as $jawaban) {
                if ($jawaban->pilihan == $jawaban->kunci_jawaban) {
                    $benar++;
                } else {
                    $salah++;
                }
            }

            // Calculate score (0-100)
            $score = $totalSoal > 0 ? ($benar / $totalSoal) * 100 : 0;

            // Get student's class ID
            $student = DB::table('master_siswa')->where('id_siswa', $id_siswa)->first();
            $id_kelas = $student ? $student->id_kelas : null;

            // Save to TransNilai
            $transNilai = new TransNilai();
            $transNilai->id_siswa = $id_siswa;
            $transNilai->id_materi = $id_materi;
            $transNilai->id_quiz = $id_quiz;
            $transNilai->id_kelas = $id_kelas;
            $transNilai->jum_soal = $totalSoal;
            $transNilai->benar = $benar;
            $transNilai->salah = $salah;
            $transNilai->score = $score;
            $transNilai->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Ujian berhasil diselesaikan',
                'data' => [
                    'total_soal' => $totalSoal,
                    'benar' => $benar,
                    'salah' => $salah,
                    'score' => $score
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyelesaikan ujian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function jadwalSiswa()
    {
        $email = Auth::user()->email;


        // Ambil data siswa beserta jadwal dan detail jadwal dalam satu query
        $data = DB::table('detail_jadwal')
            ->join('master_jadwal', 'detail_jadwal.id_jadwal', '=', 'master_jadwal.id_jadwal')
            ->join('master_siswa', 'master_jadwal.id_kelas', '=', 'master_siswa.id_kelas')
            ->where('master_siswa.email', $email)
            ->select(
                'master_jadwal.id_jadwal',
                'master_jadwal.hari',
                'master_jadwal.nama_jadwal',
                DB::raw('MIN(detail_jadwal.jam_in) as jam_in'),
                DB::raw('MIN(detail_jadwal.jam_out) as jam_out')
            )
            ->groupBy('master_jadwal.id_jadwal', 'master_jadwal.hari', 'master_jadwal.nama_jadwal')
            ->get();

        return view('kelas.jadwal_siswa', compact('data'));
    }
}
