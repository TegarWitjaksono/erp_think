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
            ->get();

        return view('inventory.raw.index', compact('items'));
    }

    public function create()
    {
        $penerimaanList = DB::table('master_penerimaan')->get();
        $details = DB::table('detail_penerimaan')->get();
        $karung = DB::table('karung')->get();
       
        return view('inventory.raw.create', compact('penerimaanList','karung','details'));
    }
    public function store(Request $request)
    {
       $data = $request->validate([
        'penerimaan_id' => 'required',
        'karung_id' => 'required',
        'catatan' => 'required',
        'id_detail_penerimaan' => 'required',
        'kadar_air' => 'required',
        'bulk_densitas' => 'required',
        'debit_qty' => 'required',
        'credit_qty' => 'required'
       ]);
       $data['timestamp'] = now();
       $data['roast_batch_id'] = null;
       $data['gl_trx_id'] = null;
         DB::table('inventory')->insert($data);

        return redirect()->route('inventory.index')->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
          $penerimaanList = DB::table('master_penerimaan')->get();
        $details = DB::table('detail_penerimaan')->get();
        $karung = DB::table('karung')->get();
        $data = DB::table('inventory')->find($id);
       
        return view('inventory.raw.edit', compact('penerimaanList','karung','details','data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
        'penerimaan_id' => 'required',
        'karung_id' => 'required',
        'catatan' => 'required',
        'id_detail_penerimaan' => 'required',
        'kadar_air' => 'required',
        'bulk_densitas' => 'required',
        'debit_qty' => 'required',
        'credit_qty' => 'required'
       ]);
       $data['timestamp'] = now();
       $data['roast_batch_id'] = null;
       $data['gl_trx_id'] = null;
         DB::table('inventory')->where('id',$id)->update($data);

        return redirect()->route('inventory.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        DB::table('inventory')->where('id',$id)->delete();

        return redirect()->route('inventory.raw.index')->with('success', 'Data dinonaktifkan');
    }
}
