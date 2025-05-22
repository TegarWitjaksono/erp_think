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
