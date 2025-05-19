<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MasterGuru;
use App\Models\MasterJadwal;
use App\Models\MasterJurusan;
use App\Models\MasterKategori;
use App\Models\MasterKelas;
use App\Models\MasterMateri;
use App\Models\MasterQuiz;
use App\Models\MasterSiswa;
use App\Models\MasterSoal;
use App\Models\Siswa;
use App\Models\Materi;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Soal;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $guruCount = MasterGuru::count();
        $siswaCount = MasterSiswa::count();
        $materiCount = MasterMateri::count();
        $userCount = User::count();
        $kelasCount = MasterKelas::count();
        $soalCount = MasterSoal::count();
        $jurusanCount = MasterJurusan::count();
        $quizCount = MasterQuiz::count();
        $jadwalCount = MasterJadwal::where('sts', 1)->count(); // Menghitung jadwal yang aktif
        $kategoriCount = MasterKategori::count();

        return view('home', compact('guruCount', 'siswaCount', 'materiCount', 'userCount', 'kelasCount', 'soalCount', 'jurusanCount', 'quizCount', 'jadwalCount', 'kategoriCount'));
    }
}