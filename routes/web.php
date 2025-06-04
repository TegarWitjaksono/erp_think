<?php


use App\Exports\JurusanExport;
use App\Exports\TemplateJurusanExport;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OriginController;
use App\Imports\JurusanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FdController;
use App\Http\Controllers\FinishedProductsController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterJenisController;
use App\Http\Controllers\MasterSuppliersController;
use App\Http\Controllers\MasterVarietasController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MasterMachinesController;
use App\Http\Controllers\MasterSkuController;
use App\Http\Controllers\SkuController;

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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('master_suppliers', MasterSuppliersController::class);
    Route::resource('master_jenis', MasterJenisController::class);
    Route::resource('master_varietas', MasterVarietasController::class);
    Route::resource('machines', MasterMachinesController::class);
    Route::resource('master_sku', MasterSkuController::class);
    Route::resource('sku', SkuController::class);
    Route::get('/logout', [LoginController::class, 'actionLogout'])->name('actionLogout');
});


// Dashboard route
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

// Alternative route for dashboard (root URL)
Route::get('/', function () {
    return redirect('/home');
})->middleware('auth');


// Add this to your existing routes
Route::resource('finished_products', FinishedProductsController::class);
Route::resource('sales', SalesController::class);
Route::get('/sales/get-product-price/{id}', [SalesController::class, 'getProductPrice'])->name('sales.getProductPrice');


// Add these routes with your other resource routes
Route::resource('master_origin', OriginController::class);

Route::resource('master_grade', GradeController::class);

Route::resource('master_barang', BarangController::class);

// Add these routes for Detail SKU
Route::get('/detail-sku/{id_sku}', [App\Http\Controllers\DetailSkuController::class, 'index'])->name('detail_sku.index');
Route::get('/detail-sku/{id_sku}/create', [App\Http\Controllers\DetailSkuController::class, 'create'])->name('detail_sku.create');
Route::post('/detail-sku', [App\Http\Controllers\DetailSkuController::class, 'store'])->name('detail_sku.store');
Route::get('/detail-sku/edit/{id}', [App\Http\Controllers\DetailSkuController::class, 'edit'])->name('detail_sku.edit');
Route::put('/detail-sku/{id}', [App\Http\Controllers\DetailSkuController::class, 'update'])->name('detail_sku.update');
Route::delete('/detail-sku/{id}', [App\Http\Controllers\DetailSkuController::class, 'destroy'])->name('detail_sku.destroy');


// Master Penerimaan Routes
Route::get('/master_penerimaan', [App\Http\Controllers\MasterPenerimaanController::class, 'index'])->name('master_penerimaan.index');
Route::post('/master_penerimaan', [App\Http\Controllers\MasterPenerimaanController::class, 'store'])->name('master_penerimaan.store');
Route::get('/master_penerimaan/{id}/edit', [App\Http\Controllers\MasterPenerimaanController::class, 'edit'])->name('master_penerimaan.edit');
Route::put('/master_penerimaan/{id}', [App\Http\Controllers\MasterPenerimaanController::class, 'update'])->name('master_penerimaan.update');
Route::delete('/master_penerimaan/{id}', [App\Http\Controllers\MasterPenerimaanController::class, 'destroy'])->name('master_penerimaan.destroy');
