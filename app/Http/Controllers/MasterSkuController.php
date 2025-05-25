<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterSkuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $barang = DB::table('master_barang')->get();
        $data = DB::table('master_sku')
            ->leftJoin('master_barang', 'master_barang.id_barang', '=', 'master_sku.id_barang')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'master_sku.id_suplier')
            ->select('master_sku.*', 'master_barang.nama_barang', 'suppliers.name')
            ->get();

        return view('master_sku.index', compact('data', 'barang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $barang = DB::table('master_barang')->get();
        return view('master_sku.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|integer',
            'qty' => 'required|integer',
        ]);

        // Tambahkan id_suppiler = 0 secara manual
        $validated['id_suplier'] = 0;
        // Tambahkan id_varietas = 0 secara manual
        $validated['id_varietas'] = 0;

        DB::table('master_sku')
            ->insert($validated);
        return redirect()->route('master_sku.index')->with('success', 'Stok created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function show($id)
    {
        // You can customize this as needed, for now just return a 404 or a simple response
        abort(404, 'Not implemented');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $barang = DB::table('master_barang')->get();
        $data = DB::table('master_sku')
            ->where('id_sku', base64_decode($id))
            ->first();
        return view('master_sku.edit', compact('data', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_barang' => 'required|integer',
            'qty' => 'required|integer',
        ]);

        // Tambahkan id_suppiler = 0 secara manual
        $validated['id_suplier'] = 0;
        // Tambahkan id_varietas = 0 secara manual
        $validated['id_varietas'] = 0;

        DB::table('master_sku')
            ->where('id_sku', $id)
            ->update($validated);
        return redirect()->route('master_sku.index')->with('success', 'Stok updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::table('master_sku')
            ->where('id_sku', $id)
            ->delete();
        return redirect()->route('master_sku.index')->with('success', 'Stok deleted successfully');
    }
}
