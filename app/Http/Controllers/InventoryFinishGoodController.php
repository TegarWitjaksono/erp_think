<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryFinishGoodController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('inventorifinishgood')
            ->join('master_barang', 'inventorifinishgood.id_product', '=', 'master_barang.id_barang')
            ->join('post_roast_blend', 'post_roast_blend.id', '=', 'inventorifinishgood.Id_postRoastblend')
            ->join('batchproduction', 'batchproduction.id', '=', 'inventorifinishgood.Id_batch_production')
            ->join('level_roast', 'level_roast.id', '=', 'batchproduction.id_level_rosting')

            
            ->select(
                'inventorifinishgood.*',
                'master_barang.nama_barang as product_name',
                'level_roast.name',
                'post_roast_blend.flavour_note'
            );

        if ($request->filled('level_roast')) {
            $query->where('master_barang.level_roast', $request->level_roast);
        }

        if ($request->filled('flavour_note')) {
            $query->where('master_barang.flavour_note', 'like', '%'.$request->flavour_note.'%');
        }

        if ($request->filled('expired_before')) {
            $query->where('inventorifinishgood.expired_date', '<=', $request->expired_before);
        }

        $items = $query->orderBy('inventorifinishgood.expired_date')->paginate(15);

        return view('inventory.finish_good.index', compact('items'));
    }

    public function create()
    {
        return view('inventory.finish_good.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:master_barang,id',
            'expired_date' => 'required|date',
            'jenis' => 'required|in:PreRoastBlend,PostRoast,Single',
            'batch_production_id' => 'nullable|exists:batch_productions,id',
            'post_roast_blend_id' => 'nullable|exists:post_roast_blends,id',
            'penjualan_id' => 'nullable|exists:penjualans,id',
            'jml_masuk' => 'nullable|integer|min:0',
            'jml_keluar' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        $data['timestamp'] = now();
        DB::table('inventorifinishgood')->insert($data);

        return redirect()->route('inventory.finish_good.index')
                         ->with('success', 'Inventory record added successfully');
    }

    public function edit($id)
    {
        $item = DB::table('inventorifinishgood')->find($id);
        return view('inventory.finish_good.edit', compact('item'));
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
