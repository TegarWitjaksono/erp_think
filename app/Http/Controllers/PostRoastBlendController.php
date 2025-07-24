<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PostRoastBlendController extends Controller
{
    public function index()
    {
        $blends = DB::table('post_roast_blend')->orderBy('id', 'desc')->paginate(15);
        return view('post_roast_blend.index', compact('blends'));
    }

    public function create()
    {
        $inventory = DB::table('inventorifinishgood')->whereRaw('jml_masuk > jml_keluar')->get();
        return view('post_roast_blend.create', compact('inventory'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expired_date' => 'required|date',
            'cupping_score' => 'nullable|numeric',
            'note_flavour' => 'nullable|string',
            'catatan' => 'nullable|string',
            'total_weight' => 'required|numeric',
            'status' => 'required|in:close,cancel',
            'details' => 'required|array|min:1',
            'details.*.inventorifinishgood_id' => 'required|integer|exists:inventorifinishgood,id',
            'details.*.reference_id' => 'nullable|string',
            'details.*.quantity_out' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $blendId = DB::table('post_roast_blend')->insertGetId([
                'expired_date'  => $data['expired_date'],
                'cupping_score' => $data['cupping_score'],
                'note_flavour'  => $data['note_flavour'],
                'catatan'       => $data['catatan'],
                'total_weight'  => $data['total_weight'],
                'status'        => $data['status'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $sumOut = 0;
            foreach ($data['details'] as $item) {
                $sumOut += $item['quantity_out'];
                DB::table('post_roast_blend_details')->insert([
                    'post_roast_blend_id'      => $blendId,
                    'inventorifinishgood_id' => $item['inventorifinishgood_id'],
                    'reference_id'             => $item['reference_id'],
                    'description'              => DB::table('inventorifinishgood')->where('id', $item['inventorifinishgood_id'])->value(DB::raw("CONCAT(bean,' / ', level_roast,' / ', note_flavour)")),
                    'quantity_out'             => $item['quantity_out'],
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                DB::table('inventorifinishgood')->where('id', $item['inventorifinishgood_id'])->increment('jml_keluar', $item['quantity_out']);
            }
            if (abs($sumOut - $data['total_weight']) > 0.001) {
                throw new Exception('Total Jumlah out harus sama dengan Berat total');
            }
            DB::commit();
            return redirect()->route('post-roast-blends.index')->with('success', 'Blend berhasil disimpan');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $blend = DB::table('post_roast_blend')->where('id',$id)->first();
        $details = DB::table('post_roast_blend_details')->where('post_roast_blend_id',$id)->get();
        return view('post_roast_blend.show', compact('blend','details'));
    }

    public function edit($id)
    {
        $blend = DB::table('post_roast_blend')->where('id',$id)->first();
        $inventory = DB::table('inventorifinishgood')->whereRaw('jml_masuk > jml_keluar')->get();
        $details = DB::table('post_roast_blend_details')->where('post_roast_blend_id',$id)->get();
        return view('post_roast_blend.edit', compact('blend','inventory','details'));
    }

    public function update(Request $request,$id)
    {
        // implement reversal & re-insert logic similarly
    }

    public function destroy($id)
    {
        DB::table('post_roast_blend')->where('id',$id)->delete();
        return back()->with('success','Blend dihapus');
    }
}