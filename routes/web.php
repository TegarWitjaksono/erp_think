<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GlController;
use App\Http\Controllers\SkuController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KarungController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LogMesinController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSkuController;
use App\Http\Controllers\LevelRoastController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\MasterJenisController;
use App\Http\Controllers\RoastProfileController;
use App\Http\Controllers\PenerimaanControllerNew;
use App\Http\Controllers\MasterMachinesController;

use App\Http\Controllers\MasterVarietasController;
use App\Http\Controllers\PostRoastBlendController;
use App\Http\Controllers\BatchProductionController;
use App\Http\Controllers\MasterSuppliersController;
use App\Http\Controllers\DetailPenerimaanController;
use App\Http\Controllers\FinishedProductsController;
use App\Http\Controllers\MasterPenerimaanController;
use App\Http\Controllers\InventoryBahanBakuController;
use App\Http\Controllers\InventoryFinishGoodController;
use App\Http\Controllers\BatchProductionResultController;

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

    Route::resource('batch-productions',BatchProductionController::class)->except(['show']);
    Route::post('batch-productions/start/{id}',[BatchProductionController::class,'start'])->name('batch-productions.start');
    Route::post('batch-productions/cancel/{id}',[BatchProductionController::class,'cancel'])->name('batch-productions.cancel');
    Route::post('batch-productions/close{id}',[BatchProductionController::class,'close'])->name('batch-productions.close');


    Route::get('/batch-production/report', [BatchProductionController::class, 'report'])->name('batch-production.report');
    Route::get('/batch-production/menu/{id}', [BatchProductionController::class, 'menu'])->name('batch-production.menu');
    Route::post('/batch-production/store-menu/{id}', [BatchProductionController::class, 'action'])->name('batch-production.store-menu');

    Route::resource('roast_profile',RoastProfileController::class);


    Route::get('list_batch_production/{id}',[BatchProductionController::class,'list'])->name('batch.list');
    Route::post('list_batch_production/{id}',[BatchProductionController::class,'storeBatchInput'])->name('batch.input');
    Route::get('list_batch_production/edit/{id}',[BatchProductionController::class,'editList'])->name('batch.edit');
    Route::put('list_batch_production/update/{id}',[BatchProductionController::class,'updateBatchInput'])->name('batch.update');
    Route::delete('list_batch_production/delete/{id}',[BatchProductionController::class,'deleteBatchInput'])->name('batch.delete');

    Route::resource('methods',MethodController::class);
    Route::resource('level-roast',LevelRoastController::class);
    Route::resource('karung',KarungController::class);

    Route::resource('roles',RoleController::class);
    Route::resource('users',MasterUserController::class);
    Route::get('gl',[JournalController::class,'index']);
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
// Master Penerimaan Routes
Route::get('/master_penerimaan', [App\Http\Controllers\MasterPenerimaanController::class, 'index'])->name('master_penerimaan.index');
Route::post('/master_penerimaan', [App\Http\Controllers\MasterPenerimaanController::class, 'store'])->name('master_penerimaan.store');
Route::get('/master_penerimaan/{id}/edit', [App\Http\Controllers\MasterPenerimaanController::class, 'edit'])->name('master_penerimaan.edit');
Route::put('/master_penerimaan/{id}', [App\Http\Controllers\MasterPenerimaanController::class, 'update'])->name('master_penerimaan.update');
Route::delete('/master_penerimaan/{id}', [App\Http\Controllers\MasterPenerimaanController::class, 'destroy'])->name('master_penerimaan.destroy');
Route::get('/master_penerimaan/create',[MasterPenerimaanController::class,'create'])->name('master_penerimaan.create');
Route::post('/master_penerimaan/store/new',[PenerimaanControllerNew::class,'store'])->name('master_penerimaan.store.new');
// Detail Penerimaan Routes
Route::resource('detail_penerimaan', DetailPenerimaanController::class);
Route::get('get-master-penerimaan/{id}', function($id) {
    $masterPenerimaan = DB::table('master_penerimaan')->where('id_penerimaan', $id)->first();
    return response()->json($masterPenerimaan);
});

Route::get('inventory/cancel/{id}', [InventoryBahanBakuController::class, 'cancel'])->name('inventory.cancel');

Route::resource('inventory', InventoryBahanBakuController::class)->except(['show']);


Route::prefix('inventory')->name('inventory.')->group(function () {


    // Report & Data routes
    Route::get('/report', [InventoryBahanBakuController::class, 'report'])->name('report');
    Route::get('/current-stock', [InventoryBahanBakuController::class, 'currentStock'])->name('current-stock');
    Route::get('/export-report', [InventoryBahanBakuController::class, 'exportReport'])->name('export');
    Route::get('/chart-data', [InventoryBahanBakuController::class, 'getChartData'])->name('chart-data');
});

// Routes untuk inventory (umum)
Route::resource('inventory', InventoryBahanBakuController::class);

// Optional: API routes for AJAX
Route::prefix('api/inventory')->name('api.inventory.')->group(function () {
    Route::get('/chart-data', [InventoryBahanBakuController::class, 'getChartData'])->name('chart-data');

    Route::get('/current-stock-data', function(Request $request) {
        $controller = new InventoryBahanBakuController();
        return response()->json($controller->getCurrentStockData());
    })->name('current-stock-data');
});


Route::get('/inventory/currentStock', [App\Http\Controllers\InventoryBahanBakuController::class, 'currentStock'])->name('inventory.currentStock');

Route::resource('batch-results', BatchProductionResultController::class);
Route::resource('inventory_fg', InventoryFinishGoodController::class)->except(['show']);
Route::get('/inventory_fg/report', [InventoryFinishGoodController::class, 'report'])->name('inventory-finish-goods.report');
Route::get('/inventory_fg/export', [InventoryFinishGoodController::class, 'exportReport'])->name('inventory-finish-goods.export');
Route::get('/inventory_fg/chart-data', [InventoryFinishGoodController::class, 'getChartData'])->name('inventory-finish-goods.chart-data');

Route::resource('/post-roast-blends', PostRoastBlendController::class);

Route::get('log-mesins/import', [LogMesinController::class, 'import'])->name('log-mesins.import');
Route::post('log-mesins/import', [LogMesinController::class, 'storeImport'])->name('log-mesins.storeImport');
Route::resource('log-mesin', LogMesinController::class)
     ->except(['create', 'store', 'edit', 'update']);
