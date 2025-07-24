<?php

namespace App\Http\Controllers;

use App\Models\InventoryBahanBaku;
use App\Http\Requests\InventoryBahanBakuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InventoryBahanBakuController extends Controller
{
    public function index()
    {
        $items = DB::table('inventory')
            ->join('master_penerimaan', 'inventory.penerimaan_id', '=', 'master_penerimaan.id_penerimaan')
            ->select('inventory.*', 'master_penerimaan.*')
            ->paginate(15);

        return view('inventory.raw.index', compact('items'));
    }

    public function create()
    {
        $penerimaanList = DB::table('penerimaan')->pluck('nama', 'id');
        return view('inventory.raw.create', compact('penerimaanList'));
    }
    public function store(Request $request)
    {
        $data = $request->validated();
        $data['qty_keluar'] = 0;
        $data['is_active'] = true;
        $data['created_by'] = auth()->id();
        DB::table('inventory')->insert($data);

        return redirect()->route('inventory.raw.index')->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $inventory = DB::table('inventory')->find($id);
        $penerimaanList = DB::table('penerimaan')->pluck('nama', 'id');

        return view('inventory.raw.edit', [
            'inventoryBahanBaku' => $inventory,
            'penerimaanList' => $penerimaanList
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        DB::table('inventory')->where('id', $id)->update($data);

        return redirect()->route('inventory.raw.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::table('inventory')->where('id', $id)->update([
            'is_active' => false,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('inventory.raw.index')->with('success', 'Data dinonaktifkan');
    }
}
