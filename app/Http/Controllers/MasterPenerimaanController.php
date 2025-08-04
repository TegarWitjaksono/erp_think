<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterPenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $data = DB::table('master_penerimaan')->get();
        $nextBatchId = $this->generateBatchId();
        return view('master_penerimaan.index', compact('data', 'nextBatchId'));
    }

    public function create(){
        $nextBatchId = $this->generateBatchId();
        $suppliers = DB::table('suppliers')->get();
        $jenis = DB::table('jenis')->get();
        $varietas = DB::table('varietas')->get();
        $grade = DB::table('grade')->get();
        $origin = DB::table('origin')->get();
        $proses = DB::table('m_proses')->get();
        $package = DB::table('m_package_size')->get();

        $inventory = DB::table('inventorifinishgood')->whereRaw('jml_masuk > jml_keluar')->get();
        return view('master_penerimaan.create',compact('nextBatchId','suppliers','jenis','varietas','grade','origin','package','proses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Generate next ID Penerimaan with format P0000
     */

    private function generateBatchId()
    {
        $last = DB::table('master_penerimaan')->orderBy('id_batch_mp', 'desc')->first();
        if (!$last || !$last->id_batch_mp) {
            return 'P0001';
        }
        $lastNumber = (int) substr($last->id_batch_mp, 1);
        $nextNumber = $lastNumber + 1;
        return 'P' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        // Validasi data master
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'cdate' => 'required|date',
            'id_batch_mp' => 'required|string|max:50',

            // Validasi array detail
            'id_suplier' => 'required|array|min:1',
            'id_suplier.*' => 'required|integer',
            'id_jenis' => 'required|array|min:1',
            'id_jenis.*' => 'required|integer',
            'id_varietas' => 'required|array|min:1',
            'id_varietas.*' => 'required|integer',
            'id_grade' => 'required|array|min:1',
            'id_grade.*' => 'required|integer',
            'origin' => 'required|array|min:1',
            'origin.*' => 'required|integer',
            'jumlah_karung' => 'required|array|min:1',
            'jumlah_karung.*' => 'required|integer|min:1',
            'berat_per_karung' => 'required|array|min:1',
            'berat_per_karung.*' => 'required|numeric|min:0.01',
            'bulk_value' => 'required|array|min:1',
            'bulk_value.*' => 'required|numeric|min:0.01',
            'bulk_unit' => 'required|array|min:1',
            'bulk_unit.*' => 'required|in:kg,liter',
            'kadar_air' => 'required|array|min:1',
            'kadar_air.*' => 'required|numeric|min:0|max:100',
            'size' => 'required|array|min:1',
            'size.*' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|array|min:1',
            'harga_per_kg.*' => 'required|numeric|min:0',
            'kemasan' => 'required|array|min:1',
            'kemasan.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            $cdate = strtotime($request->cdate);

            // 1. Insert Master Penerimaan
            $masterPenerimaanId = DB::table('master_penerimaan')->insertGetId([

                'id_batch_mp' => $request->id_batch_mp,
                'keterangan' => $request->keterangan,
                'cdate' => $cdate,

            ]);

            // 2. Loop untuk setiap detail
            foreach ($request->id_suplier as $index => $supplierId) {
                // Generate batch ID untuk setiap detail
                $batchId = $this->generateBatchId();

                // Hitung total berat
                $jumlahKarung = $request->jumlah_karung[$index];
                $beratPerKarung = $request->berat_per_karung[$index];
                $totalBerat = $jumlahKarung * $beratPerKarung;

                // Format bulk
                $bulk = $request->bulk_value[$index] . ' ' . $request->bulk_unit[$index];

                // Insert Detail Penerimaan
                $idDetail = DB::table('detail_penerimaan')->insertGetId([
                    'id_penerimaan' => $masterPenerimaanId,
                    'id_batch' => $batchId,
                    'id_suplier' => $supplierId,
                    'id_jenis' => $request->id_jenis[$index],
                    'id_varietas' => $request->id_varietas[$index],
                    'id_grade' => $request->id_grade[$index],
                    'id_origin' => $request->origin[$index],
                    'kadar_air' => $request->kadar_air[$index],
                    'bulk' => $bulk,
                    'id_kemasan' => $request->kemasan[$index],
                    'berat' => $beratPerKarung,
                    'jumlah' => $jumlahKarung,
                    'jumlah_tot' => $totalBerat,
                    'size' => $request->size[$index],
                    'harga_per_kg' => $request->harga_per_kg[$index],

                ]);

                // 3. Generate Karung dan Inventory untuk setiap karung
                $karungIds = [];

                for ($i = 1; $i <= $jumlahKarung; $i++) {
                    // Generate kode karung unik
                    do {
                        $kodeKarung = 'K-' . strtoupper(Str::random(6));
                    } while (DB::table('karung')->where('kode_karung', $kodeKarung)->exists());

                    // Insert Karung
                    $idKarung = DB::table('karung')->insertGetId([
                        'penerimaan_id' => $masterPenerimaanId,

                        'kode_karung' => $kodeKarung,
                        'berat_masuk' => $beratPerKarung,
                        'catatan' => 'Auto generate - Karung ' . $i,

                    ]);

                    $karungIds[] = $idKarung;

                    // Insert Inventory (1 baris per karung)
                    DB::table('inventory')->insert([
                        'timestamp' => now(),
                        'penerimaan_id' => $masterPenerimaanId,
                        'karung_id' => $idKarung,
                        'roast_batch_id' => null,
                        'id_detail_penerimaan' => $idDetail,
                        'catatan' => 'Initial stock - ' . $kodeKarung,
                        'kadar_air' => $request->kadar_air[$index],
                        'bulk_densitas' => $request->bulk_value[$index],
                        'debit_qty' => $beratPerKarung,
                        'credit_qty' => 0,
                        'gl_trx_id' => null, // akan diupdate setelah GL

                    ]);
                }

                // 4. Generate Journal Entry (GL)
                $totalNilai = $totalBerat * $request->harga_per_kg[$index];

                // Insert GL Header
                $glHeaderId = DB::table('gl_headers')->insertGetId([
                    'ref_module' => 'PENERIMAAN',
                    'ref_id' => $masterPenerimaanId,
                    'doc_no' => 'GR-' . $batchId . '-' . now()->format('YmdHis'),
                    'doc_date' => now(),
                    'posting_date' => now(),
                    'currency' => 'IDR',
                    'total_debit' => $totalNilai,
                    'total_credit' => $totalNilai,
                    'status' => 'posted',
                    'notes' => 'Auto generate from Master Penerimaan - Batch: ' . $batchId,
                    'created_by' => auth()->user()->id ?? 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert GL Lines
                DB::table('gl_lines')->insert([
                    [
                        'header_id' => $glHeaderId,
                        'line_no' => 1,
                        'account_id' => 8, // Akun Persediaan Bahan Baku
                        'debit' => $totalNilai,
                        'credit' => 0,
                        'memo' => 'Persediaan bahan baku - Batch: ' . $batchId,
                        'batch_id' => $batchId,

                    ],
                    [
                        'header_id' => $glHeaderId,
                        'line_no' => 2,
                        'account_id' => 9, // Akun Hutang/GRNI
                        'debit' => 0,
                        'credit' => $totalNilai,
                        'memo' => 'Hutang GRNI - Batch: ' . $batchId,
                        'batch_id' => $batchId,

                    ]
                ]);

                // 5. Update inventory dengan GL ID
                DB::table('inventory')
                    ->where('id_detail_penerimaan', $idDetail)
                    ->whereIn('karung_id', $karungIds)
                    ->update([
                        'gl_trx_id' => $glHeaderId,

                    ]);
            }

            DB::commit();

            return redirect()->route('master_penerimaan.index')
                ->with('success', 'Master Penerimaan dan detail berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Master Penerimaan Store Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

private function generatePenerimaanId()
{
    $lastRecord = DB::table('master_penerimaan')
        ->orderBy('id', 'desc')
        ->first();

    if ($lastRecord && isset($lastRecord->id_penerimaan)) {
        $lastNumber = (int) substr($lastRecord->id_penerimaan, 1);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return 'R' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $masterPenerimaan = DB::table('master_penerimaan')
            ->where('id_penerimaan', $id)
            ->first();

        if (!$masterPenerimaan) {
            return redirect()->route('master_penerimaan.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        // Get detail penerimaan
        $details = DB::table('detail_penerimaan')
            ->where('id_penerimaan', $masterPenerimaan->id_penerimaan)
            ->get();

        // Load master data
        $suppliers = DB::table('suppliers')->select('id', 'name')->get();
        $jenis = DB::table('jenis')->select('id_jenis', 'deskripsi')->get();
        $varietas = DB::table('varietas')->select('id_varietas', 'deskripsi')->get();
        $grade = DB::table('grade')->select('id_grade', 'deskripsi')->get();
        $origin = DB::table('origin')->select('id_origin', 'deskripsi')->get();
        $proses = DB::table('m_proses')->get();
        $package = DB::table('m_package_size')->get();

        return view('master_penerimaan.edit', compact(
            'masterPenerimaan', 'details', 'suppliers', 'jenis', 'varietas', 'grade', 'origin',
            'proses', 'package'
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
        // Validasi data master
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'cdate' => 'required|date',
            'id_batch_mp' => 'required|string|max:50',


            'id_suplier' => 'required|array|min:1',
            'id_suplier.*' => 'required|integer',
            'id_jenis' => 'required|array|min:1',
            'id_jenis.*' => 'required|integer',
            'id_varietas' => 'required|array|min:1',
            'id_varietas.*' => 'required|integer',
            'id_grade' => 'required|array|min:1',
            'id_grade.*' => 'required|integer',
            'origin' => 'required|array|min:1',
            'origin.*' => 'required|integer',
            'jumlah_karung' => 'required|array|min:1',
            'jumlah_karung.*' => 'required|integer|min:1',
            'berat_per_karung' => 'required|array|min:1',
            'berat_per_karung.*' => 'required|numeric|min:0.01',
            'bulk_value' => 'required|array|min:1',
            'bulk_value.*' => 'required|numeric|min:0.01',
            'bulk_unit' => 'required|array|min:1',
            'bulk_unit.*' => 'required|in:kg,liter',
            'kadar_air' => 'required|array|min:1',
            'kadar_air.*' => 'required|numeric|min:0|max:100',
            'size' => 'required|array|min:1',
            'size.*' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|array|min:1',
            'harga_per_kg.*' => 'required|numeric|min:0',
            'kemasan' => 'required|array|min:1',
            'kemasan.*' => 'required|integer|min:1',
        ]);

        $masterPenerimaan = DB::table('master_penerimaan')
            ->where('id_penerimaan', $id)
            ->first();

        if (!$masterPenerimaan) {
            return redirect()->route('master_penerimaan.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $cdate = strtotime($request->cdate);

            // 1. Update Master Penerimaan
            DB::table('master_penerimaan')
                ->where('id_penerimaan', $id)
                ->update([
                    'keterangan' => $request->keterangan,
                    'cdate' => $cdate,

                ]);

            // 2. Get existing detail IDs
            $existingDetailIds = DB::table('detail_penerimaan')
                ->where('id_penerimaan', $masterPenerimaan->id_penerimaan)
                ->pluck('id_penerimaan')
                ->toArray();

            $submittedDetailIds = array_filter($request->detail_ids);

            // 3. Delete details that are not in submitted data
            $detailsToDelete = array_diff($existingDetailIds, $submittedDetailIds);

            foreach ($detailsToDelete as $detailId) {
                $this->deleteDetailAndRelatedData($detailId);
            }

            // 4. Process each detail (update existing or create new)
            foreach ($request->id_suplier as $index => $supplierId) {
                $detailId = $request->detail_ids[$index] ?? null;

                // Hitung total berat
                $jumlahKarung = $request->jumlah_karung[$index];
                $beratPerKarung = $request->berat_per_karung[$index];
                $totalBerat = $jumlahKarung * $beratPerKarung;

                // Format bulk
                $bulk = $request->bulk_value[$index] . ' ' . $request->bulk_unit[$index];

                $detailData = [
                    'id_suplier' => $supplierId,
                    'id_jenis' => $request->id_jenis[$index],
                    'id_varietas' => $request->id_varietas[$index],
                    'id_grade' => $request->id_grade[$index],
                    'id_origin' => $request->origin[$index],
                    'kadar_air' => $request->kadar_air[$index],
                    'bulk' => $bulk,
                    'id_kemasan' => $request->kemasan[$index],
                    'berat' => $beratPerKarung,
                    'jumlah' => $jumlahKarung,
                    'jumlah_tot' => $totalBerat,
                    'size' => $request->size[$index],
                    'harga_per_kg' => $request->harga_per_kg[$index],

                ];

                if ($detailId && in_array($detailId, $existingDetailIds)) {
                    // Update existing detail
                    DB::table('detail_penerimaan')
                        ->where('id_detail_penerimaan', $detailId)
                        ->update($detailData);

                    // Update related data
                    $this->updateDetailRelatedData($id, $jumlahKarung, $beratPerKarung, $request->kadar_air[$index], $request->bulk_value[$index], $request->harga_per_kg[$index], $totalBerat);

                } else {
                    // Create new detail
                    $batchId = $this->generateBatchId();

                    $detailData['id_penerimaan'] = $masterPenerimaan->id_penerimaan;
                    $detailData['id_batch'] = $batchId;

                    $newDetailId = DB::table('detail_penerimaan')->insertGetId($detailData);

                    // Generate karung dan inventory untuk detail baru
                    $this->generateKarungAndInventory($masterPenerimaan->id_penerimaan, $newDetailId, $jumlahKarung, $beratPerKarung, $request->kadar_air[$index], $request->bulk_value[$index]);

                    // Generate journal entry untuk detail baru
                    $totalNilai = $totalBerat * $request->harga_per_kg[$index];
                    $this->generateJournalEntry($masterPenerimaan->id_penerimaan, $batchId, $totalNilai, $newDetailId);
                }
            }

            DB::commit();

            return redirect()->route('master_penerimaan.index')
                ->with('success', 'Master Penerimaan berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Master Penerimaan Update Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
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
        $masterPenerimaan = DB::table('master_penerimaan')
            ->where('id', $id)
            ->first();

        if (!$masterPenerimaan) {
            return redirect()->route('master_penerimaan.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            // Get all details
            $details = DB::table('detail_penerimaan')
                ->where('id_penerimaan', $masterPenerimaan->id_penerimaan)
                ->get();

            // Delete each detail and related data
            foreach ($details as $detail) {
                $this->deleteDetailAndRelatedData($detail->id);
            }

            // Delete master penerimaan
            DB::table('master_penerimaan')->where('id', $id)->delete();

            DB::commit();

            return redirect()->route('master_penerimaan.index')
                ->with('success', 'Master Penerimaan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Master Penerimaan Delete Error: ' . $e->getMessage());

            return redirect()->route('master_penerimaan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    // ========== PRIVATE HELPER METHODS ==========

    /**
     * Delete detail and all related data (karung, inventory, GL)
     */
    private function deleteDetailAndRelatedData($detailId)
    {

        // Get GL transaction IDs
        $glTransactionIds = DB::table('inventory')
            ->where('id_detail_penerimaan', $detailId)
            ->whereNotNull('gl_trx_id')
            ->pluck('gl_trx_id')
            ->unique()
            ->toArray();

        // Delete inventory
        DB::table('inventory')->where('id_detail_penerimaan', $detailId)->delete();

        // Delete karung
        if (!empty($karungIds)) {
            DB::table('karung')->whereIn('id', $karungIds)->delete();
        }

        // Delete GL lines and headers
        foreach ($glTransactionIds as $glId) {
            DB::table('gl_lines')->where('header_id', $glId)->delete();
            DB::table('gl_headers')->where('id', $glId)->delete();
        }

        // Delete detail
        DB::table('detail_penerimaan')->where('id_detail_penerimaan', $detailId)->delete();
    }

    /**
     * Update detail related data when detail is updated
     */
    private function updateDetailRelatedData($detailId, $jumlahKarung, $beratPerKarung, $kadarAir, $bulkValue, $hargaPerKg, $totalBerat)
    {
        // Get existing karung
        $existingKarung = DB::table('karung')
            ->where('id_detail_penerimaan', $detailId)
            ->get();

        $existingKarungCount = $existingKarung->count();

        if ($jumlahKarung > $existingKarungCount) {
            // Add new karung
            $detail = DB::table('detail_penerimaan')->where('id_detail_penerimaan', $detailId)->first();

            for ($i = $existingKarungCount + 1; $i <= $jumlahKarung; $i++) {
                // Generate kode karung unik
                do {
                    $kodeKarung = 'K-' . strtoupper(Str::random(6));
                } while (DB::table('karung')->where('kode_karung', $kodeKarung)->exists());

                // Insert Karung
                $idKarung = DB::table('karung')->insertGetId([
                    'penerimaan_id' => $detail->id_penerimaan,

                    'kode_karung' => $kodeKarung,
                    'berat_masuk' => $beratPerKarung,
                    'catatan' => 'Auto generate - Karung ' . $i,

                ]);

                // Insert Inventory
                DB::table('inventory')->insert([
                    'timestamp' => now(),
                    'penerimaan_id' => $detail->id_penerimaan,
                    'karung_id' => $idKarung,
                    'roast_batch_id' => null,
                    'id_detail_penerimaan' => $detailId,
                    'catatan' => 'Updated stock - ' . $kodeKarung,
                    'kadar_air' => $kadarAir,
                    'bulk_densitas' => $bulkValue,
                    'debit_qty' => $beratPerKarung,
                    'credit_qty' => 0,
                    'gl_trx_id' => null,

                ]);
            }

        } elseif ($jumlahKarung < $existingKarungCount) {
            // Remove excess karung
            $karungToDelete = $existingKarung->slice($jumlahKarung);

            foreach ($karungToDelete as $karung) {
                DB::table('inventory')->where('karung_id', $karung->id)->delete();
                DB::table('karung')->where('id', $karung->id)->delete();
            }
        }

        // Update existing karung and inventory
        $remainingKarung = DB::table('karung')
            ->where('penerimaan_id', $detailId)
            ->limit($jumlahKarung)
            ->get();

        foreach ($remainingKarung as $karung) {
            DB::table('karung')
                ->where('id', $karung->id)
                ->update([
                    'berat_masuk' => $beratPerKarung,

                ]);

            DB::table('inventory')
                ->where('karung_id', $karung->id)
                ->update([
                    'kadar_air' => $kadarAir,
                    'bulk_densitas' => $bulkValue,
                    'debit_qty' => $beratPerKarung,

                ]);
        }

        // Update GL entries
        $totalNilai = $totalBerat * $hargaPerKg;
        $detail = DB::table('detail_penerimaan')->where('id_detail_penerimaan', $detailId)->first();

        $glHeaderIds = DB::table('inventory')
            ->where('id_detail_penerimaan', $detailId)
            ->whereNotNull('gl_trx_id')
            ->pluck('gl_trx_id')
            ->unique();

        foreach ($glHeaderIds as $glHeaderId) {
            DB::table('gl_headers')
                ->where('id', $glHeaderId)
                ->update([
                    'total_debit' => $totalNilai,
                    'total_credit' => $totalNilai,
                    'updated_at' => now(),
                ]);

            DB::table('gl_lines')
                ->where('header_id', $glHeaderId)
                ->where('line_no', 1)
                ->update([
                    'debit' => $totalNilai,
                ]);

            DB::table('gl_lines')
                ->where('header_id', $glHeaderId)
                ->where('line_no', 2)
                ->update([
                    'credit' => $totalNilai,
                ]);
        }
    }

    private function generateKarungAndInventory($idPenerimaan, $idDetail, $jumlahKarung, $beratPerKarung, $kadarAir, $bulkValue)
    {
        $karungIds = [];

        for ($i = 1; $i <= $jumlahKarung; $i++) {
            // Generate kode karung unik
            do {
                $kodeKarung = 'K-' . strtoupper(Str::random(6));
            } while (DB::table('karung')->where('kode_karung', $kodeKarung)->exists());

            // Insert Karung
            $idKarung = DB::table('karung')->insertGetId([
                'penerimaan_id' => $idPenerimaan,
                'kode_karung' => $kodeKarung,
                'berat_masuk' => $beratPerKarung,
                'catatan' => 'Auto generate - Karung ' . $i,

            ]);

            $karungIds[] = $idKarung;

            // Insert Inventory (1 baris per karung)
            DB::table('inventory')->insert([
                'timestamp' => now(),
                'penerimaan_id' => $idPenerimaan,
                'karung_id' => $idKarung,
                'roast_batch_id' => null,
                'id_detail_penerimaan' => $idDetail,
                'catatan' => 'Initial stock - ' . $kodeKarung,
                'kadar_air' => $kadarAir,
                'bulk_densitas' => $bulkValue,
                'debit_qty' => $beratPerKarung,
                'credit_qty' => 0,
                'gl_trx_id' => null, // akan diupdate setelah GL

            ]);
        }

        return $karungIds;
    }

    /**
     * Generate Journal Entry (GL)
     */
    private function generateJournalEntry($idPenerimaan, $batchId, $totalNilai, $idDetail)
    {
        // Insert GL Header
        $glHeaderId = DB::table('gl_headers')->insertGetId([
            'ref_module' => 'PENERIMAAN',
            'ref_id' => $idPenerimaan,
            'doc_no' => 'GR-' . $batchId . '-' . now()->format('YmdHis'),
            'doc_date' => now(),
            'posting_date' => now(),
            'currency' => 'IDR',
            'total_debit' => $totalNilai,
            'total_credit' => $totalNilai,
            'status' => 'posted',
            'notes' => 'Auto generate from Master Penerimaan - Batch: ' . $batchId,
            'created_by' => auth()->user()->id ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert GL Lines
        DB::table('gl_lines')->insert([
            [
                'header_id' => $glHeaderId,
                'line_no' => 1,
                'account_id' => 8, // Akun Persediaan Bahan Baku
                'debit' => $totalNilai,
                'credit' => 0,
                'memo' => 'Persediaan bahan baku - Batch: ' . $batchId,
                'batch_id' => $batchId,

            ],
            [
                'header_id' => $glHeaderId,
                'line_no' => 2,
                'account_id' => 9, // Akun Hutang/GRNI
                'debit' => 0,
                'credit' => $totalNilai,
                'memo' => 'Hutang GRNI - Batch: ' . $batchId,
                'batch_id' => $batchId,

            ]
        ]);

        // Update inventory dengan GL ID
        $karungIds = DB::table('karung')
            ->where('penerimaan_id', $idDetail)
            ->pluck('id')
            ->toArray();

        DB::table('inventory')
            ->where('id_detail_penerimaan', $idDetail)
            ->whereIn('karung_id', $karungIds)
            ->update([
                'gl_trx_id' => $glHeaderId,

            ]);

        return $glHeaderId;
    }

    /**
     * Generate next batch ID for form display
     */
    private function generateNextBatchId()
    {
        $lastRecord = DB::table('master_penerimaan')
            ->orderBy('id_penerimaan', 'desc')
            ->first();

        if ($lastRecord && isset($lastRecord->id_batch_mp)) {
            $lastNumber = (int) substr($lastRecord->id_batch_mp, 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'MP' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }


}
