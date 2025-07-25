<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryFinishGoodController extends Controller
{
    public function index(Request $request)
{
    $query = DB::table('inventorifinishgood')
        ->leftJoin('master_barang', 'inventorifinishgood.id_product', '=', 'master_barang.id_barang')
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
}
