<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\View 
     */
    public function index()
    {
        $id_penerimaan = request('id_penerimaan');

        $data = DB::table('detail_penerimaan')
            ->join('master_penerimaan', 'detail_penerimaan.id_penerimaan', '=', 'master_penerimaan.id_penerimaan')
            ->join('suppliers', 'detail_penerimaan.id_suplier', '=', 'suppliers.id')
            ->join('jenis', 'detail_penerimaan.id_jenis', '=', 'jenis.id_jenis')
            ->join('varietas', 'detail_penerimaan.id_varietas', '=', 'varietas.id_varietas')
            ->join('grade', 'detail_penerimaan.id_grade', '=', 'grade.id_grade')
            ->join('origin', 'detail_penerimaan.id_origin', '=', 'origin.id_origin')
            ->select(
                'detail_penerimaan.*',
                'master_penerimaan.id_penerimaan', // Tambahkan ini
                'master_penerimaan.keterangan as master_keterangan',
                'suppliers.name as nama_suplier',
                'jenis.deskripsi as nama_jenis',
                'varietas.deskripsi as nama_varietas',
                'grade.deskripsi as nama_grade',
                'origin.deskripsi as nama_origin'
            )
            ->when($id_penerimaan, function($query) use ($id_penerimaan) {
                return $query->where('detail_penerimaan.id_penerimaan', $id_penerimaan);
            })
            ->orderBy('detail_penerimaan.id_detail_penerimaan', 'desc')
            ->get();

        $master_penerimaan = DB::table('master_penerimaan')
            ->when($id_penerimaan, function($query) use ($id_penerimaan) {
                return $query->where('id_penerimaan', $id_penerimaan);
            })
            ->get();

        $suppliers = DB::table('suppliers')->get();
        $jenis = DB::table('jenis')->get();
        $varietas = DB::table('varietas')->get();
        $grade = DB::table('grade')->get();
        $origin = DB::table('origin')->get();

        // Cari id_pen yang sesuai id_penerimaan
        $id_penerimaan = optional($master_penerimaan->first())->id_penerimaan ?? '';

        // Ambil master penerimaan sesuai id_penerimaan dari request
        $id_penerimaan = request('id_penerimaan');
        $master = DB::table('master_penerimaan')->where('id_penerimaan', $id_penerimaan)->first();
        $id_batch_mp = $master->id_batch_mp ?? '';

        return view('detail_penerimaan.index', compact(
            'data', 
            'master_penerimaan',
            'suppliers',
            'jenis',
            'varietas',
            'grade',
            'origin',
            'id_penerimaan',
            'id_batch_mp'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penerimaan' => 'required',
            'id_suplier' => 'required',
            'id_jenis' => 'required',
            'id_varietas' => 'required',
            'id_grade' => 'required',
            'id_origin' => 'required',
            'kadar_air' => 'required|numeric',
            'bulk_value' => 'required|numeric',
            'bulk_unit' => 'required|in:kg,liter',
            'id_kemasan' => 'required|integer',
            'berat' => 'required|numeric',
            'jumlah' => 'required|integer',
            'harga_per_kg' => 'required|numeric',
            'size' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // Hitung total berat
            $totalBerat = $request->berat * $request->jumlah;
            $bulk = $request->bulk_value . ' ' . $request->bulk_unit;

           
            // 1. Simpan Detail Penerimaan
            $idDetail = DB::table('detail_penerimaan')->insertGetId([
                'id_penerimaan' => $request->id_penerimaan,
                'id_batch' => $request->id_batch,
                'id_suplier' => $request->id_suplier,
                'id_jenis' => $request->id_jenis,
                'id_varietas' => $request->id_varietas,
                'id_grade' => $request->id_grade,
                'id_origin' => $request->id_origin,
                'kadar_air' => $request->kadar_air,
                'bulk' => $bulk,
                'id_kemasan' => $request->id_kemasan,
                'berat' => $request->berat,
                'jumlah' => $request->jumlah,
                'jumlah_tot' => $totalBerat,
                'size' => $request->size,
                'harga_per_kg' => $request->harga_per_kg
            ]);

            // 2. Loop buat karung + inventory
            $karungIds = [];

            for ($i = 1; $i <= $request->jumlah; $i++) {
                // Buat kode karung unik
                do {
                    $kodeKarung = 'K-' . strtoupper(Str::random(6));
                } while (DB::table('karung')->where('kode_karung', $kodeKarung)->exists());

                // Simpan karung
                $idKarung = DB::table('karung')->insertGetId([
                    'penerimaan_id' => $request->id_penerimaan,
                    // 'id_detail_penerimaan' => $idDetail,
                    'kode_karung' => $kodeKarung,
                    'berat_masuk' => $request->berat,
                    'catatan' => 'auto generate'
                ]);

                $karungIds[] = $idKarung;

                // Simpan inventory (1 baris per karung)
                DB::table('inventory')->insert([
                    'timestamp' => now(),
                    'penerimaan_id' => $request->id_penerimaan,
                    'karung_id' => $idKarung,
                    'roast_batch_id' => null,
                    'id_detail_penerimaan' => $idDetail,
                    'catatan' => 'init stock',
                    'kadar_air' => $request->kadar_air,
                    'bulk_densitas' => $request->bulk_value,
                    'debit_qty' => $request->berat,
                    'credit_qty' => 0,
                    'gl_trx_id' => null // update nanti setelah GL
                ]);
            }

            // 3. Hitung total nilai transaksi
            $totalDebit = $totalBerat * $request->harga_per_kg;

            // 4. Simpan jurnal GL header
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'PENERIMAAN',
                'ref_id' => $request->id_penerimaan,
                'doc_no' => 'GR-' . now()->format('YmdHis'),
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $totalDebit,
                'total_credit' => $totalDebit,
                'status' => 'posted',
                'notes' => 'Auto generate from Detail Raw Material',
                'created_by' => auth()->user()->id ?? 1,
            ]);

            // 5. GL Lines
            DB::table('gl_lines')->insert([
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 1,
                    'account_id' => 8, // akun persediaan bahan baku
                    'debit' => $totalDebit,
                    'credit' => 0,
                    'memo' => 'Persediaan bahan baku',
                    'batch_id' => $request->id_batch
                ],
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 2,
                    'account_id' => 8, // akun hutang/grni
                    'debit' => 0,
                    'credit' => $totalDebit,
                    'memo' => 'Hutang GRNI',
                    'batch_id' => $request->id_batch
                ]
            ]);

            // 6. Update inventory rows with GL ID
            DB::table('inventory')
                ->where('id_detail_penerimaan', $idDetail)
                ->whereIn('karung_id', $karungIds)
                ->update(['gl_trx_id' => $glHeaderId]);

            DB::commit();

            return redirect()->back()->with('success', 'Detail penerimaan dan stok berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.' . $e->getMessage());
        }
    }




    function generateKodeKarung(int $penerimaanId): string
    {
        // Hitung karung yang sudah ada untuk penerimaan ini
        $countKarung = DB::table('karung')
            ->where('penerimaan_id', $penerimaanId)
            ->count();

        $karungSequence = $countKarung + 1; // next

        // Ambil nomor urut penerimaan untuk format (atau langsung pakai id)
        // Kalau mau pakai langsung id_penerimaan:
        $penerimaanSequence = $penerimaanId;

        // Pad 3 digit
        $penStr = str_pad($penerimaanSequence, 3, '0', STR_PAD_LEFT);
        $karStr = str_pad($karungSequence, 3, '0', STR_PAD_LEFT);

        return "KRG-BATCH-GB-{$penStr}-{$karStr}";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $data = DB::table('detail_penerimaan')
            ->join('master_penerimaan', 'detail_penerimaan.id_penerimaan', '=', 'master_penerimaan.id_penerimaan')
            ->select('detail_penerimaan.*', 'master_penerimaan.id_batch_mp')
            ->where('id_detail_penerimaan', $id)
            ->first();

        if ($data) {
            $bulkParts = explode(' ', $data->bulk);
            $data->bulk_value = $bulkParts[0] ?? '';
            $data->bulk_unit = $bulkParts[1] ?? 'kg';
        }

        $master_penerimaan = DB::table('master_penerimaan')->get();
        $suppliers = DB::table('suppliers')->get();
        $jenis = DB::table('jenis')->get();
        $varietas = DB::table('varietas')->get();
        $grade = DB::table('grade')->get();
        $origin = DB::table('origin')->get();

        return view('detail_penerimaan.edit', compact(
            'data',
            'master_penerimaan',
            'suppliers',
            'jenis',
            'varietas',
            'grade',
            'origin'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Debug: Log semua input yang diterima
        Log::info('UPDATE REQUEST DATA:', [
            'id' => $id,
            'all_request' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $validated = $request->validate([
            'id_penerimaan' => 'required',
            'id_batch' => 'nullable', // Optional field
            'id_suplier' => 'required',
            'id_jenis' => 'required',
            'id_varietas' => 'required',
            'id_grade' => 'required',
            'id_origin' => 'required',
            'kadar_air' => 'required|numeric',
            'bulk_value' => 'required|numeric',
            'bulk_unit' => 'required|in:kg,liter',
            'id_kemasan' => 'required', // Remove integer constraint karena bisa string
            'berat' => 'required|numeric',
            'jumlah' => 'required|integer',
            'size' => 'nullable|string|max:50',
            // Harga per kg tidak ada di form, jadi kita buat optional atau set default
            'harga_per_kg' => 'nullable|numeric',
        ]);

        Log::info('VALIDATED DATA:', $validated);

        DB::beginTransaction();

        try {
            // Cari detail penerimaan yang akan diupdate
            $detailPenerimaan = DB::table('detail_penerimaan')->where('id_detail_penerimaan', $id)->first();
            
            Log::info('EXISTING DETAIL PENERIMAAN:', [
                'found' => $detailPenerimaan ? true : false,
                'data' => $detailPenerimaan
            ]);
            
            if (!$detailPenerimaan) {
                throw new \Exception('Detail penerimaan tidak ditemukan dengan ID: ' . $id);
            }

            // Debug: Cek data yang akan dihapus
            $existingKarungIds = DB::table('karung')
                ->where('penerimaan_id', $detailPenerimaan->id_penerimaan)
                ->pluck('id')
                ->toArray();

            $existingInventoryCount = DB::table('inventory')
                ->where('id_detail_penerimaan', $id)
                ->count();

            $existingGlHeaders = DB::table('gl_headers')
                ->where('ref_module', 'PENERIMAAN')
                ->where('ref_id', $detailPenerimaan->id_penerimaan)
                ->pluck('id')
                ->toArray();

            Log::info('EXISTING DATA TO DELETE:', [
                'karung_ids' => $existingKarungIds,
                'inventory_count' => $existingInventoryCount,
                'gl_header_ids' => $existingGlHeaders
            ]);

            // 1. Hapus data terkait yang sudah ada
            
            // Hapus inventory records
            $deletedInventory = DB::table('inventory')
                ->where('id_detail_penerimaan', $id)
                ->delete();

            // Hapus karung records (hanya yang terkait dengan detail ini)
            $deletedKarung = DB::table('karung')
                ->whereIn('id', DB::table('inventory')
                    ->where('id_detail_penerimaan', $id)
                    ->pluck('karung_id'))
                ->delete();

            // Hapus GL records jika ada
            if (!empty($existingGlHeaders)) {
                $deletedGlLines = DB::table('gl_lines')->whereIn('header_id', $existingGlHeaders)->delete();
                $deletedGlHeaders = DB::table('gl_headers')->whereIn('id', $existingGlHeaders)->delete();
            }

            Log::info('DELETION RESULTS:', [
                'deleted_inventory' => $deletedInventory,
                'deleted_karung' => $deletedKarung,
                'deleted_gl_lines' => $deletedGlLines ?? 0,
                'deleted_gl_headers' => $deletedGlHeaders ?? 0
            ]);

            // 2. Hitung ulang data baru
            $totalBerat = $request->berat * $request->jumlah;
            $bulk = $request->bulk_value . ' ' . $request->bulk_unit;
            
            // Set default harga_per_kg jika tidak ada di form
            $hargaPerKg = $request->harga_per_kg ?? $detailPenerimaan->harga_per_kg ?? 0;

            Log::info('CALCULATED VALUES:', [
                'total_berat' => $totalBerat,
                'bulk' => $bulk,
                'berat_per_karung' => $request->berat,
                'jumlah_karung' => $request->jumlah,
                'harga_per_kg' => $hargaPerKg
            ]);

            // 3. Update Detail Penerimaan
            $updateResult = DB::table('detail_penerimaan')
                ->where('id_detail_penerimaan', $id)
                ->update([
                    'id_penerimaan' => $request->id_penerimaan,
                    'id_batch' => $request->id_batch ?? null,
                    'id_suplier' => $request->id_suplier,
                    'id_jenis' => $request->id_jenis,
                    'id_varietas' => $request->id_varietas,
                    'id_grade' => $request->id_grade,
                    'id_origin' => $request->id_origin,
                    'kadar_air' => $request->kadar_air,
                    'bulk' => $bulk,
                    'id_kemasan' => $request->id_kemasan,
                    'berat' => $request->berat,
                    'jumlah' => $request->jumlah,
                    'jumlah_tot' => $totalBerat,
                    'size' => $request->size,
                    'harga_per_kg' => $hargaPerKg,
                   
                ]);

            Log::info('UPDATE DETAIL RESULT:', ['affected_rows' => $updateResult]);

            // 4. Buat ulang karung dan inventory
            $newKarungIds = [];
            $inventoryInserts = [];

            for ($i = 1; $i <= $request->jumlah; $i++) {
                // Buat kode karung unik
                do {
                    $kodeKarung = 'K-' . strtoupper(Str::random(6));
                } while (DB::table('karung')->where('kode_karung', $kodeKarung)->exists());

                Log::info('CREATING KARUNG:', ['iteration' => $i, 'kode_karung' => $kodeKarung]);

                // Simpan karung baru
                $idKarung = DB::table('karung')->insertGetId([
                    'penerimaan_id' => $request->id_penerimaan,
                    'kode_karung' => $kodeKarung,
                    'berat_masuk' => $request->berat,
                    'catatan' => 'updated - auto generate',
                   
                ]);

                $newKarungIds[] = $idKarung;

                // Prepare inventory data
                $inventoryInserts[] = [
                    'timestamp' => now(),
                    'penerimaan_id' => $request->id_penerimaan,
                    'karung_id' => $idKarung,
                    'roast_batch_id' => null,
                    'id_detail_penerimaan' => $id,
                    'catatan' => 'updated stock',
                    'kadar_air' => $request->kadar_air,
                    'bulk_densitas' => $request->bulk_value,
                    'debit_qty' => $request->berat,
                    'credit_qty' => 0,
                    'gl_trx_id' => null, // update nanti setelah GL
                   
                ];

                Log::info('KARUNG CREATED:', ['karung_id' => $idKarung, 'kode' => $kodeKarung]);
            }

            // Bulk insert inventory
            DB::table('inventory')->insert($inventoryInserts);

            Log::info('INVENTORY CREATED:', [
                'total_records' => count($inventoryInserts),
                'karung_ids' => $newKarungIds
            ]);

            // 5. Hitung total nilai transaksi baru
            $totalDebit = $totalBerat * $hargaPerKg;

            Log::info('GL CALCULATION:', [
                'total_berat' => $totalBerat,
                'harga_per_kg' => $hargaPerKg,
                'total_debit' => $totalDebit
            ]);

            // 6. Simpan jurnal GL header baru
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'PENERIMAAN',
                'ref_id' => $request->id_penerimaan,
                'doc_no' => 'GR-UPD-' . now()->format('YmdHis'),
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $totalDebit,
                'total_credit' => $totalDebit,
                'status' => 'posted',
                'notes' => 'Auto generate from Updated Detail Raw Material',
                'created_by' => auth()->user()->id ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('GL HEADER CREATED:', ['gl_header_id' => $glHeaderId]);

            // 7. GL Lines baru
            $glLinesInserted = DB::table('gl_lines')->insert([
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 1,
                    'account_id' => 8, // akun persediaan bahan baku
                    'debit' => $totalDebit,
                    'credit' => 0,
                    'memo' => 'Persediaan bahan baku (Updated)',
                    'batch_id' => $request->id_batch ?? null,
                  
                ],
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 2,
                    'account_id' => 8, // akun hutang/grni
                    'debit' => 0,
                    'credit' => $totalDebit,
                    'memo' => 'Hutang GRNI (Updated)',
                    'batch_id' => $request->id_batch ?? null,
                 
                ]
            ]);

            Log::info('GL LINES CREATED:', ['success' => $glLinesInserted]);

            // 8. Update inventory rows with new GL ID
            $inventoryUpdated = DB::table('inventory')
                ->where('id_detail_penerimaan', $id)
                ->whereIn('karung_id', $newKarungIds)
                ->update(['gl_trx_id' => $glHeaderId]);

            Log::info('INVENTORY GL UPDATE:', [
                'affected_rows' => $inventoryUpdated,
                'gl_header_id' => $glHeaderId
            ]);

            // Final verification
            $finalDetailCount = DB::table('detail_penerimaan')->where('id_detail_penerimaan', $id)->count();
            $finalKarungCount = DB::table('karung')->whereIn('id', $newKarungIds)->count();
            $finalInventoryCount = DB::table('inventory')->where('id_detail_penerimaan', $id)->count();

            Log::info('FINAL VERIFICATION:', [
                'detail_exists' => $finalDetailCount,
                'karung_created' => $finalKarungCount,
                'inventory_created' => $finalInventoryCount,
                'expected_karung' => $request->jumlah,
                'expected_inventory' => $request->jumlah
            ]);

            DB::commit();

            Log::info('UPDATE TRANSACTION COMMITTED SUCCESSFULLY');

            return redirect()->route('detail_penerimaan.index',$request->id_penerimaan)->with('success', 'Detail penerimaan berhasil diupdate. Debug info logged.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('UPDATE ERROR OCCURRED:', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'detail_id' => $id
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage() . ' | Check logs for details.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::table('detail_penerimaan')->where('id_detail_penerimaan', $id)->delete();
            
            // Redirect back with the id_penerimaan parameter
            return redirect()->route('detail_penerimaan.index', [
                'id_penerimaan' => request('id_penerimaan')
            ])->with('success', 'Detail penerimaan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}