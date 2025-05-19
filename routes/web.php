<?php


use App\Models\MasterGuru;
use App\Models\MasterJadwal;
use App\Exports\JurusanExport;
use App\Exports\TemplateJurusanExport;
use App\Imports\JurusanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FdController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TransController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TempUjianController;
use App\Http\Controllers\MasterGuruController;
use App\Http\Controllers\MasterQuizController;
use App\Http\Controllers\MasterSoalController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\MasterKelasController;
use App\Http\Controllers\MasterSiswaController;
use App\Http\Controllers\LaporanNilaiController;
use App\Http\Controllers\MasterJadwalController;
use App\Http\Controllers\MasterJurusanController;
use App\Http\Controllers\MasterMapSoalController;
use App\Http\Controllers\GuruExportImportController;
use App\Http\Controllers\SoalExportImportController;
use App\Http\Controllers\KelasExportImportController;
use App\Http\Controllers\SiswaExportImportController;
use App\Http\Controllers\UsersExportImportController;
use App\Http\Controllers\MateriExportImportController;
use App\Http\Controllers\MasterMateriController;  // Keep this line if needed
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// REGISTER
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register/action', [RegisterController::class, 'actionregister'])->name('actionregister');


// Route untuk login
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/actionLogin', [LoginController::class, 'actionLogin'])->name('actionLogin');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/changePassword', [LoginController::class, 'changeView'])->name('change.view');
    Route::post('/change-password', [LoginController::class, 'changePassword'])->name('change.password');



    // Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index']);



    Route::get('/logout', [LoginController::class, 'actionLogout'])->name('actionLogout');

    // Route untuk master_guru
    Route::resource('master_guru', MasterGuruController::class);
    Route::put('/master_guru/{id_guru}', [MasterGuruController::class, 'update'])->name('master_guru.update');

    //siswa
    Route::resource('master_siswa', MasterSiswaController::class);
    Route::post('/master-siswa/update-status', [MasterSiswaController::class, 'updateStatus'])
        ->name('master_siswa.update.status');
    Route::post('/master-siswa/update-status-bulk', [MasterSiswaController::class, 'updateStatusBulk'])
        ->name('master_siswa.update.status.bulk');


    //materi
    Route::resource('master_materi', MasterMateriController::class);

    //kelas
    Route::resource('master_kelas', MasterKelasController::class);
    Route::post('/master-kelas/update-status', [MasterKelasController::class, 'updateStatus'])
        ->name('master_kelas.update.status');
    Route::post('/master-kelas/update-status-bulk', [MasterKelasController::class, 'updateStatusBulk'])
        ->name('master_kelas.update.status.bulk');

    //user atau pengguna
    Route::resource('master_user', MasterUserController::class);
    Route::post('/master_user/{id}/reset-password', [MasterUserController::class, 'resetPassword'])
        ->name('master_user.reset_password')
        ->middleware('auth');

    // Add this route
    Route::post('/master-user/{id}/reset-password', [MasterUserController::class, 'resetPassword'])
        ->name('master_user.reset_password');

    //soal
    Route::resource('master_soal', MasterSoalController::class);

    //jurusan
    Route::resource('master_jurusan', MasterJurusanController::class);

    //quiz
    Route::resource('master_quiz', MasterQuizController::class);

    Route::resource('master_jadwal', MasterJadwalController::class)->except(['show']);

    //map soal
    Route::get('master_map_soal/data', [MasterMapSoalController::class, 'get_data'])->name('get_data');
    Route::resource('master_map_soal', MasterMapSoalController::class);
    Route::get('/filter-soal', [MasterMapSoalController::class, 'filterSoalByBobot']);



    Route::get('jurusan/export', function () {
        return Excel::download(new JurusanExport, 'jurusan.xlsx');
    })->name('jurusan.export');

    Route::post('jurusan/import', function () {
        Excel::import(new JurusanImport, request()->file('file'));
        return redirect()->back()->with('success', 'Data jurusan berhasil diimpor.');
    })->name('jurusan.import');

    Route::get('jurusan/template', function () {
        return Excel::download(new TemplateJurusanExport, 'template_jurusan.xlsx');
    })->name('jurusan.template');

    Route::get('guru/export', [GuruExportImportController::class, 'export'])->name('guru.export');
    Route::post('guru/import', [GuruExportImportController::class, 'import'])->name('guru.import');
    Route::get('guru/template', [GuruExportImportController::class, 'exportTemplate'])->name('guru.template');


    Route::get('siswa/export', [SiswaExportImportController::class, 'export'])->name('siswa.export');
    Route::post('siswa/import', [SiswaExportImportController::class, 'import'])->name('siswa.import');
    Route::get('siswa/template', [SiswaExportImportController::class, 'exportTemplate'])->name('siswa.template');

    Route::get('materi/export', [MateriExportImportController::class, 'export'])->name('materi.export');
    Route::post('materi/import', [MateriExportImportController::class, 'import'])->name('materi.import');
    Route::get('materi/template', [MateriExportImportController::class, 'exportTemplate'])->name('materi.template');

    Route::get('users/export', [UsersExportImportController::class, 'export'])->name('users.export');
    Route::post('users/import', [UsersExportImportController::class, 'import'])->name('users.import');
    Route::get('users/template', [UsersExportImportController::class, 'exportTemplate'])->name('users.template');

    Route::get('kelas/export', [KelasExportImportController::class, 'export'])->name('kelas.export');
    Route::post('kelas/import', [KelasExportImportController::class, 'import'])->name('kelas.import');
    Route::get('kelas/template', [KelasExportImportController::class, 'exportTemplate'])->name('kelas.template');

    Route::get('soal/export', [SoalExportImportController::class, 'export'])->name('soal.export');
    Route::post('soal/import', [SoalExportImportController::class, 'import'])->name('soal.import');
    Route::get('soal/template', [SoalExportImportController::class, 'exportTemplate'])->name('soal.exportTemplate');

    Route::get('master_jadwal/export', [MasterJadwalController::class, 'export'])->name('master_jadwal.export');
    Route::post('master_jadwal/import', [MasterJadwalController::class, 'import'])->name('master_jadwal.import');
    Route::get('master_jadwal/detail/{id}', [MasterJadwalController::class, 'detail'])->name('master_jadwal.detail');
    Route::post('master_jadwal/detail_store/{id}', [MasterJadwalController::class, 'detailStore'])->name('master_jadwal.detail-store');
    Route::get('master_jadwal/detail/edit/{id}', [MasterJadwalController::class, 'detailEdit'])->name('master_jadwal.detail.edit');
    Route::delete('master_jadwal/detail/destroy/{id}', [MasterJadwalController::class, 'detailDestroy'])->name('master_jadwal.detail.destroy');
    Route::put('master_jadwal/detail/update/{id}', [MasterJadwalController::class, 'detailUpdate'])->name('master_jadwal.detail-update');

    Route::get('quiz/export', [MasterQuizController::class, 'export'])->name('quiz.export');
    Route::post('quiz/import', [MasterQuizController::class, 'import'])->name('quiz.import');
    Route::get('quiz/template', [MasterQuizController::class, 'exportTemplate'])->name('quiz.template');
    //  siswain
    Route::get('/siswaIn', [FdController::class, 'afterlogin'])->name('siswaIn');
    Route::get('/kelas/detail/{id}', [FdController::class, 'kelasDetail']);
    Route::get('/kelas/latihan-soal/{id}', [FdController::class, 'kelasDetail']);
    Route::get('/kelas/mulai-ujian/{id}', [FdController::class, 'mulaiUjian']);
    Route::get('/jadwal', [FdController::class, 'jadwalSiswa'])->name('jadwal.siswa');

    Route::get('/mulai-ujian/{id}', [FdController::class, 'mulaiUjian'])->name('mulai.ujian');
    Route::post('/simpan-jawaban-sementara', [TempUjianController::class, 'simpanJawabanSementara'])->name('simpan.jawaban.sementara');
    Route::post('/selesai-ujian', [TempUjianController::class, 'selesaiUjian'])->name('selesai.ujian');
    Route::post('/get-jawaban-sementara', [TempUjianController::class, 'getJawabanSementara'])->name('get.jawaban.sementara');

    // Exam results routes
    Route::get('/hasil-ujian', [TempUjianController::class, 'showExamResults'])->name('hasil.ujian');
    Route::post('/get-exam-results', [TempUjianController::class, 'getExamResults'])->name('get.exam.results');
    Route::post('/get-exam-results-summary', [TempUjianController::class, 'getExamResultsSummary'])->name('get.exam.results.summary');

    Route::get('/siswaIn', [FdController::class, 'afterlogin'])->name('siswaIn');
    Route::get('/kelas/detail/{id}', [FdController::class, 'kelasDetail']);
    Route::get('/kelas/latihan-soal/{id}', [FdController::class, 'kelasDetail']);
    Route::get('/kelas/mulai-ujian/{id}', [FdController::class, 'mulaiUjian']);

    // Route for setting session flag when navigating from detail to exam page
    Route::post('/set-from-detail', [App\Http\Controllers\FdController::class, 'setFromDetail']);

    //route controller laporan
    Route::get('/laporan_nilai', [LaporanNilaiController::class, 'index'])->name('laporan_nilai.index');
    Route::get('/laporan_nilai/detail/{id}', [LaporanNilaiController::class, 'detail'])->name('laporan_nilai.detail');
    Route::get('/laporan_nilai/export', [LaporanNilaiController::class, 'export'])->name('laporan_nilai.export');
    Route::get('/detail_nilai/export/{id}', [LaporanNilaiController::class, 'exportDetail'])->name('detail_nilai.export');
    Route::get('laporan_nilai/detail_all', [LaporanNilaiController::class, 'detailAll'])->name('laporan_nilai.detail_all');
    Route::get('/detail/export/all', [LaporanNilaiController::class, 'exportAllDetail'])
        ->name('detail_nilai.all.export');





    //route jadwal guru
    Route::get('/jadwal_guru', [MasterGuruController::class, 'jadwalGuru'])->name('get.jadwal.guru');

    // route chat
    Route::controller(ChatController::class)->group(function () {
        Route::get('getData/chat', 'getData')->name('chat.getData');
        Route::get('chat-audio', 'chatAudio')->name('chat.audio');
        Route::get('chat-audio/list', 'chatAudioList');
        Route::get('chat/getuser', 'getUser')->name('chat.getuser');
        Route::post('chatsimpan', 'store');
        Route::get('getAllData/chat', 'getAllData');
    });
});

Route::get('/', [FdController::class, 'bfrlogin']);

// Add this route for updating all statuses
// Add this route in web.php
Route::post('/master-siswa/update-status-all', [MasterSiswaController::class, 'updateStatusAll'])
    ->name('master_siswa.update.status.all');
