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

        // Validasi stok tersedia dan hitung total cost
        $inventoryDetails = [];
        $totalCost = 0;

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

            // Hitung cost untuk GL
            $unitCost = $inventory->harga_per_kg ?? 0;
            $itemTotalCost = $unitCost * floatval($detail['quantity_out']);
            $totalCost += $itemTotalCost;

            $inventoryDetails[] = [
                'inventory_id' => $inventory->id,
                'quantity_out' => floatval($detail['quantity_out']),
                'unit_cost' => $unitCost,
                'total_cost' => $itemTotalCost
            ];
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

            // ========== TAMBAHAN: GL POSTING ==========

            // Buat GL Header
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'POST_ROAST_BLEND',
                'ref_id' => $postRoastBlendId,
                'doc_no' => 'GL-BLEND-' . $blendId,
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $totalCost,
                'total_credit' => $totalCost,
                'status' => 'posted',
                'notes' => "Post Roast Blend - {$blendId}",
                'created_by' => auth()->id() ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info("Created GL Header ID: $glHeaderId with total cost: $totalCost");

            // Buat GL Lines
            $glLines = [];
            $lineNo = 1;

            // GL Lines untuk setiap item yang dikeluarkan (CREDIT Finished Goods)
            foreach ($inventoryDetails as $invDetail) {
                $glLines[] = [
                    'header_id' => $glHeaderId,
                    'line_no' => $lineNo++,
                    'account_id' => 10, // Akun Finished Goods Inventory
                    'debit' => 0,
                    'credit' => $invDetail['total_cost'],
                    'memo' => "FG Out - Blend Component Inventory ID {$invDetail['inventory_id']}",
                    'inventory_id' => $invDetail['inventory_id'],
                    'batch_id' => $postRoastBlendId,

                ];
            }

            // GL Line untuk Work in Process atau Manufacturing (DEBIT)
            if ($request->status === 'close') {
                // Jika close, anggap masuk ke FG baru (tapi belum ada inventory ID)
                $glLines[] = [
                    'header_id' => $glHeaderId,
                    'line_no' => $lineNo,
                    'account_id' => 10, // Akun Finished Goods Inventory (blend result)
                    'debit' => $totalCost,
                    'credit' => 0,
                    'memo' => "FG In - Blend Result {$blendId}",
                    'inventory_id' => null, // Tambahkan inventory_id null
                    'batch_id' => $postRoastBlendId,

                ];
            } else {
                // Jika cancel, masuk ke WIP atau expense
                $glLines[] = [
                    'header_id' => $glHeaderId,
                    'line_no' => $lineNo,
                    'account_id' => 12, // Akun Work in Process atau Manufacturing Expense
                    'debit' => $totalCost,
                    'credit' => 0,
                    'memo' => "Blend WIP/Expense - {$blendId}",
                    'inventory_id' => null, // Tambahkan inventory_id null
                    'batch_id' => $postRoastBlendId,

                ];
            }

            // Insert semua GL Lines
            DB::table('gl_lines')->insert($glLines);

            Log::info("Created " . count($glLines) . " GL Lines for blend {$blendId}");

            // Jika status close, buat entry baru di inventorifinishgood untuk hasil blend


            DB::commit();
            Log::info('Transaction committed successfully');

            return redirect()->route('post-roast-blends.index')
                ->with('success', "Post Roast Blend {$blendId} berhasil disimpan dengan GL posting");

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
        $details = DB::table('post_roast_blend_details')
        ->join('post_roast_blend','post_roast_blend.id','=','post_roast_blend_details.post_roast_blend_id')
        ->where('post_roast_blend_id',$id)
        ->select('post_roast_blend_details.*','post_roast_blend.catatan as catatan_blend')
        ->get();

        return view('post_roast_blend.detail', compact('blend','details'));
    }

   public function edit($id)
    {
        try {
            // Ambil data blend utama
            $blend = DB::table('post_roast_blend')->where('id', $id)->first();

            if (!$blend) {
                return redirect()->route('post-roast-blends.index')
                    ->with('error', 'Data Post Roast Blend tidak ditemukan');
            }

            // Ambil detail blend
            $details = DB::table('post_roast_blend_details')
            ->where('post_roast_blend_id', $id)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            });



            // Ambil inventory yang tersedia
            $inventory = DB::table('inventorifinishgood')
                ->select('id', 'jenis', 'Id_batch_production', 'expired_date', 'jml_masuk', 'jml_keluar')
                ->whereRaw('(jml_masuk - jml_keluar) > 0') // Hanya yang ada stoknya
                ->orderBy('jenis')
                ->orderBy('Id_batch_production')
                ->get();

            Log::info('Edit PostRoastBlend:', [
                'id' => $id,
                'blend' => $blend,
                'details_count' => $details->count(),
                'inventory_count' => $inventory->count()
            ]);

            return view('post_roast_blend.edit', compact('blend', 'details', 'inventory'));

        } catch (Exception $e) {
            Log::error('Error in edit method:', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->route('post_roast_blends.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

   public function update(Request $request, $id)
    {


        try {
            // Cek apakah record exists
            $existingBlend = DB::table('post_roast_blend')->where('id', $id)->first();
            if (!$existingBlend) {
                return redirect()->route('post-roast-blends.index')
                    ->with('error', 'Data Post Roast Blend tidak ditemukan');
            }

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

            DB::beginTransaction();

            try {
                // ========== REVERSE PREVIOUS TRANSACTIONS ==========

                // 1. Ambil detail lama untuk di-reverse
                $oldDetails = DB::table('post_roast_blend_details')
                    ->where('post_roast_blend_id', $id)
                    ->get();

                // 2. Reverse inventory movements (kembalikan jml_keluar ke posisi semula)
                foreach ($oldDetails as $oldDetail) {
                    DB::table('inventorifinishgood')
                        ->where('id', $oldDetail->inventorifinishgood_id)
                        ->decrement('jml_keluar', $oldDetail->quantity_out);

                    Log::info("Reversed inventory ID {$oldDetail->inventorifinishgood_id}, reduced jml_keluar by {$oldDetail->quantity_out}");
                }

                // 3. Delete old GL entries
                $oldGLHeader = DB::table('gl_headers')
                    ->where('ref_module', 'POST_ROAST_BLEND')
                    ->where('ref_id', $id)
                    ->first();

                if ($oldGLHeader) {
                    DB::table('gl_lines')->where('header_id', $oldGLHeader->id)->delete();
                    DB::table('gl_headers')->where('id', $oldGLHeader->id)->delete();
                    Log::info("Deleted old GL entries for header ID: {$oldGLHeader->id}");
                }

                // 4. Delete old detail records
                DB::table('post_roast_blend_details')->where('post_roast_blend_id', $id)->delete();
                Log::info("Deleted old blend details for ID: $id");

                // ========== VALIDATE NEW DATA ==========

                // Validasi stok tersedia dan hitung total cost untuk data baru
                $inventoryDetails = [];
                $totalCost = 0;

                foreach ($request->details as $detail) {
                    $inventory = DB::table('inventorifinishgood')
                        ->where('id', $detail['inventorifinishgood_id'])
                        ->first();

                    if (!$inventory) {
                        throw new Exception('Inventory tidak ditemukan: ID ' . $detail['inventorifinishgood_id']);
                    }

                    $availableStock = $inventory->jml_masuk - $inventory->jml_keluar;
                    Log::info("Inventory ID {$inventory->id}: Available Stock = $availableStock, Requested = {$detail['quantity_out']}");

                    if (floatval($detail['quantity_out']) > $availableStock) {
                        throw new Exception("Stok inventory ID {$inventory->id} tidak mencukupi. Tersedia: {$availableStock}, Diminta: {$detail['quantity_out']}");
                    }

                    // Hitung cost untuk GL
                    $unitCost = $inventory->harga_per_kg ?? 0;
                    $itemTotalCost = $unitCost * floatval($detail['quantity_out']);
                    $totalCost += $itemTotalCost;

                    $inventoryDetails[] = [
                        'inventory_id' => $inventory->id,
                        'quantity_out' => floatval($detail['quantity_out']),
                        'unit_cost' => $unitCost,
                        'total_cost' => $itemTotalCost
                    ];
                }

                // ========== UPDATE MAIN RECORD ==========

                $blendData = [
                    'est_expired_date' => $request->expired_date,
                    'cupping_score' => $request->cupping_score ? floatval($request->cupping_score) : null,
                    'note_flavour' => $request->note_flavour,
                    'catatan' => $request->catatan,
                    'berat_total' => floatval($request->total_weight),
                    'status' => $request->status,

                ];

                Log::info('Updating blend data:', $blendData);

                $updated = DB::table('post_roast_blend')
                    ->where('id', $id)
                    ->update($blendData);

                if (!$updated) {
                    throw new Exception('Gagal update post_roast_blend');
                }

                Log::info("Updated Post Roast Blend ID: $id");

                // ========== INSERT NEW DETAILS ==========

                foreach ($request->details as $index => $detail) {
                    $detailData = [
                        'post_roast_blend_id' => $id,
                        'inventorifinishgood_id' => $detail['inventorifinishgood_id'],
                        'reference_id' => $detail['reference_id'],
                        'description' => $detail['description'],
                        'quantity_out' => floatval($detail['quantity_out']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    Log::info("Inserting new detail $index:", $detailData);

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

                // ========== CREATE NEW GL POSTING ==========

                // Generate blend ID untuk GL reference
                $blendId = 'PRB-' . date('Ymd') . '-' . str_pad($id, 3, '0', STR_PAD_LEFT);

                // Buat GL Header baru
                $glHeaderId = DB::table('gl_headers')->insertGetId([
                    'ref_module' => 'POST_ROAST_BLEND',
                    'ref_id' => $id,
                    'doc_no' => 'GL-BLEND-' . $blendId . '-UPD',
                    'doc_date' => now(),
                    'posting_date' => now(),
                    'currency' => 'IDR',
                    'total_debit' => $totalCost,
                    'total_credit' => $totalCost,
                    'status' => 'posted',
                    'notes' => "Post Roast Blend Update - {$blendId}",
                    'created_by' => auth()->id() ?? 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Log::info("Created new GL Header ID: $glHeaderId with total cost: $totalCost");

                // Buat GL Lines baru
                $glLines = [];
                $lineNo = 1;

                // GL Lines untuk setiap item yang dikeluarkan (CREDIT Finished Goods)
                foreach ($inventoryDetails as $invDetail) {
                    $glLines[] = [
                        'header_id' => $glHeaderId,
                        'line_no' => $lineNo++,
                        'account_id' => 10, // Akun Finished Goods Inventory
                        'debit' => 0,
                        'credit' => $invDetail['total_cost'],
                        'memo' => "FG Out - Blend Component Inventory ID {$invDetail['inventory_id']} (Updated)",
                        'inventory_id' => $invDetail['inventory_id'],
                        'batch_id' => $id,
                    ];
                }

                // GL Line untuk Work in Process atau Manufacturing (DEBIT)
                if ($request->status === 'close') {
                    $glLines[] = [
                        'header_id' => $glHeaderId,
                        'line_no' => $lineNo,
                        'account_id' => 10, // Akun Finished Goods Inventory (blend result)
                        'debit' => $totalCost,
                        'credit' => 0,
                        'memo' => "FG In - Blend Result {$blendId} (Updated)",
                        'inventory_id' => null,
                        'batch_id' => $id,
                    ];
                } else {
                    $glLines[] = [
                        'header_id' => $glHeaderId,
                        'line_no' => $lineNo,
                        'account_id' => 12, // Akun Work in Process atau Manufacturing Expense
                        'debit' => $totalCost,
                        'credit' => 0,
                        'memo' => "Blend WIP/Expense - {$blendId} (Updated)",
                        'inventory_id' => null,
                        'batch_id' => $id,
                    ];
                }

                // Insert semua GL Lines baru
                DB::table('gl_lines')->insert($glLines);

                Log::info("Created " . count($glLines) . " new GL Lines for updated blend {$blendId}");

                DB::commit();
                Log::info('Update transaction committed successfully');

                return redirect()->route('post-roast-blends.index')
                    ->with('success', "Post Roast Blend {$blendId} berhasil diupdate dengan GL posting");

            } catch (Exception $e) {
                DB::rollback();
                Log::error('Database transaction failed during update:', [
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
            Log::error('General error in update method:', [
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

    public function destroy($id)
    {
        DB::table('post_roast_blend')->where('id',$id)->delete();
        return back()->with('success','Blend dihapus');
    }
}
