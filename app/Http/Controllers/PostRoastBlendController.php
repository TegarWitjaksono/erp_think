<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PostRoastBlendController extends Controller
{
    public function index()
    {
        $blends = DB::table('post_roast_blend')
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Fetch details and inventory in separate query
        $blendIds = $blends->pluck('id')->toArray();
        $details = DB::table('post_roast_blend_item as d')
            ->join('inventorifinishgood as i', 'd.inventorifinishgood_id', '=', 'i.id')
            ->whereIn('d.post_roast_blend_id', $blendIds)
            ->select('d.post_roast_blend_id', 'd.description', 'd.quantity_out', 'i.product', 'i.expired_date')
            ->get()
            ->groupBy('post_roast_blend_id');

        return view('post_roast_blend.index', compact('blends', 'details'));
    }

    public function create()
    {
        $inventory = DB::table('inventorifinishgood')
            ->whereRaw('jml_masuk > jml_keluar')
            ->get();

        return view('post_roast_blend.create', compact('inventory'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expired_date'    => 'required|date',
            'cupping_score'   => 'nullable|numeric',
            'note_flavour'    => 'nullable|string',
            'total_weight'    => 'required|numeric',
            'status'          => 'required|in:close,cancel',
            'details'         => 'required|array|min:1',
            'details.*.inventorifinishgood_id' => 'required|integer|exists:inventorifinishgood,id',
            'details.*.description' => 'required|string',
            'details.*.quantity_out' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Insert main blend
            $blendId = DB::table('post_roast_blend')->insertGetId([
                'expired_date'  => $data['expired_date'],
                'cupping_score' => $data['cupping_score'],
                'note_flavour'  => $data['note_flavour'],
                'total_weight'  => $data['total_weight'],
                'status'        => $data['status'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $totalOut = 0;

            foreach ($data['details'] as $item) {
                $totalOut += $item['quantity_out'];

                // Insert detail
                DB::table('post_roast_blend_item')->insert([
                    'post_roast_blend_id'      => $blendId,
                    'inventorifinishgood_id' => $item['inventorifinishgood_id'],
                    'reference_id'             => $item['reference_id'] ?? null,
                    'description'              => $item['description'],
                    'quantity_out'             => $item['quantity_out'],
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                // Update inventory: increment jml_keluar
                DB::table('inventorifinishgood')
                    ->where('id', $item['inventorifinishgood_id'])
                    ->increment('jml_keluar', $item['quantity_out']);

                // Optionally insert new inventory record for blended FG here
            }

            if (abs($totalOut - $data['total_weight']) > 0.001) {
                throw new Exception('Total quantity out must equal total_weight');
            }

            DB::commit();

            return redirect()->route('post-roast-blends.index')
                             ->with('success', 'Post Roast Blend saved successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $blend = DB::table('post_roast_blend')->where('id', $id)->first();
        $details = DB::table('post_roast_blend_item as d')
            ->join('inventorifinishgood as i', 'd.inventorifinishgood_id', '=', 'i.id')
            ->where('d.post_roast_blend_id', $id)
            ->select('d.*', 'i.product', 'i.expired_date')
            ->get();

        return view('post_roast_blend.show', compact('blend', 'details'));
    }

    public function edit($id)
    {
        $blend = DB::table('post_roast_blend')->where('id', $id)->first();
        $inventory = DB::table('inventorifinishgood')
            ->whereRaw('jml_masuk > jml_keluar')
            ->get();

        return view('post_roast_blend.edit', compact('blend', 'inventory'));
    }

    public function update(Request $request, $id)
    {
        // Implement similar logic to store(), handling reversals and re-inserts via Query Builder
    }

    public function destroy($id)
    {
        DB::table('post_roast_blend')->where('id', $id)->delete();
        return back()->with('success', 'Blend deleted');
    }
}