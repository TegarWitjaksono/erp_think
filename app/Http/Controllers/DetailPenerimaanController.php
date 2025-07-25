<?php

namespace App\Http\Controllers;

use Log;
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
                    'id_detail_penerimaan' => $idDetail,
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

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
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
        $request->validate([
            'id_penerimaan' => 'required',
            'id_suplier' => 'required',
            'id_jenis' => 'required',
            'id_varietas' => 'required',
            'id_grade' => 'required',
            'id_origin' => 'required',
            'kadar_air' => 'required|numeric',
            'bulk_value' => 'required|numeric',
            'bulk_unit' => 'required|in:kg,liter',
            'id_kemasan' => 'required',
            'berat' => 'required|numeric',
            'jumlah' => 'required|integer',
            'size' => 'required',
        ]);

        // Get current detail data
        $currentDetail = DB::table('detail_penerimaan')
            ->where('id_detail_penerimaan', $id)
            ->first();

        // Calculate jumlah_tot
        $jumlah_tot = $request->berat * $request->jumlah;

        // Combine bulk_value and bulk_unit into a single bulk field
        $bulk = $request->bulk_value . ' ' . $request->bulk_unit;

        DB::table('detail_penerimaan')
            ->where('id_detail_penerimaan', $id)
            ->update([
                'id_penerimaan' => $request->id_penerimaan,
                'id_batch' => $currentDetail->id_batch, // Use existing id_batch
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
                'jumlah_tot' => $jumlah_tot,
                'size' => $request->size,
            ]);

        return redirect()->route('detail_penerimaan.index', [
            'id_penerimaan' => $request->id_penerimaan
        ])->with('success', 'Detail penerimaan berhasil diupdate');
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