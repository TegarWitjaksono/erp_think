<?php 

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        // Debug: Log semua data yang masuk
        Log::info('PostRoastBlend Store Request:', $request->all());
        
        try {
            // Validasi dasar
            $validator = Validator::make($request->all(), [
                'expired_date' => 'required|date',
                'cupping_score' => 'nullable|numeric|min:0|max:100',
                'note_flavour' => 'nullable|string',
                'catatan' => 'nullable|string',
                'total_weight' => 'required|numeric|min:0.001',
                'status' => 'required|in:close,cancel',
                'details' => 'required|array|min:1',
                'details.*.inventorifinishgood_id' => 'required|exists:inventorifinishgood,id',
                'details.*.reference_id' => 'nullable|string',
                'details.*.description' => 'nullable|string',
                'details.*.quantity_out' => 'required|numeric|min:0.01',
            ], [
                'details.required' => 'Minimal harus ada 1 detail blend',
                'details.*.inventorifinishgood_id.required' => 'Inventory harus dipilih',
                'details.*.inventorifinishgood_id.exists' => 'Inventory tidak valid',
                'details.*.quantity_out.required' => 'Jumlah out harus diisi',
                'details.*.quantity_out.min' => 'Jumlah out minimal 0.01',
                'total_weight.required' => 'Berat total harus diisi',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Validasi gagal: ' . $validator->errors()->first());
            }

            // Validasi total weight sama dengan sum quantity_out
            $totalQuantityOut = 0;
            foreach ($request->details as $detail) {
                $totalQuantityOut += floatval($detail['quantity_out']);
            }
            
            Log::info("Total Quantity Out: $totalQuantityOut, Total Weight: {$request->total_weight}");
            
            if (abs($totalQuantityOut - floatval($request->total_weight)) > 0.01) {
                return redirect()->back()
                    ->withErrors(['total_weight' => "Total weight ({$request->total_weight}) harus sama dengan jumlah quantity out ({$totalQuantityOut})"])
                    ->withInput()
                    ->with('error', 'Total weight tidak sesuai dengan quantity out');
            }

            // Validasi stok tersedia
            foreach ($request->details as $detail) {
                $inventory = DB::table('inventorifinishgood')
                    ->where('id', $detail['inventorifinishgood_id'])
                    ->first();
                
                if (!$inventory) {
                    return redirect()->back()
                        ->withErrors(['details' => 'Inventory tidak ditemukan'])
                        ->withInput()
                        ->with('error', 'Inventory ID ' . $detail['inventorifinishgood_id'] . ' tidak ditemukan');
                }

                $availableStock = $inventory->jml_masuk - $inventory->jml_keluar;
                Log::info("Inventory ID {$inventory->id}: Available Stock = $availableStock, Requested = {$detail['quantity_out']}");
                
                if (floatval($detail['quantity_out']) > $availableStock) {
                    return redirect()->back()
                        ->withErrors(['details' => "Stok inventory ID {$inventory->id} tidak mencukupi. Tersedia: {$availableStock}, Diminta: {$detail['quantity_out']}"])
                        ->withInput()
                        ->with('error', "Stok tidak mencukupi untuk inventory ID {$inventory->id}");
                }
            }

            DB::beginTransaction();
            
            try {
                // Cek apakah tabel ada
                if (!DB::getSchemaBuilder()->hasTable('post_roast_blend')) {
                    throw new Exception('Tabel post_roast_blend tidak ditemukan');
                }

                // Generate ID untuk post roast blend
                $todayCount = DB::table('post_roast_blend')
                    ->count();
                
                $blendId = 'PRB-' . date('Ymd') . '-' . str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);
                
                Log::info("Generated Blend ID: $blendId");

                // Data untuk insert
                $blendData = [
                   'timestamp' => now(),
                    'est_expired_date' => $request->expired_date,
                    'cupping_score' => $request->cupping_score ? floatval($request->cupping_score) : null,
                    'note_flavour' => $request->note_flavour,
                    'catatan' => $request->catatan,
                    'berat_total' => floatval($request->total_weight),
                    'status' => $request->status
                ];

                Log::info('Inserting blend data:', $blendData);

                // Insert ke post_roast_blends
                $postRoastBlendId = DB::table('post_roast_blend')->insertGetId($blendData);
                
                if (!$postRoastBlendId) {
                    throw new Exception('Gagal insert ke tabel post_roast_blends');
                }

                Log::info("Inserted Post Roast Blend ID: $postRoastBlendId");

                // Insert detail dan update inventory
                foreach ($request->details as $index => $detail) {
                    // Cek apakah tabel detail ada
                    if (!DB::getSchemaBuilder()->hasTable('post_roast_blend_details')) {
                        throw new Exception('Tabel post_roast_blend_details tidak ditemukan');
                    }

                    $detailData = [
                        'post_roast_blend_id' => $postRoastBlendId,
                        'inventorifinishgood_id' => $detail['inventorifinishgood_id'],
                        'reference_id' => $detail['reference_id'],
                        'description' => $detail['description'],
                        'quantity_out' => floatval($detail['quantity_out']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    Log::info("Inserting detail $index:", $detailData);

                    // Insert detail blend
                    $detailId = DB::table('post_roast_blend_details')->insertGetId($detailData);
                    
                    if (!$detailId) {
                        throw new Exception("Gagal insert detail ke-$index");
                    }

                    // Update jml_keluar di inventorifinishgood
                    $updated = DB::table('inventorifinishgood')
                        ->where('id', $detail['inventorifinishgood_id'])
                        ->increment('jml_keluar', floatval($detail['quantity_out']));

                    if (!$updated) {
                        throw new Exception("Gagal update inventory ID {$detail['inventorifinishgood_id']}");
                    }

                    Log::info("Updated inventory ID {$detail['inventorifinishgood_id']}, added {$detail['quantity_out']} to jml_keluar");
                }

                // Jika status close, buat entry baru di inventorifinishgood untuk hasil blend
                

                DB::commit();
                Log::info('Transaction committed successfully');

                return redirect()->route('post-roast-blends.index')
                    ->with('success', "Post Roast Blend {$blendId} berhasil disimpan");

            } catch (Exception $e) {
                DB::rollback();
                Log::error('Database transaction failed:', [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);
                
                return redirect()->back()
                    ->withErrors(['database' => 'Database error: ' . $e->getMessage()])
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan database: ' . $e->getMessage());
            }

        } catch (Exception $e) {
            Log::error('General error in store method:', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk debugging - bisa dihapus setelah selesai
    public function debugTables()
    {
        try {
            // Cek tabel yang ada
            $tables = DB::select('SHOW TABLES');
            Log::info('Available tables:', $tables);

            // Cek struktur tabel inventorifinishgood
            if (DB::getSchemaBuilder()->hasTable('inventorifinishgood')) {
                $columns = DB::select('DESCRIBE inventorifinishgood');
                Log::info('inventorifinishgood structure:', $columns);
            }

            // Cek data sample
            $sampleInventory = DB::table('inventorifinishgood')
                ->where('id', 1)
                ->first();
            Log::info('Sample inventory data:', (array)$sampleInventory);

            return response()->json([
                'tables' => $tables,
                'sample_inventory' => $sampleInventory
            ]);

        } catch (Exception $e) {
            Log::error('Debug tables error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()]);
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