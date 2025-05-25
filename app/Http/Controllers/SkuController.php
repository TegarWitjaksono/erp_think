<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all SKUs with related data
        $data = DB::table('sku')
            ->leftJoin('master_barang', 'master_barang.id_barang', '=', 'sku.id_barang')
            ->leftJoin('varietas', 'varietas.id_varietas', '=', 'sku.id_varietas')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'sku.id_supplier')
            ->select('sku.*', 'master_barang.nama_barang', 'varietas.deskripsi as nama_varietas', 'suppliers.name as nama_supplier')
            ->get();

        // Get data for dropdowns
        $barang = DB::table('master_barang')->get();
        $varietas = DB::table('varietas')->get();
        $suppliers = DB::table('suppliers')->get();

        return view('sku.index', compact('data', 'barang', 'varietas', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sku' => 'nullable|string',
            'id_barang' => 'required|integer',
            'id_varietas' => 'required|integer',
            'id_supplier' => 'required|integer',
        ]);

        // Insert into sku table
        DB::table('sku')->insert($validated);
        
        return redirect()->route('sku.index')->with('success', 'SKU created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        
        // Get SKU data
        $data = DB::table('sku')
            ->where('id_sku_asli', $id)
            ->first();
            
        // Get data for dropdowns
        $barang = DB::table('master_barang')->get();
        $varietas = DB::table('varietas')->get();
        $suppliers = DB::table('suppliers')->get();
        
        return view('sku.edit', compact('data', 'barang', 'varietas', 'suppliers'));
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
        $validated = $request->validate([
            'nama_sku' => 'nullable|string',
            'id_barang' => 'required|integer',
            'id_varietas' => 'required|integer',
            'id_supplier' => 'required|integer',
        ]);

        // Update sku
        DB::table('sku')
            ->where('id_sku_asli', $id)
            ->update($validated);
            
        return redirect()->route('sku.index')->with('success', 'SKU updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete sku
        DB::table('sku')
            ->where('id_sku_asli', $id)
            ->delete();
            
        return redirect()->route('sku.index')->with('success', 'SKU deleted successfully');
    }
}