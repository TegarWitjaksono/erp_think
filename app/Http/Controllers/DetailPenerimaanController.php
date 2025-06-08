<?php

namespace App\Http\Controllers;

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
        // Get id_penerimaan from request
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

        // Get master_penerimaan for the form
        $master_penerimaan = DB::table('master_penerimaan')
            ->when($id_penerimaan, function($query) use ($id_penerimaan) {
                return $query->where('id_penerimaan', $id_penerimaan);
            })
            ->get();

        // Get other required data
        $suppliers = DB::table('suppliers')->get();
        $jenis = DB::table('jenis')->get();
        $varietas = DB::table('varietas')->get();
        $grade = DB::table('grade')->get();
        $origin = DB::table('origin')->get();

        return view('detail_penerimaan.index', compact(
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
            'id_batch' => 'required|string|max:100',
            'id_suplier' => 'required',
            'id_jenis' => 'required',
            'id_varietas' => 'required',
            'id_grade' => 'required',
            'id_origin' => 'required',
            'kadar_air' => 'required|numeric',
            'bulk_value' => 'required|numeric',
            'bulk_unit' => 'required|in:kg,liter',
            'id_kemasan' => 'required|integer', // Change validation to integer
            'berat' => 'required|numeric',
            'jumlah' => 'required|integer',
            'size' => 'required|string|max:50',
        ]);

        // Combine bulk value and unit
        $bulk = $request->bulk_value . ' ' . $request->bulk_unit;

        try {
            DB::table('detail_penerimaan')->insert([
                'id_penerimaan' => $request->id_penerimaan,
                'id_batch' => $request->id_batch,
                'id_suplier' => $request->id_suplier,
                'id_jenis' => $request->id_jenis,
                'id_varietas' => $request->id_varietas,
                'id_grade' => $request->id_grade,
                'id_origin' => $request->id_origin,
                'kadar_air' => $request->kadar_air,
                'bulk' => $bulk,
                'id_kemasan' => (int)$request->id_kemasan, // Cast to integer
                'berat' => $request->berat,
                'jumlah' => $request->jumlah,
                'jumlah_tot' => $request->berat * $request->jumlah,
                'size' => $request->size,
            ]);

            return redirect()->back()->with('success', 'Detail penerimaan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
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
        // Get detail_penerimaan data with all its relations
        $data = DB::table('detail_penerimaan')
            ->join('master_penerimaan', 'detail_penerimaan.id_penerimaan', '=', 'master_penerimaan.id_penerimaan')
            ->where('detail_penerimaan.id_detail_penerimaan', $id)
            ->first();
        
        // Split bulk into value and unit
        if ($data) {
            $bulkParts = explode(' ', $data->bulk);
            $data->bulk_value = $bulkParts[0] ?? '';
            $data->bulk_unit = $bulkParts[1] ?? 'kg';
        }
        
        // Get master_penerimaan data (no need to select id_batch separately)
        $master_penerimaan = DB::table('master_penerimaan')
            ->get();
    
        // Get other required data    
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