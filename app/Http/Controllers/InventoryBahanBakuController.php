<?php

namespace App\Http\Controllers;

use App\Models\InventoryBahanBaku;
use App\Http\Requests\InventoryBahanBakuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class InventoryBahanBakuController extends Controller
{
    public function index()
    {
        $items = DB::table('inventory')
            ->join('detail_penerimaan', 'detail_penerimaan.id_detail_penerimaan', '=', 'inventory.id_detail_penerimaan')
            ->select('inventory.*', 'detail_penerimaan.*')
            ->get();

        return view('inventory.raw.index', compact('items'));
    }

    public function create()
    {
        $penerimaanList = DB::table('master_penerimaan')->get();
        $details = DB::table('detail_penerimaan')->get();
        $karung = DB::table('karung')->get();

        return view('inventory.raw.create', compact('penerimaanList','karung','details'));
    }
    public function store(Request $request)
    {
       $data = $request->validate([
        'penerimaan_id' => 'required',
        'karung_id' => 'required',
        'catatan' => 'required',
        'id_detail_penerimaan' => 'required',
        'kadar_air' => 'required',
        'bulk_densitas' => 'required',
        'debit_qty' => 'required',
        'credit_qty' => 'required'
       ]);
       $data['timestamp'] = now();
       $data['roast_batch_id'] = null;
       $data['gl_trx_id'] = null;
         DB::table('inventory')->insert($data);

        return redirect()->route('inventory.index')->with('success', 'Data berhasil disimpan');
    }
    public function show()
    {
        $items = DB::table('inventory')
            ->join('master_penerimaan', 'inventory.penerimaan_id', '=', 'master_penerimaan.id_penerimaan')
            ->select('inventory.*', 'master_penerimaan.*')
            ->get();

        return view('inventory.raw.index', compact('items'));
    }
    public function edit($id)
    {
          $penerimaanList = DB::table('master_penerimaan')->get();
        $details = DB::table('detail_penerimaan')->get();
        $karung = DB::table('karung')->get();
        $data = DB::table('inventory')->find($id);

        return view('inventory.raw.edit', compact('penerimaanList','karung','details','data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
        'penerimaan_id' => 'required',
        'karung_id' => 'required',
        'catatan' => 'required',
        'id_detail_penerimaan' => 'required',
        'kadar_air' => 'required',
        'bulk_densitas' => 'required',
        'debit_qty' => 'required',
        'credit_qty' => 'required'
       ]);
       $data['timestamp'] = now();
       $data['roast_batch_id'] = null;
       $data['gl_trx_id'] = null;
         DB::table('inventory')->where('id',$id)->update($data);

        return redirect()->route('inventory.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        DB::table('inventory')->where('id',$id)->delete();

        return redirect()->route('inventory.raw.index')->with('success', 'Data dinonaktifkan');
    }


    public function cancel($id)
    {
        // Ambil data detail penerimaan
        $detail = DB::table('detail_penerimaan')
            ->where('id_detail_penerimaan', $id)
            ->first();

        if (!$detail) {
            return redirect()->back()->with('error', 'Detail penerimaan tidak ditemukan');
        }

        // Update status menjadi 'batal'
        DB::table('detail_penerimaan')
            ->where('id_detail_penerimaan', $id)
            ->update(['status' => 'batal']);

        // Ambil semua inventory masuk yang terkait
        $inventories = DB::table('inventory')
            ->where('id_detail_penerimaan', $id)
            ->where('keluar', 0) // hanya ambil yang masuk
            ->get();

        $grandTotal = 0;
        foreach ($inventories as $inv) {
            $noInventoryKeluar = $inv->no_inventory . '-CANCEL';

            DB::table('inventory')->insert([
                'id_detail_penerimaan' => $id,
                'no_ref' => $inv->no_ref,
                'masuk' => 0,
                'keluar' => $inv->masuk,
                'catatan' => 'Pembatalan - ' . $inv->no_inventory,
                'created_at' => now(),
                'updated_at' => now(),
                'no_inventory' => $noInventoryKeluar
            ]);

            $grandTotal += $inv->masuk;
        }

        // Ambil no penerimaan (jika ada di tabel utama)
        $noPenerimaan = DB::table('penerimaan')
            ->where('id', $detail->id_penerimaan ?? null)
            ->value('no_penerimaan') ?? 'UNKNOWN';

        // INSERT JURNAL GL
        $glHeaderId = DB::table('gl_headers')->insertGetId([
            'ref_module' => 'PENERIMAAN',
            'ref_id' => $id,
            'doc_no' => 'GR-CNL-' . $detail->id_batch_mp . '-' . now()->format('YmdHis'),
            'doc_date' => now(),
            'posting_date' => now(),
            'currency' => 'IDR',
            'total_debit' => $grandTotal,
            'total_credit' => $grandTotal,
            'status' => 'posted',
            'notes' => 'Pembatalan penerimaan - Batch: ' . $noPenerimaan,
            'created_by' => auth()->user()->id ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('gl_lines')->insert([
            [
                'header_id' => $glHeaderId,
                'line_no' => 1,
                'account_id' => 9, // GRNI dikembalikan
                'debit' => $grandTotal,
                'credit' => 0,
                'memo' => 'Pembatalan Hutang GRNI - Batch: ' . $noPenerimaan,
                'batch_id' => $detail->id_batch_mp,
            ],
            [
                'header_id' => $glHeaderId,
                'line_no' => 2,
                'account_id' => 8, // Persediaan bahan baku keluar
                'debit' => 0,
                'credit' => $grandTotal,
                'memo' => 'Pengurangan Persediaan bahan baku - Batch: ' . $noPenerimaan,
                'batch_id' => $detail->id_batch_mp,
            ]
        ]);

        return redirect()->back()->with('success', 'Penerimaan berhasil dibatalkan');
    }





    /**
     * Menampilkan laporan inventory
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Data untuk tabel laporan
        $reportData = $this->getInventoryReportData($startDate, $endDate);

        // Data untuk grafik
        $chartData = $this->getInventoryChartData($startDate, $endDate);

        // Summary data
        $summary = $this->getInventorySummary($startDate, $endDate);

        return view('inventory.raw.report', compact('reportData', 'chartData', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Mendapatkan data laporan inventory
     */
    private function getInventoryReportData($startDate, $endDate)
    {
        return DB::table('inventory as i')
            ->join('master_penerimaan as mp', 'i.penerimaan_id', '=', 'mp.id_penerimaan')
            ->leftJoin('detail_penerimaan as dp', 'i.id_detail_penerimaan', '=', 'dp.id_detail_penerimaan')
            ->select([
                'i.id',
                'i.timestamp',
                'mp.keterangan as penerimaan_keterangan',
                'mp.id_batch_mp',
                'dp.id_batch',
                'i.kadar_air',
                'i.bulk_densitas',
                'i.debit_qty',
                'i.credit_qty',
                DB::raw('(i.credit_qty - i.debit_qty) as saldo_qty'),
                'i.catatan'
            ])
            ->whereBetween('i.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('i.timestamp', 'desc')
            ->get();
    }

    /**
     * Mendapatkan data untuk grafik
     */
    private function getInventoryChartData($startDate, $endDate)
    {
        // Data stok per hari
        $dailyStock = DB::table('inventory as i')
            ->select([
                DB::raw('DATE(i.timestamp) as tanggal'),
                DB::raw('SUM(i.credit_qty - i.debit_qty) as total_stock')
            ])
            ->whereBetween('i.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(i.timestamp)'))
            ->orderBy('tanggal')
            ->get();

        // Data transaksi per hari (masuk vs keluar)
        $dailyTransactions = DB::table('inventory as i')
            ->select([
                DB::raw('DATE(i.timestamp) as tanggal'),
                DB::raw('SUM(i.credit_qty) as total_masuk'),
                DB::raw('SUM(i.debit_qty) as total_keluar')
            ])
            ->whereBetween('i.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(i.timestamp)'))
            ->orderBy('tanggal')
            ->get();

        // Data kadar air rata-rata per hari
        $dailyMoisture = DB::table('inventory as i')
            ->select([
                DB::raw('DATE(i.timestamp) as tanggal'),
                DB::raw('AVG(i.kadar_air) as avg_kadar_air')
            ])
            ->whereBetween('i.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(i.timestamp)'))
            ->orderBy('tanggal')
            ->get();

        // Data bulk density rata-rata per hari
        $dailyBulkDensity = DB::table('inventory as i')
            ->select([
                DB::raw('DATE(i.timestamp) as tanggal'),
                DB::raw('AVG(i.bulk_densitas) as avg_bulk_density')
            ])
            ->whereBetween('i.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(i.timestamp)'))
            ->orderBy('tanggal')
            ->get();

        return [
            'dailyStock' => $dailyStock,
            'dailyTransactions' => $dailyTransactions,
            'dailyMoisture' => $dailyMoisture,
            'dailyBulkDensity' => $dailyBulkDensity
        ];
    }

    /**
     * Mendapatkan summary data
     */
    private function getInventorySummary($startDate, $endDate)
    {
        $summary = DB::table('inventory as i')
            ->select([
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(i.credit_qty) as total_masuk'),
                DB::raw('SUM(i.debit_qty) as total_keluar'),
                DB::raw('SUM(i.credit_qty - i.debit_qty) as saldo_akhir'),
                DB::raw('AVG(i.kadar_air) as avg_kadar_air'),
                DB::raw('AVG(i.bulk_densitas) as avg_bulk_density'),
                DB::raw('MIN(i.kadar_air) as min_kadar_air'),
                DB::raw('MAX(i.kadar_air) as max_kadar_air'),
                DB::raw('MIN(i.bulk_densitas) as min_bulk_density'),
                DB::raw('MAX(i.bulk_densitas) as max_bulk_density')
            ])
            ->whereBetween('i.timestamp', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->first();

        return $summary;
    }

    /**
     * Export laporan ke Excel
     */
    public function exportReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $reportData = $this->getInventoryReportData($startDate, $endDate);
        $summary = $this->getInventorySummary($startDate, $endDate);

        // Generate Excel using Laravel Excel or similar package
        // This is a placeholder - you'll need to implement the actual Excel export
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

        $chartData = $this->getInventoryChartData($startDate, $endDate);

        return response()->json($chartData);
    }

    /**
     * Laporan stok real-time
     */
    public function currentStock()
    {
        $currentStock = DB::table('inventory as i')
            ->leftJoin('master_penerimaan as mp', 'i.penerimaan_id', '=', 'mp.id_penerimaan')
            ->leftJoin('detail_penerimaan as dp', 'i.id_detail_penerimaan', '=', 'dp.id_detail_penerimaan')
            ->select([
                'mp.id_batch_mp',
                'dp.id_batch',
                'mp.keterangan',
                DB::raw('SUM(i.credit_qty - i.debit_qty) as current_stock'),
                DB::raw('AVG(i.kadar_air) as avg_kadar_air'),
                DB::raw('AVG(i.bulk_densitas) as avg_bulk_density'),
                DB::raw('MAX(i.timestamp) as last_update')
            ])
            ->groupBy('mp.id_batch_mp', 'dp.id_batch', 'mp.keterangan')

            ->orderBy('last_update', 'desc')
            ->get();


        return view('inventory.raw.current-stock', compact('currentStock'));
    }


}
