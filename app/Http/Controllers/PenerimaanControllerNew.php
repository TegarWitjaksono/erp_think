<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenerimaanControllerNew extends Controller
{
  public function store(Request $request)
    {
        $request->validate([
            'id_batch_mp' => 'required|string|max:255',
            'cdate' => 'required|date',
            'id_suplier' => 'required|integer',
            'no_po' => 'required|string|max:255',
            'no_do' => 'required|string|max:255',
            'no_invoice' => 'required|string|max:255',
            'jumlah_tagihan' => 'required|string|max:255',
            'biaya-lain' => 'required|string|max:255',
            'keterangan' => 'required|string|max:100',

            // Detail validation
            'no_batch' => 'required|array',
            'no_batch.*' => 'required|string|max:255',
            'id_jenis' => 'required|array',
            'id_jenis.*' => 'required|integer',
            'id_varietas' => 'required|array',
            'id_varietas.*' => 'required|integer',
            'id_grade' => 'required|array',
            'id_grade.*' => 'required|integer',
            'bulk_value' => 'required|array',
            'bulk_value.*' => 'required|numeric',
            'kadar_air' => 'required|array',
            'kadar_air.*' => 'required|string|max:30',
            'size' => 'required|array',
            'size.*' => 'required|integer',
            'proses' => 'required|array',
            'proses.*' => 'required|integer',
            'berat_per_karung' => 'required|array',
            'berat_per_karung.*' => 'required|numeric',
            'p_size' => 'required|array',
            'p_size.*' => 'required|integer',
            'id_origin' => 'required|array',
            'id_origin.*' => 'required|integer',
            'harga_per_kg' => 'required|array',
            'harga_per_kg.*' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Insert master penerimaan
            $masterPenerimaan = DB::table('master_penerimaan')->insertGetId([
                'keterangan' => $request->keterangan,
                'cdate' => strtotime($request->cdate),
                'id_batch_mp' => $request->id_batch_mp,
                'id_supplier' => $request->id_suplier,
                'no_po' => $request->no_po,
                'no_do' => $request->no_do,
                'no_invoice' => $request->no_invoice,
                'jum_tagihan' => $request->jumlah_tagihan,
                'biaya_lain' => $request->input('biaya-lain'),
            ]);

            // Generate nomor penerimaan dengan format P + YYYYMMDD + nomor urut
            $today = date('Ymd');
            $noPenerimaan = 'P' . $today . str_pad($masterPenerimaan, 3, '0', STR_PAD_LEFT);

            // Update nomor penerimaan di master
            DB::table('master_penerimaan')
                ->where('id_penerimaan', $masterPenerimaan)
                ->update(['id_batch_mp' => $noPenerimaan]);

            // Insert detail penerimaan
            $totalDetails = count($request->no_batch);
            $totalBerat = 0;
            $totalHarga = 0;

            for ($i = 0; $i < $totalDetails; $i++) {
                $beratPerKemasan = $request->berat_per_karung[$i];
                $hargaPerKg = $request->harga_per_kg[$i];
                $jumlahKarung = $request->jumlah[$i];
                $subtotal = $beratPerKemasan * $hargaPerKg * $jumlahKarung;

                $totalBerat += ($beratPerKemasan * $jumlahKarung);
                $totalHarga += $subtotal;

                // Insert detail penerimaan
                $detailId = DB::table('detail_penerimaan')->insertGetId([
                    'id_penerimaan' => $masterPenerimaan,
                    'id_batch' => 'B' . str_pad($masterPenerimaan, 4, '0', STR_PAD_LEFT) . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'id_suplier' => $request->id_suplier,
                    'id_jenis' => $request->id_jenis[$i],
                    'id_varietas' => $request->id_varietas[$i],
                    'id_grade' => $request->id_grade[$i],
                    'id_origin' => $request->id_origin[$i],
                    'kadar_air' => $request->kadar_air[$i],
                    'bulk' => $request->bulk_value[$i],
                    'harga_per_kg' => $hargaPerKg,
                    'jenis_kemasan' => 'Karung',
                    'berat_per_kemasan' => $beratPerKemasan,
                    'id_kemasan' => 1,
                    'berat' => $beratPerKemasan * $jumlahKarung,
                    'jumlah' => $jumlahKarung,
                    'jumlah_tot' => $subtotal,
                    'size' => $request->size[$i],
                    'no_batch' => $request->no_batch[$i],
                    'id_proses' => $request->proses[$i],
                    'id_p_size' => $request->p_size[$i],
                ]);

                // Insert ke inventory per karung
                $beratDiterima = $beratPerKemasan * $jumlahKarung; // dalam gram
                $packageSize = $request->p_size[$i]; // package size dalam kg, convert ke gram



                for ($karung = 1; $karung <= $jumlahKarung; $karung++) {
                    $beratKarung = $beratPerKemasan; // berat per karung dalam gram

                    // Generate nomor referensi inventory
                    $noRef = $noPenerimaan . '-' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '-' . str_pad($karung, 3, '0', STR_PAD_LEFT);

                    DB::table('inventory')->insert([
                        'id_detail_penerimaan' => $detailId,
                        'no_ref' => null,
                        'masuk' => $beratKarung,
                        'keluar' => 0,
                        'catatan' => 'Penerimaan barang - ' . $noRef,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'no_inventory' => $noRef
                    ]);
                }
            }

            // Insert GL (General Ledger)
            $totalTagihan = (float)str_replace([',', '.'], ['', ''], $request->jumlah_tagihan);
            $biayaLain = (float)str_replace([',', '.'], ['', ''], $request->input('biaya-lain'));
            $grandTotal = $totalTagihan + $biayaLain;
 // Insert GL Header
                $glHeaderId = DB::table('gl_headers')->insertGetId([
                    'ref_module' => 'PENERIMAAN',
                    'ref_id' => $masterPenerimaan,
                    'doc_no' => 'GR-' . $noPenerimaan . '-' . now()->format('YmdHis'),
                    'doc_date' => now(),
                    'posting_date' => now(),
                    'currency' => 'IDR',
                    'total_debit' => $grandTotal,
                    'total_credit' => $grandTotal,
                    'status' => 'posted',
                    'notes' => 'Auto generate from Master Penerimaan - Batch: ' . $noPenerimaan,
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
                        'debit' => $grandTotal,
                        'credit' => 0,
                        'memo' => 'Persediaan bahan baku - Batch: ' . $noPenerimaan,
                        'batch_id' => null,

                    ],
                    [
                        'header_id' => $glHeaderId,
                        'line_no' => 2,
                        'account_id' => 9, // Akun Hutang/GRNI
                        'debit' => 0,
                        'credit' => $grandTotal,
                        'memo' => 'Hutang GRNI - Batch: ' . $noPenerimaan,
                        'batch_id' => null,

                    ]
                ]);

            DB::commit();

            return redirect()->route('master_penerimaan.index')
                ->with('success', 'Data penerimaan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_batch_mp' => 'required|string|max:255',
            'cdate' => 'required|date',
            'id_suplier' => 'required|integer',
            'no_po' => 'required|string|max:255',
            'no_do' => 'required|string|max:255',
            'no_invoice' => 'required|string|max:255',
            'jumlah_tagihan' => 'required|string|max:255',
            'biaya-lain' => 'required|string|max:255',
            'keterangan' => 'required|string|max:100',

            // Detail validation
            'detail_ids' => 'nullable|array',
            'detail_ids.*' => 'nullable|integer',
            'no_batch' => 'required|array',
            'no_batch.*' => 'required|string|max:255',
            'id_jenis' => 'required|array',
            'id_jenis.*' => 'required|integer',
            'id_varietas' => 'required|array',
            'id_varietas.*' => 'required|integer',
            'id_grade' => 'required|array',
            'id_grade.*' => 'required|integer',
            'bulk_value' => 'required|array',
            'bulk_value.*' => 'required|numeric',
            'bulk_unit' => 'required|array',
            'bulk_unit.*' => 'required|string|in:kg,liter',
            'kadar_air' => 'required|array',
            'kadar_air.*' => 'required|string|max:30',
            'size' => 'required|array',
            'size.*' => 'required|integer',
            'proses' => 'required|array',
            'proses.*' => 'required|integer',
            'berat_per_karung' => 'required|array',
            'berat_per_karung.*' => 'required|numeric',
            'p_size' => 'required|array',
            'p_size.*' => 'required|integer',
            'origin' => 'required|array',
            'origin.*' => 'required|integer',
            'harga_per_kg' => 'required|array',
            'harga_per_kg.*' => 'required|numeric',
            'jumlah_karung' => 'required|array',
            'jumlah_karung.*' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            // Find master penerimaan
            $masterPenerimaan = DB::table('master_penerimaan')->where('id_penerimaan', $id)->first();

            if (!$masterPenerimaan) {
                throw new \Exception('Data penerimaan tidak ditemukan');
            }

            // Update master penerimaan
            DB::table('master_penerimaan')
                ->where('id_penerimaan', $id)
                ->update([
                    'keterangan' => $request->keterangan,
                    'cdate' => strtotime($request->cdate),
                    'id_supplier' => $request->id_suplier,
                    'no_po' => $request->no_po,
                    'no_do' => $request->no_do,
                    'no_invoice' => $request->no_invoice,
                    'jum_tagihan' => $request->jumlah_tagihan,
                    'biaya_lain' => $request->input('biaya-lain'),

                ]);

            // Get existing details
            $existingDetails = DB::table('detail_penerimaan')
                ->where('id_penerimaan', $id)
                ->pluck('id_detail_penerimaan')
                ->toArray();

            // Collect detail IDs from request (existing details being updated)
            $requestDetailIds = array_filter($request->detail_ids ?? []);

            // Find details to delete (existing but not in request)
            $detailsToDelete = array_diff($existingDetails, $requestDetailIds);


            // Process detail updates/inserts
            $totalDetails = count($request->no_batch);
            $totalBerat = 0;
            $totalHarga = 0;

            for ($i = 0; $i < $totalDetails; $i++) {
                $beratPerKemasan = $request->berat_per_karung[$i];
                $hargaPerKg = $request->harga_per_kg[$i];
                $jumlahKarung = $request->jumlah_karung[$i];
                $subtotal = $beratPerKemasan * $hargaPerKg * $jumlahKarung;

                $totalBerat += ($beratPerKemasan * $jumlahKarung);
                $totalHarga += $subtotal;

                $detailData = [
                    'id_penerimaan' => $id,
                    'id_suplier' => $request->id_suplier,
                    'id_jenis' => $request->id_jenis[$i],
                    'id_varietas' => $request->id_varietas[$i],
                    'id_grade' => $request->id_grade[$i],
                    'id_origin' => $request->origin[$i],
                    'kadar_air' => $request->kadar_air[$i],
                    'bulk' => $request->bulk_value[$i] . ' ' . $request->bulk_unit[$i],
                    'harga_per_kg' => $hargaPerKg,
                    'jenis_kemasan' => 'Karung',
                    'berat_per_kemasan' => $beratPerKemasan,
                    'id_kemasan' => 1,
                    'berat' => $beratPerKemasan * $jumlahKarung,
                    'jumlah' => $jumlahKarung,
                    'jumlah_tot' => $subtotal,
                    'size' => $request->size[$i],
                    'no_batch' => $request->no_batch[$i],
                    'id_proses' => $request->proses[$i],
                    'id_p_size' => $request->p_size[$i],
                ];

                $detailId = null;

                // Check if this is update or insert
                if (!empty($request->detail_ids[$i])) {
                    // Update existing detail
                    $detailId = $request->detail_ids[$i];

                    DB::table('detail_penerimaan')
                        ->where('id_detail_penerimaan', $detailId)
                        ->update($detailData);


                } else {
                    // Insert new detail
                    $detailData['id_batch'] = 'B' . str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

                    $detailId = DB::table('detail_penerimaan')->insertGetId($detailData);
                }

                // Re-create inventory entries for this detail
                for ($karung = 1; $karung <= $jumlahKarung; $karung++) {
                    $beratKarung = $beratPerKemasan; // berat per karung

                    // Generate nomor referensi inventory
                    $noRef = $masterPenerimaan->id_batch_mp . '-' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '-' . str_pad($karung, 3, '0', STR_PAD_LEFT);

                    DB::table('inventory')->insert([
                        'id_detail_penerimaan' => $detailId,
                        'no_ref' => null,
                        'masuk' => $beratKarung,
                        'keluar' => 0,
                        'catatan' => 'Update penerimaan barang - ' . $noRef,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'no_inventory' => $noRef
                    ]);
                }
            }

            // Update GL entries
            $totalTagihan = (float)str_replace([',', '.'], ['', ''], $request->jumlah_tagihan);
            $biayaLain = (float)str_replace([',', '.'], ['', ''], $request->input('biaya-lain'));
            $grandTotal = $totalTagihan + $biayaLain;

            // Find existing GL header
            $glHeader = DB::table('gl_headers')
                ->where('ref_module', 'PENERIMAAN')
                ->where('ref_id', $id)
                ->first();

            if ($glHeader) {
                // Update GL Header
                DB::table('gl_headers')
                    ->where('id', $glHeader->id)
                    ->update([
                        'doc_date' => now(),
                        'posting_date' => now(),
                        'total_debit' => $grandTotal,
                        'total_credit' => $grandTotal,
                        'notes' => 'Updated from Master Penerimaan - Batch: ' . $masterPenerimaan->id_batch_mp,
                        'updated_by' => auth()->user()->id ?? 1,
                        'updated_at' => now(),
                    ]);

                // Update GL Lines
                DB::table('gl_lines')
                    ->where('header_id', $glHeader->id)
                    ->where('line_no', 1)
                    ->update([
                        'debit' => $grandTotal,
                        'memo' => 'Updated persediaan bahan baku - Batch: ' . $masterPenerimaan->id_batch_mp,
                    ]);

                DB::table('gl_lines')
                    ->where('header_id', $glHeader->id)
                    ->where('line_no', 2)
                    ->update([
                        'credit' => $grandTotal,
                        'memo' => 'Updated hutang GRNI - Batch: ' . $masterPenerimaan->id_batch_mp,
                    ]);
            } else {
                // Create new GL entries if not exist
                $glHeaderId = DB::table('gl_headers')->insertGetId([
                    'ref_module' => 'PENERIMAAN',
                    'ref_id' => $id,
                    'doc_no' => 'GR-' . $masterPenerimaan->id_batch_mp . '-' . now()->format('YmdHis'),
                    'doc_date' => now(),
                    'posting_date' => now(),
                    'currency' => 'IDR',
                    'total_debit' => $grandTotal,
                    'total_credit' => $grandTotal,
                    'status' => 'posted',
                    'notes' => 'Auto generate from Master Penerimaan Update - Batch: ' . $masterPenerimaan->id_batch_mp,
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
                        'debit' => $grandTotal,
                        'credit' => 0,
                        'memo' => 'Persediaan bahan baku - Batch: ' . $masterPenerimaan->id_batch_mp,
                        'batch_id' => null,
                    ],
                    [
                        'header_id' => $glHeaderId,
                        'line_no' => 2,
                        'account_id' => 9, // Akun Hutang/GRNI
                        'debit' => 0,
                        'credit' => $grandTotal,
                        'memo' => 'Hutang GRNI - Batch: ' . $masterPenerimaan->id_batch_mp,
                        'batch_id' => null,
                    ]
                ]);
            }

            DB::commit();

            return redirect()->route('master_penerimaan.index')
                ->with('success', 'Data penerimaan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
