<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryFinishGoodController extends Controller
{
    public function index(Request $request)
{
    $query = DB::table('inventorifinishgood')
        ->join('master_barang', 'inventorifinishgood.id_product', '=', 'master_barang.id_barang')
        ->select(
            'inventorifinishgood.*',
            'master_barang.nama_barang as product_name',
           
        );

    if ($request->filled('level_roast')) {
        $query->where('master_barang.level_roast', 'like', '%'.$request->level_roast.'%');
    }

    if ($request->filled('flavour_note')) {
        $query->where('master_barang.flavour_note', 'like', '%'.$request->flavour_note.'%');
    }

    if ($request->filled('expired_before')) {
        $query->where('inventorifinishgood.expired_date', '<=', $request->expired_before);
    }

    $items = $query->orderBy('inventorifinishgood.expired_date')->get();

    
    return view('inventory.finish_good.index', compact('items'));
}


    public function create()
    {
        $inventory = DB::table('inventory')
                    ->leftJoin('master_penerimaan','master_penerimaan.id_penerimaan','=','inventory.penerimaan_id')
                    ->get();
        $barang = DB::table('master_barang')->get();
        $batches = DB::table('batchproduction')->pluck('id', 'id');
        
        return view('inventory.finish_good.create',compact('inventory','barang','batches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_inventory' => 'required',
            'id_product' => 'required|exists:master_barang,id_barang',
            'expired_date' => 'required|date',
            'jenis' => 'required|in:PreRoastBlend,PostRoast,Single',
            'Id_batch_production' => 'nullable|exists:batchproduction,id',
            'post_roast_blend_id' => 'nullable|exists:post_roast_blends,id',
            'penjualan_id' => 'nullable|exists:penjualans,id',
            'jml_masuk' => 'nullable',
            'jml_keluar' => 'nullable',
            'catatan' => 'nullable|string',
        ]);

        $data['timestamp'] = now();
        DB::table('inventorifinishgood')->insert($data);

        return redirect()->route('inventory_fg.index')
                         ->with('success', 'Inventory record added successfully');
    }

    public function edit($id)
    {
        $item = DB::table('inventorifinishgood')->find($id);
          $inventory = DB::table('inventory')
                    ->leftJoin('master_penerimaan','master_penerimaan.id_penerimaan','=','inventory.penerimaan_id')
                    ->get();
        $barang = DB::table('master_barang')->get();
        $batches = DB::table('batchproduction')->pluck('id', 'id');
        return view('inventory.finish_good.edit', compact('item','inventory','barang','batches'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'expired_date' => 'required|date',
            'jml_masuk' => 'nullable|integer|min:0',
            'jml_keluar' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        DB::table('inventorifinishgood')->where('id', $id)->update($data);

        return redirect()->route('inventorifinishgood.index')
                         ->with('success', 'Inventory record updated');
    }

    public function destroy($id)
    {
        DB::table('inventorifinishgood')->where('id', $id)->delete();
        return redirect()->route('inventorifinishgood.index')
                         ->with('success', 'Inventory record deleted');
    }

    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Data untuk tabel laporan
        $reportData = $this->getFinishGoodsReportData($startDate, $endDate);
        
        // Data untuk grafik
        $chartData = $this->getFinishGoodsChartData($startDate, $endDate);
        
        // Summary data
        $summary = $this->getFinishGoodsSummary($startDate, $endDate);
        
        return view('inventory.finish_good.report', compact('reportData', 'chartData', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Mendapatkan data laporan finish goods
     */
    private function getFinishGoodsReportData($startDate, $endDate)
    {
        return DB::table('inventorifinishgood as ifg')
            ->leftJoin('batchproduction as bp', 'ifg.Id_batch_production', '=', 'bp.id')
            ->select([
                'ifg.id',
                'ifg.timestamp',
                'ifg.expired_date',
                'ifg.jenis',
                'ifg.Id_batch_production',
                'ifg.Id_postRoastblend',
                'ifg.Id_penjualan',
                'ifg.jml_masuk',
                'ifg.jml_keluar',
                DB::raw('(ifg.jml_masuk - ifg.jml_keluar) as saldo'),
                'ifg.Catatan',
                'bp.berat_diroasting_kg',
                'bp.status as production_status',
                'bp.estimate_expire_date'
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('ifg.timestamp', 'desc')
            ->get();
    }

    /**
     * Mendapatkan data untuk grafik
     */
    private function getFinishGoodsChartData($startDate, $endDate)
    {
        // Data stok per hari
        $dailyStock = DB::table('inventorifinishgood as ifg')
            ->select([
                DB::raw('DATE(ifg.timestamp) as tanggal'),
                DB::raw('SUM(ifg.jml_masuk - ifg.jml_keluar) as total_stock')
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(ifg.timestamp)'))
            ->orderBy('tanggal')
            ->get();

        // Data transaksi per hari (masuk vs keluar)
        $dailyTransactions = DB::table('inventorifinishgood as ifg')
            ->select([
                DB::raw('DATE(ifg.timestamp) as tanggal'),
                DB::raw('SUM(ifg.jml_masuk) as total_masuk'),
                DB::raw('SUM(ifg.jml_keluar) as total_keluar')
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(ifg.timestamp)'))
            ->orderBy('tanggal')
            ->get();

        // Data per jenis produk
        $jenisDistribution = DB::table('inventorifinishgood as ifg')
            ->select([
                'ifg.jenis',
                DB::raw('SUM(ifg.jml_masuk - ifg.jml_keluar) as total_stock'),
                DB::raw('COUNT(*) as total_transaksi')
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('ifg.jenis')
            ->get();

        // Data produksi per batch
        $batchProduction = DB::table('inventorifinishgood as ifg')
            ->join('batchproduction as bp', 'ifg.Id_batch_production', '=', 'bp.id')
            ->select([
                'ifg.Id_batch_production',
                DB::raw('SUM(ifg.jml_masuk) as total_produksi'),
                DB::raw('SUM(ifg.jml_keluar) as total_keluar'),
                DB::raw('SUM(ifg.jml_masuk - ifg.jml_keluar) as sisa_stock'),
                'bp.berat_diroasting_kg',
                'bp.status'
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('ifg.Id_batch_production', 'bp.berat_diroasting_kg', 'bp.status')
            ->orderBy('total_produksi', 'desc')
            ->limit(10)
            ->get();

        // Data expiry analysis
        $expiryAnalysis = DB::table('inventorifinishgood as ifg')
            ->select([
                DB::raw('CASE 
                    WHEN ifg.expired_date IS NULL THEN "Tanpa Expired" 
                    WHEN ifg.expired_date <= CURDATE() THEN "Expired" 
                    WHEN ifg.expired_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN "Akan Expired (7 hari)" 
                    WHEN ifg.expired_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN "Akan Expired (30 hari)" 
                    ELSE "Aman" 
                END as status_expiry'),
                DB::raw('SUM(ifg.jml_masuk - ifg.jml_keluar) as total_stock'),
                DB::raw('COUNT(*) as jumlah_batch')
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('CASE 
                WHEN ifg.expired_date IS NULL THEN "Tanpa Expired" 
                WHEN ifg.expired_date <= CURDATE() THEN "Expired" 
                WHEN ifg.expired_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN "Akan Expired (7 hari)" 
                WHEN ifg.expired_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN "Akan Expired (30 hari)" 
                ELSE "Aman" 
            END'))
            ->get();

        return [
            'dailyStock' => $dailyStock,
            'dailyTransactions' => $dailyTransactions,
            'jenisDistribution' => $jenisDistribution,
            'batchProduction' => $batchProduction,
            'expiryAnalysis' => $expiryAnalysis
        ];
    }

    /**
     * Mendapatkan summary data
     */
    private function getFinishGoodsSummary($startDate, $endDate)
    {
        $summary = DB::table('inventorifinishgood as ifg')
            ->select([
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(ifg.jml_masuk) as total_masuk'),
                DB::raw('SUM(ifg.jml_keluar) as total_keluar'),
                DB::raw('SUM(ifg.jml_masuk - ifg.jml_keluar) as saldo_akhir'),
                DB::raw('COUNT(DISTINCT ifg.Id_batch_production) as total_batch'),
                DB::raw('COUNT(DISTINCT ifg.jenis) as total_jenis')
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->first();

        // Tambahan data expiry
        $expiryData = DB::table('inventorifinishgood as ifg')
            ->select([
                DB::raw('COUNT(CASE WHEN ifg.expired_date <= CURDATE() THEN 1 END) as expired_items'),
                DB::raw('COUNT(CASE WHEN ifg.expired_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND ifg.expired_date > CURDATE() THEN 1 END) as expiring_soon'),
                DB::raw('SUM(CASE WHEN ifg.expired_date <= CURDATE() THEN (ifg.jml_masuk - ifg.jml_keluar) ELSE 0 END) as expired_stock'),
                DB::raw('SUM(CASE WHEN ifg.expired_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND ifg.expired_date > CURDATE() THEN (ifg.jml_masuk - ifg.jml_keluar) ELSE 0 END) as expiring_soon_stock')
            ])
            ->whereBetween('ifg.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->first();

        // Merge data
        $summary->expired_items = $expiryData->expired_items;
        $summary->expiring_soon = $expiryData->expiring_soon;
        $summary->expired_stock = $expiryData->expired_stock;
        $summary->expiring_soon_stock = $expiryData->expiring_soon_stock;

        return $summary;
    }

    /**
     * Laporan stok real-time finish goods
     */
    public function currentStock()
    {
        $currentStock = DB::table('inventorifinishgood as ifg')
            ->leftJoin('batchproduction as bp', 'ifg.Id_batch_production', '=', 'bp.id')
            ->select([
                'ifg.Id_batch_production',
                'ifg.jenis',
                'ifg.Id_postRoastblend',
                DB::raw('SUM(ifg.jml_masuk - ifg.jml_keluar) as current_stock'),
                DB::raw('MAX(ifg.timestamp) as last_update'),
                DB::raw('MIN(ifg.expired_date) as nearest_expiry'),
                'bp.berat_diroasting_kg',
                'bp.status as production_status',
                'bp.estimate_expire_date'
            ])
            ->groupBy('ifg.Id_batch_production', 'ifg.jenis', 'ifg.Id_postRoastblend', 'bp.berat_diroasting_kg', 'bp.status', 'bp.estimate_expire_date')
            ->having('current_stock', '>', 0)
            ->orderBy('last_update', 'desc')
            ->get();
        
        return view('inventory.finish_good.current-stock', compact('currentStock'));
    }

    /**
     * Export laporan ke Excel
     */
    public function exportReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $reportData = $this->getFinishGoodsReportData($startDate, $endDate);
        $summary = $this->getFinishGoodsSummary($startDate, $endDate);
        
        // Generate Excel using Laravel Excel or similar package
        return response()->json([
            'message' => 'Export functionality needs to be implemented with Laravel Excel package',
            'data' => $reportData,
            'summary' => $summary
        ]);
    }

    /**
     * API endpoint untuk data grafik (untuk AJAX calls)
     */
    public function getChartData(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $chartData = $this->getFinishGoodsChartData($startDate, $endDate);
        
        return response()->json($chartData);
    }
}
