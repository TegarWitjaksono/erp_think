<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterPenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('master_penerimaan')
            ->join('suppliers', 'master_penerimaan.id_suplier', '=', 'suppliers.id')
            ->join('jenis', 'master_penerimaan.id_jenis', '=', 'jenis.id_jenis')
            ->join('varietas', 'master_penerimaan.id_varietas', '=', 'varietas.id_varietas')
            ->join('grade', 'master_penerimaan.id_grade', '=', 'grade.id_grade')
            ->join('origin', 'master_penerimaan.id_origin', '=', 'origin.id_origin')
            ->select(
                'master_penerimaan.*',
                'suppliers.name as nama_suplier',
                'jenis.deskripsi as nama_jenis',
                'varietas.deskripsi as nama_varietas',
                'grade.deskripsi as nama_grade',
                'origin.deskripsi as nama_origin'
            )
            ->get();

        $suppliers = DB::table('suppliers')->get();
        $jenis = DB::table('jenis')->get();
        $varietas = DB::table('varietas')->get();
        $grade = DB::table('grade')->get();
        $origin = DB::table('origin')->get();

        return view('master_penerimaan.index', compact('data', 'suppliers', 'jenis', 'varietas', 'grade', 'origin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_suplier' => 'required',
            'id_jenis' => 'required',
            'id_varietas' => 'required',
            'id_grade' => 'required',
            'id_origin' => 'required',
            'kadar_air' => 'required|numeric',
            'bulk_value' => 'required|numeric', // Validate the bulk value
            'bulk_unit' => 'required|in:kg,liter', // Validate the bulk unit
            'id_kemasan' => 'required',
            'berat' => 'required|numeric',
            'jumlah' => 'required|integer',
            'size' => 'required',
        ]);

        // Generate batch ID
        $lastBatch = DB::table('master_penerimaan')->orderBy('id_batch', 'desc')->first();
        $batchNumber = 1;
        
        if ($lastBatch) {
            $lastBatchNumber = (int) substr($lastBatch->id_batch, 1);
            $batchNumber = $lastBatchNumber + 1;
        }
        
        $id_batch = 'B' . str_pad($batchNumber, 4, '0', STR_PAD_LEFT);
        
        // Calculate jumlah_tot
        $jumlah_tot = $request->berat * $request->jumlah;

        // Combine bulk_value and bulk_unit into a single bulk field
        $bulk = $request->bulk_value . ' ' . $request->bulk_unit;

        DB::table('master_penerimaan')->insert([
            'id_batch' => $id_batch,
            'id_suplier' => $request->id_suplier,
            'id_jenis' => $request->id_jenis,
            'id_varietas' => $request->id_varietas,
            'id_grade' => $request->id_grade,
            'id_origin' => $request->id_origin,
            'kadar_air' => $request->kadar_air,
            'bulk' => $bulk, // Store the combined value
            'id_kemasan' => $request->id_kemasan,
            'berat' => $request->berat,
            'jumlah' => $request->jumlah,
            'jumlah_tot' => $jumlah_tot,
            'size' => $request->size,
        ]);

        return redirect()->route('master_penerimaan.index')->with('success', 'Data penerimaan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB::table('master_penerimaan')->where('id_penerimaan', $id)->first();
        
        // Split bulk into value and unit
        if ($data) {
            $bulkParts = explode(' ', $data->bulk);
            $data->bulk_value = $bulkParts[0] ?? '';
            $data->bulk_unit = $bulkParts[1] ?? 'kg';
        }
        
        $suppliers = DB::table('suppliers')->get();
        $jenis = DB::table('jenis')->get();
        $varietas = DB::table('varietas')->get();
        $grade = DB::table('grade')->get();
        $origin = DB::table('origin')->get();
    
        return view('master_penerimaan.edit', compact('data', 'suppliers', 'jenis', 'varietas', 'grade', 'origin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_suplier' => 'required',
            'id_jenis' => 'required',
            'id_varietas' => 'required',
            'id_grade' => 'required',
            'id_origin' => 'required',
            'kadar_air' => 'required|numeric',
            'bulk_value' => 'required|numeric', // Validate the bulk value
            'bulk_unit' => 'required|in:kg,liter', // Validate the bulk unit
            'id_kemasan' => 'required',
            'berat' => 'required|numeric',
            'jumlah' => 'required|integer',
            'size' => 'required',
        ]);

        // Calculate jumlah_tot
        $jumlah_tot = $request->berat * $request->jumlah;

        // Combine bulk_value and bulk_unit into a single bulk field
        $bulk = $request->bulk_value . ' ' . $request->bulk_unit;

        DB::table('master_penerimaan')->where('id_penerimaan', $id)->update([
            'id_suplier' => $request->id_suplier,
            'id_jenis' => $request->id_jenis,
            'id_varietas' => $request->id_varietas,
            'id_grade' => $request->id_grade,
            'id_origin' => $request->id_origin,
            'kadar_air' => $request->kadar_air,
            'bulk' => $bulk, // Store the combined value
            'id_kemasan' => $request->id_kemasan,
            'berat' => $request->berat,
            'jumlah' => $request->jumlah,
            'jumlah_tot' => $jumlah_tot,
            'size' => $request->size,
        ]);

        return redirect()->route('master_penerimaan.index')->with('success', 'Data penerimaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('master_penerimaan')->where('id_penerimaan', $id)->delete();
        return redirect()->route('master_penerimaan.index')->with('success', 'Data penerimaan berhasil dihapus');
    }
}