<?php 

namespace App\Http\Controllers;

use Log;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BatchProductionRequest;

class BatchProductionController extends Controller
{
    public function index()
    {
        $items = DB::table('batchproduction')
            ->join('machines', 'batchproduction.id_mesin', '=', 'machines.id')
            ->join('method', 'batchproduction.id_method', '=', 'method.id')
            ->selectRaw('batchproduction.*, machines.merk as mesin_nama, method.deskripsi as method_deskripsi')
            ->selectRaw('batchproduction.*, machines.merk as mesin_nama, method.deskripsi as method_deskripsi')
            ->get();

       
        

        return view('batch-productions.index', compact('items'));

       

        return view('batch-productions.index',compact('items'));    }

    public function create()
    {
        $machines      = DB::table('machines')->pluck('merk','id');
         $methods       = DB::table('method')->pluck('deskripsi','id');
        $profiles      = DB::table('roast_profile')->pluck('deskripsi','id');
        $levels        = DB::table('level_roast')->pluck('name','id');
        $statuses      = ['open'=>'Open','on process'=>'On Process','closing'=>'Closing','cancel'=>'Cancel'];
        $attentions    = ['normal'=>'Normal','priority'=>'Priority'];
         $nextBatchId = $this->generateBatchId();
          $inventory = DB::table('inventory')
          ->get();
        return view('batch-productions.create', compact(
            'machines','methods','profiles','levels','statuses','attentions','nextBatchId','inventory'
        ));
    }

   private function generateBatchId()
    {
        $today = now(); // pakai Carbon juga bisa: Carbon::now()
        $prefix = 'BA' . $today->format('y') . $today->format('m') . $today->format('d'); // BA + tanggal + tahun, contoh: BA2525

        // Ambil data terakhir hari ini yang punya prefix yang sama
        $last = DB::table('batchproduction')
            ->where('no_batch', 'like', $prefix . '%')
            ->orderBy('no_batch', 'desc')
            ->first();

        if (!$last) {
            return $prefix . '0001';
        }

        // Ambil 4 digit terakhir dan increment
        $lastNumber = (int) substr($last->no_batch, -4);
        $nextNumber = $lastNumber + 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }


    public function store(Request $request)
    {

       
        // Validasi data utama batch production
        $batchData = $request->validate([
            'id_mesin' => 'required|integer',
            'method_id' => 'required|integer',
            'roast_profile_id' => 'required|integer',
            'level_roast_id' => 'required|integer',
            'berat_diroasting' => 'required|numeric|min:0',
            'status' => 'required|string',
            'attention' => 'required|string',
            'estimate_expire_date' => 'required|date',
            'catatan' => 'nullable|string|max:255',
            'no_batch' => 'required|string|unique:batchproduction,no_batch',
        ]);

        // Validasi data detail input (array)
        $detailData = $request->validate([
            'id_inventory' => 'required|array|min:1',
            'id_inventory.*' => 'required|integer|exists:inventory,id',
            'kadar_air' => 'required|array|min:1',
            'kadar_air.*' => 'required|numeric|min:0',
            'bulk_densitas' => 'required|array|min:1',
            'bulk_densitas.*' => 'required|numeric|min:0',
            'qty_out' => 'required|array|min:1',
            'qty_out.*' => 'required|numeric|min:0',
            'catatan_detail' => 'nullable|array',
            'catatan_detail.*' => 'nullable|string|max:255',
        ]);

        // Validasi total qty_out tidak melebihi berat_diroasting
        $totalQtyOut = array_sum($detailData['qty_out']);
        if ($totalQtyOut > $batchData['berat_diroasting']) {
            return back()->withErrors([
                'qty_out' => 'Total qty_out (' . $totalQtyOut . ' kg) melebihi target berat roasting (' . $batchData['berat_diroasting'] . ' kg)!'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. Insert batch production utama
            $batchId = DB::table('batchproduction')->insertGetId([
                'timestamp' => now(),
                'datetime' => now(),
                'estimate_expire_date' => $batchData['estimate_expire_date'],
                'id_mesin' => $batchData['id_mesin'],
                'id_method' => $batchData['method_id'],
                'id_roastprofile' => $batchData['roast_profile_id'],
                'id_level_rosting' => $batchData['level_roast_id'],
                'level_roasting_id' => $batchData['level_roast_id'],
                'berat_diroasting' => $batchData['berat_diroasting'],
                'status' => $batchData['status'],
                'attention' => $batchData['attention'],
                'catatan' => $batchData['catatan'] ?? null,
                'no_batch' => $batchData['no_batch']
            ]);

            // 2. Insert detail batch production input
            foreach ($detailData['id_inventory'] as $index => $inventoryId) {
                $qtyOut = $detailData['qty_out'][$index];
                $catatan = $detailData['catatan_detail'][$index] ?? null;

                // Insert ke batchproduction_input
                $inputId = DB::table('batchproduction_input')->insertGetId([
                    'batchproduction_id' => $batchId,
                    'inventory_id' => $inventoryId,
                    'kadar_air' => $detailData['kadar_air'][$index],
                    'bulk_densitas' => $detailData['bulk_densitas'][$index],
                    'qty_out' => $qtyOut,
                    'catatan' => $catatan,
                    
                ]);

                // 3. Create GL Header untuk setiap input
                $glHeaderId = DB::table('gl_headers')->insertGetId([
                    'ref_module' => 'batch_pic_raw',
                    'ref_id' => $inputId,
                    'doc_no' => 'GL-PICK-' . $inputId,
                    'doc_date' => now(),
                    'posting_date' => now(),
                    'currency' => 'IDR',
                    'total_debit' => $qtyOut,
                    'total_credit' => $qtyOut,
                    'status' => 'posted',
                    'notes' => 'Ambil bahan baku batch untuk #' . $batchId,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // 4. Create GL Lines (Debit & Credit)
                DB::table('gl_lines')->insert([
                    [
                        'header_id' => $glHeaderId,
                        'line_no' => 1,
                        'account_id' => 8, // Account untuk persediaan
                        'debit' => 0,
                        'credit' => $qtyOut,
                        'memo' => 'Persediaan keluar - Batch #' . $batchId,
                        'inventory_id' => $inventoryId,
                        'batch_id' => $batchId,
                    ],
                    [
                        'header_id' => $glHeaderId,
                        'line_no' => 2,
                        'account_id' => 8, // Account untuk COGS
                        'debit' => $qtyOut,
                        'credit' => 0,
                        'memo' => 'COGS - Batch #' . $batchId,
                        'inventory_id' => $inventoryId,
                        'batch_id' => $batchId,
                    ]
                ]);
            }

            DB::commit();
            return redirect()->route('batch-productions.index')->with('success', 'Batch Production berhasil dibuat dengan ' . count($detailData['id_inventory']) . ' detail input.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating batch production: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'Gagal menyimpan batch production: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Method untuk menambah detail input ke batch yang sudah ada
     */
    public function addInput(Request $request, $batchId)
    {
        $validated = $request->validate([
            'id_inventory' => 'required|integer|exists:inventory,id',
            'kadar_air' => 'required|numeric|min:0',
            'bulk_densitas' => 'required|numeric|min:0',
            'qty_out' => 'required|numeric|min:0',
            'catatan_detail' => 'nullable|string|max:255',
        ]);

        // Cek apakah batch production ada
        $batchProduction = DB::table('batchproduction')->find($batchId);
        if (!$batchProduction) {
            return back()->withErrors(['error' => 'Batch production tidak ditemukan.']);
        }

        // Cek total qty_out tidak melebihi target
        $totalQtyOut = DB::table('batchproduction_input')
            ->where('batchproduction_id', $batchId)
            ->sum('qty_out');

        if ($totalQtyOut + $validated['qty_out'] > $batchProduction->berat_diroasting) {
            return back()->withErrors([
                'qty_out' => 'Total qty_out akan melebihi target berat roasting! (Current: ' . $totalQtyOut . ' kg, Target: ' . $batchProduction->berat_diroasting . ' kg)'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // Insert ke batchproduction_input
            $inputId = DB::table('batchproduction_input')->insertGetId([
                'batchproduction_id' => $batchId,
                'inventory_id' => $validated['id_inventory'],
                'kadar_air' => $validated['kadar_air'],
                'bulk_densitas' => $validated['bulk_densitas'],
                'qty_out' => $validated['qty_out'],
                'catatan' => $validated['catatan_detail'],
                
            ]);

            // Create GL Header
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'batch_pic_raw',
                'ref_id' => $inputId,
                'doc_no' => 'GL-PICK-' . $inputId,
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $validated['qty_out'],
                'total_credit' => $validated['qty_out'],
                'status' => 'posted',
                'notes' => 'Ambil bahan baku batch untuk #' . $batchId,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create GL Lines
            DB::table('gl_lines')->insert([
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 1,
                    'account_id' => 8,
                    'debit' => 0,
                    'credit' => $validated['qty_out'],
                    'memo' => 'Persediaan keluar - Batch #' . $batchId,
                    'inventory_id' => $validated['id_inventory'],
                    'batch_id' => $batchId,
                ],
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 2,
                    'account_id' => 8,
                    'debit' => $validated['qty_out'],
                    'credit' => 0,
                    'memo' => 'COGS - Batch #' . $batchId,
                    'inventory_id' => $validated['id_inventory'],
                    'batch_id' => $batchId,
                ]
            ]);

            DB::commit();
            return back()->with('success', 'Detail input berhasil ditambahkan ke batch.');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error adding batch input: ' . $e->getMessage(), [
                'batch_id' => $batchId,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->withErrors([
                'error' => 'Gagal menambah input: ' . $e->getMessage()
            ])->withInput();
        }
    }


    public function edit($id)
    {
        $batch = DB::table('batchproduction')->find($id);
        $idBatch = base64_decode($id);
        $batch = DB::table('batchproduction')->find($idBatch);
    
        $machines      = DB::table('machines')->pluck('merk','id');
        $methods       = DB::table('method')->pluck('deskripsi','id');
        $profiles      = DB::table('roast_profile')->pluck('deskripsi','id');
        $levels        = DB::table('level_roast')->pluck('name','id');
        $statuses   = ['open'=>'Open','on process'=>'On Process','closing'=>'Closing','cancel'=>'Cancel'];
        $attentions = ['normal'=>'Normal','priority'=>'Priority'];
          $nextBatchId = $this->generateBatchId();
          $inventory = DB::table('inventory')->get();

        $details = DB::table('batchproduction_input')->where('batchproduction_id',$idBatch)->get();
       
        return view('batch-productions.edit', compact(
            'batch','machines','methods','profiles','levels','statuses','attentions','nextBatchId','details','inventory'
        ));
    }

   public function update(Request $request, $id)
    {
        // Cek apakah batch production ada
        $existingBatch = DB::table('batchproduction')->find($id);
        if (!$existingBatch) {
            return redirect()->route('batch-productions.index')->with('error', 'Batch production tidak ditemukan.');
        }

        // Validasi data utama batch production
        $batchData = $request->validate([
            'id_mesin' => 'required|integer',
            'method_id' => 'required|integer',
            'roast_profile_id' => 'required|integer',
            'level_roast_id' => 'required|integer',
            'berat_diroasting' => 'required|numeric|min:0',
            'status' => 'required|string',
            'attention' => 'required|string',
            'estimate_expire_date' => 'required|date',
            'catatan' => 'nullable|string|max:255',
            'no_batch' => 'required|string|unique:batchproduction,no_batch,' . $id,
        ]);

        // Validasi data detail input (array) - jika ada
        $detailData = [];
        if ($request->has('id_inventory') && is_array($request->id_inventory)) {
            $detailData = $request->validate([
                'id_inventory' => 'required|array|min:1',
                'id_inventory.*' => 'required|integer|exists:inventory,id',
                'kadar_air' => 'required|array|min:1',
                'kadar_air.*' => 'required|numeric|min:0',
                'bulk_densitas' => 'required|array|min:1',
                'bulk_densitas.*' => 'required|numeric|min:0',
                'qty_out' => 'required|array|min:1',
                'qty_out.*' => 'required|numeric|min:0',
                'catatan_detail' => 'nullable|array',
                'catatan_detail.*' => 'nullable|string|max:255',
            ]);

            // Validasi total qty_out tidak melebihi berat_diroasting
            $totalQtyOut = array_sum($detailData['qty_out']);
            if ($totalQtyOut > $batchData['berat_diroasting']) {
                return back()->withErrors([
                    'qty_out' => 'Total qty_out (' . $totalQtyOut . ' kg) melebihi target berat roasting (' . $batchData['berat_diroasting'] . ' kg)!'
                ])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // 1. Update batch production utama
            DB::table('batchproduction')->where('id', $id)->update([
                'estimate_expire_date' => $batchData['estimate_expire_date'],
                'id_mesin' => $batchData['id_mesin'],
                'id_method' => $batchData['method_id'],
                'id_roastprofile' => $batchData['roast_profile_id'],
                'id_level_rosting' => $batchData['level_roast_id'],
                'berat_diroasting' => $batchData['berat_diroasting'],
                'status' => $batchData['status'],
                'attention' => $batchData['attention'],
                'catatan' => $batchData['catatan'] ?? null,
                'no_batch' => $batchData['no_batch'],
               
            ]);

            // 2. Jika ada detail input baru, hapus yang lama dan insert yang baru
            if (!empty($detailData)) {
                // Hapus GL entries yang lama
                $oldInputs = DB::table('batchproduction_input')
                    ->where('batchproduction_id', $id)
                    ->get();

                foreach ($oldInputs as $oldInput) {
                    // Hapus GL lines
                    $glHeaders = DB::table('gl_headers')
                        ->where('ref_module', 'batch_pic_raw')
                        ->where('ref_id', $oldInput->id)
                        ->get();
                    
                    foreach ($glHeaders as $header) {
                        DB::table('gl_lines')->where('header_id', $header->id)->delete();
                        DB::table('gl_headers')->where('id', $header->id)->delete();
                    }
                }

                // Hapus detail input yang lama
                DB::table('batchproduction_input')->where('batchproduction_id', $id)->delete();

                // Insert detail input yang baru
                foreach ($detailData['id_inventory'] as $index => $inventoryId) {
                    $qtyOut = $detailData['qty_out'][$index];
                    $catatan = $detailData['catatan_detail'][$index] ?? null;

                    // Insert ke batchproduction_input
                    $inputId = DB::table('batchproduction_input')->insertGetId([
                        'batchproduction_id' => $id,
                        'inventory_id' => $inventoryId,
                        'kadar_air' => $detailData['kadar_air'][$index],
                        'bulk_densitas' => $detailData['bulk_densitas'][$index],
                        'qty_out' => $qtyOut,
                        'catatan' => $catatan,
                        
                    ]);

                    // 3. Create GL Header untuk setiap input
                    $glHeaderId = DB::table('gl_headers')->insertGetId([
                        'ref_module' => 'batch_pic_raw',
                        'ref_id' => $inputId,
                        'doc_no' => 'GL-PICK-' . $inputId,
                        'doc_date' => now(),
                        'posting_date' => now(),
                        'currency' => 'IDR',
                        'total_debit' => $qtyOut,
                        'total_credit' => $qtyOut,
                        'status' => 'posted',
                        'notes' => 'Ambil bahan baku batch untuk #' . $id . ' (Updated)',
                        'created_by' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // 4. Create GL Lines (Debit & Credit)
                    DB::table('gl_lines')->insert([
                        [
                            'header_id' => $glHeaderId,
                            'line_no' => 1,
                            'account_id' => 8,
                            'debit' => 0,
                            'credit' => $qtyOut,
                            'memo' => 'Persediaan keluar - Batch #' . $id . ' (Updated)',
                            'inventory_id' => $inventoryId,
                            'batch_id' => $id,
                           
                        ],
                        [
                            'header_id' => $glHeaderId,
                            'line_no' => 2,
                            'account_id' => 8,
                            'debit' => $qtyOut,
                            'credit' => 0,
                            'memo' => 'COGS - Batch #' . $id . ' (Updated)',
                            'inventory_id' => $inventoryId,
                            'batch_id' => $id,
                           
                        ]
                    ]);
                }
            }

            DB::commit();
            
            $message = 'Batch Production berhasil diupdate';
            if (!empty($detailData)) {
                $message .= ' dengan ' . count($detailData['id_inventory']) . ' detail input.';
            }
            
            return redirect()->route('batch-productions.index')->with('success', $message);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating batch production: ' . $e->getMessage(), [
                'batch_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'Gagal mengupdate batch production: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove specific input from batch production
     */
    public function removeInput($batchId, $inputId)
    {
        DB::beginTransaction();
        try {
            // Cek apakah input ada dan milik batch yang benar
            $input = DB::table('batchproduction_input')
                ->where('id', $inputId)
                ->where('batchproduction_id', $batchId)
                ->first();
                
            if (!$input) {
                return back()->withErrors(['error' => 'Detail input tidak ditemukan.']);
            }

            // Hapus GL entries
            $glHeaders = DB::table('gl_headers')
                ->where('ref_module', 'batch_pic_raw')
                ->where('ref_id', $inputId)
                ->get();
            
            foreach ($glHeaders as $header) {
                DB::table('gl_lines')->where('header_id', $header->id)->delete();
                DB::table('gl_headers')->where('id', $header->id)->delete();
            }

            // Hapus input
            DB::table('batchproduction_input')->where('id', $inputId)->delete();

            DB::commit();
            return back()->with('success', 'Detail input berhasil dihapus.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error removing batch input: ' . $e->getMessage(), [
                'batch_id' => $batchId,
                'input_id' => $inputId
            ]);
            
            return back()->withErrors([
                'error' => 'Gagal menghapus input: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {

       
        DB::table('batchproduction_input')->where('batchproduction_id',$id)->delete();
        DB::table('batchproduction')->where('id',$id)->delete();
        return redirect()->route('batch-productions.index')->with('success','Batch berhasil di hapus');
    }

    public function start($id)
    {
        DB::beginTransaction();

        try {
            $batch = DB::table('batchproduction')->where('id', $id)->first();

            if (!$batch) {
                throw new Exception('Batch tidak ditemukan.');
            }

            if ($batch->status !== 'open') {
                throw new Exception('Hanya batch dengan status OPEN yang bisa diproses.');
            }

                        // Ambil data input bahan baku dari batch ini
            $inputs = DB::table('batchproduction_input')
                ->where('batchproduction_id', $id)
                ->get();

            if ($inputs->isEmpty()) {
                throw new Exception('Tidak ada input bahan baku.');
            }

            // Gunakan qty_out karena berat_diroasting tidak tersedia
            $totalQty = $inputs->sum('qty_out');


            // Update status batch ke 'on process'
            DB::table('batchproduction')->where('id', $id)->update([
                'status' => 'on process',
            ]);

            // Simpan ke GL Header
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'batch_pick_raw',
                'ref_id' => $id,
                'doc_no' => 'GL-PICK-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $totalQty,
                'total_credit' => $totalQty,
                'status' => 'posted',
                'notes' => 'Pengambilan bahan baku untuk Batch #' . $id,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan ke GL Lines dan kurangi stok
            $lineNo = 1;
            foreach ($inputs as $input) {
                // Line Credit - Inventory keluar
                DB::table('gl_lines')->insert([
                    'header_id' => $glHeaderId,
                    'line_no' => $lineNo++,
                    'account_id' => 9, // ganti sesuai akun persediaan
                    'debit' => 0,
                    'credit' => $input->qty_out,
                    'memo' => 'Pengurangan stok bahan baku - Batch #' . $id,
                    'inventory_id' => $input->inventory_id,
                    'batch_id' => $id,
                    
                ]);

                // Line Debit - COGS / Bahan dalam proses
                DB::table('gl_lines')->insert([
                    'header_id' => $glHeaderId,
                    'line_no' => $lineNo++,
                    'account_id' => 9, // ganti sesuai akun HPP
                    'debit' => $input->qty_out,
                    'credit' => 0,
                    'memo' => 'COGS bahan baku - Batch #' . $id,
                    'inventory_id' => $input->inventory_id,
                    'batch_id' => $id,
                    
                ]);

                // Kurangi stok inventory
               DB::table('inventory')
        ->where('id', $input->inventory_id)
        ->increment('credit_qty', $input->qty_out);

            }

            DB::commit();
            return redirect()->back()->with('success', 'Batch berhasil dimulai dan bahan baku dikurangkan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memulai batch: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        DB::beginTransaction();

        try {
            $batch = DB::table('batchproduction')->where('id', $id)->first();

            if (!$batch) {
                throw new Exception('Batch tidak ditemukan.');
            }

            

            $inputs = DB::table('batchproduction_input')->where('batchproduction_id', $id)->get();

            if ($inputs->isEmpty()) {
                throw new Exception('Tidak ada input bahan baku.');
            }

            $totalQty = $inputs->sum('qty_out');

            // Update status ke cancelled
            DB::table('batchproduction')->where('id', $id)->update([
                'status' => 'cancelled',
            ]);

            // Buat jurnal pembalik
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'batch_cancel',
                'ref_id' => $id,
                'doc_no' => 'GL-CANCEL-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $totalQty,
                'total_credit' => $totalQty,
                'status' => 'posted',
                'notes' => 'Pembatalan batch #' . $id,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $lineNo = 1;
            foreach ($inputs as $input) {
                // Balik jurnal sebelumnya

                // Line Debit - Kembalikan ke inventory
                DB::table('gl_lines')->insert([
                    'header_id' => $glHeaderId,
                    'line_no' => $lineNo++,
                    'account_id' => 9, // Persediaan
                    'debit' => $input->qty_out,
                    'credit' => 0,
                    'memo' => 'Pembatalan batch, kembalikan stok - Batch #' . $id,
                    'inventory_id' => $input->inventory_id,
                    'batch_id' => $id,
                ]);

                // Line Credit - HPP dibalik
                DB::table('gl_lines')->insert([
                    'header_id' => $glHeaderId,
                    'line_no' => $lineNo++,
                    'account_id' => 9, // HPP
                    'debit' => 0,
                    'credit' => $input->qty_out,
                    'memo' => 'Pembatalan batch, rollback COGS - Batch #' . $id,
                    'inventory_id' => $input->inventory_id,
                    'batch_id' => $id,
                ]);

                // Kembalikan stok ke inventory (tambah debit_qty)
                DB::table('inventory')
                    ->where('id', $input->inventory_id)
                    ->increment('debit_qty', $input->qty_out);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Batch berhasil dibatalkan dan stok dikembalikan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan batch: ' . $e->getMessage());
        }
    }

   public function close($id)
    {
        DB::beginTransaction();

        try {
            $batch = DB::table('batchproduction')->where('id', $id)->first();

            if (!$batch) {
                throw new Exception('Batch tidak ditemukan.');
            }

            if ($batch->status !== 'on process') {
                throw new Exception('Hanya batch ON PROCESS yang bisa diselesaikan.');
            }

            // Ambil semua hasil roasting
            $results = DB::table('batchproductionresult')
                ->where('id_bacthproduction', $id)
                ->get();

            if ($results->isEmpty()) {
                throw new Exception('Hasil roasting belum tersedia.');
            }

            // Ambil input batch (berat bahan baku yang digunakan)
            $inputs = DB::table('batchproduction_input')
                ->where('batchproduction_id', $id)
                ->get();

            if ($inputs->isEmpty()) {
                throw new Exception('Input bahan baku belum tersedia.');
            }

            $totalQtyHasil = 0;
            $totalQtyBahanBaku = 0;
            $inventoryFinishId = null;

            foreach ($results as $result) {
                if (!$result->berat_akhir) {
                    throw new Exception('Berat akhir pada hasil belum lengkap.');
                }

                $totalQtyHasil += $result->berat_akhir;

                if (!$inventoryFinishId && isset($result->inventory_id)) {
                    $inventoryFinishId = $result->inventory_id;
                }
            }

            foreach ($inputs as $input) {
                if (!$input->qty_out) {
                    throw new Exception('Berat masuk pada input belum lengkap.');
                }

                $totalQtyBahanBaku += $input->qty_out;
            }

            if (!$inventoryFinishId) {
                $inventoryFinishId = 999; // fallback
            }

            // Update status batch
            DB::table('batchproduction')->where('id', $id)->update([
                'status' => 'closing',
            ]);

            // Buat jurnal GL
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module'   => 'batch_close',
                'ref_id'       => $id,
                'doc_no'       => 'GL-CLOSE-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'doc_date'     => now(),
                'posting_date' => now(),
                'currency'     => 'IDR',
                'total_debit'  => $totalQtyHasil,
                'total_credit' => $totalQtyBahanBaku,
                'status'       => 'posted',
                'notes'        => 'Penyelesaian batch #' . $id,
                'created_by'   => auth()->id(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $lineNo = 1;

            // Debit: Inventory FG
            DB::table('gl_lines')->insert([
                'header_id'    => $glHeaderId,
                'line_no'      => $lineNo++,
                'account_id'   => 9,
                'debit'        => $totalQtyHasil,
                'credit'       => 0,
                'memo'         => 'Masuk hasil roasting - Batch #' . $id,
                'inventory_id' => $inventoryFinishId,
                'batch_id'     => $id,
            ]);

            // Credit: WIP
            DB::table('gl_lines')->insert([
                'header_id'    => $glHeaderId,
                'line_no'      => $lineNo++,
                'account_id'   => 10,
                'debit'        => 0,
                'credit'       => $totalQtyBahanBaku,
                'memo'         => 'Keluar bahan dalam proses - Batch #' . $id,
                'inventory_id' => $inventoryFinishId,
                'batch_id'     => $id,
            ]);

            // Update inventory stok masuk
            DB::table('inventory')
                ->where('id', $inventoryFinishId)
                ->increment('debit_qty', $totalQtyHasil);

            // Simpan ke inventori finish good
            DB::table('inventorifinishgood')->insert([
                'id_inventory'         => $inventoryFinishId,
                'id_product'           => null,
                'timestamp'            => now(),
                'expired_date'         => null,
                'jenis'                => 'Single',
                'Id_batch_production'  => $id,
                'Id_postRoastblend'    => null,
                'Id_penjualan'         => null,
                'jml_masuk'            => $totalQtyHasil,
                'jml_keluar'           => 0,
                'Catatan'              => 'Hasil produksi batch #' . $id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Batch berhasil diselesaikan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyelesaikan batch: ' . $e->getMessage());
        }
    }









    








    public function list($id){
        $items = DB::table('batchproduction_input')
                ->where('batchproduction_id',$id)
                ->get();
      
        $inventory = DB::table('inventory')->get();
       
        return view('batch-productions.detail.list',compact('items','inventory','id'));
    }

   
    public function storeBatchInput(Request $request, $id)
    {
        $validated = $request->validate([
            'id_origin' => 'required|integer|exists:inventory,id',
            'kadar_air' => 'required|numeric',
            'bulk_densitas' => 'required|numeric',
            'qty_out' => 'required|numeric',
            'catatan' => 'nullable|string',
        ]);

       $batchProduction = DB::table('batchproduction')->find($id);

           $totalQtyOut = DB::table('batchproduction_input')
            ->where('batchproduction_id', $id)
            ->sum('qty_out');

        if ($totalQtyOut + $request->qty_out > $batchProduction->berat_diroasting) {
            return back()->with('error', 'Total qty_out melebihi target berat roasting!');
        }



        DB::beginTransaction();
        try {
            // Insert ke batchproduction_input
            $inputId = DB::table('batchproduction_input')->insertGetId([
                'batchproduction_id' => $id,
                'inventory_id' => $validated['id_origin'],
                'kadar_air' => $validated['kadar_air'],
                'bulk_densitas' => $validated['bulk_densitas'],
                'qty_out' => $validated['qty_out'],
                'catatan' => $validated['catatan'],
               
            ]);

            
            // ========== INVENTORY: Kredit ==========
            

            // ========== GL ==========
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'batch_pic_raw',
                'ref_id' => $inputId,
                'doc_no' => 'GL-PICK-' . $inputId,
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $validated['qty_out'],
                'total_credit' => $validated['qty_out'],
                'status' => 'posted',
                'notes' => 'Ambil bahan baku batch untuk' . $id,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('gl_lines')->insert([
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 1,
                    'account_id' => 8,
                    'debit' => 0,
                    'credit' => $validated['qty_out'],
                    'memo' => 'Persediaan keluar - Batch #' . $id,
                    'inventory_id' => $validated['id_origin'],
                    'batch_id' => $id,
                ],
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 2,
                    'account_id' => 8,
                    'debit' => $validated['qty_out'],
                    'credit' => 0,
                    'memo' => 'COGS - Batch #' . $id,
                    'inventory_id' => $validated['id_origin'], // ✅ TAMBAHKAN INI
                    'batch_id' => $id,
                ],
            ]);


            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e); // Debug langsung errornya
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }



    public function editList($id){
        $data = DB::table('batchproduction_input')->find($id);
         $inventory = DB::table('inventory')->get();
        return view('batch-productions.detail.edit',compact('data','inventory'));
    }

    public function updateBatchInput(Request $request, $id)
    {
        $validated = $request->validate([
            'id_origin' => 'required|integer|exists:inventory,id',
            'kadar_air' => 'required|numeric',
            'bulk_densitas' => 'required|numeric',
            'qty_out' => 'required|numeric',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Ambil data batch input yang akan diupdate
            $batchInput = DB::table('batchproduction_input')->where('id', $id)->first();
            
            if (!$batchInput) {
                throw new \Exception('Batch input tidak ditemukan');
            }

            $batchProductionId = $batchInput->batchproduction_id;
            
            // Ambil data batch production untuk validasi
            $batchProduction = DB::table('batchproduction')->find($batchProductionId);
            
            if (!$batchProduction) {
                throw new \Exception('Batch production tidak ditemukan');
            }

            // Validasi total qty_out (exclude current record)
            $totalQtyOut = DB::table('batchproduction_input')
                ->where('batchproduction_id', $batchProductionId)
                ->where('id', '!=', $id) // Exclude current record
                ->sum('qty_out');

            if ($totalQtyOut + $validated['qty_out'] > $batchProduction->berat_diroasting) {
                return back()->with('error', 'Total qty_out melebihi target berat roasting!');
            }

            // 1. Hapus GL records yang terkait dengan batch input ini
            $existingGlHeaders = DB::table('gl_headers')
                ->where('ref_module', 'batch_pic_raw')
                ->where('ref_id', $id)
                ->pluck('id')
                ->toArray();

            if (!empty($existingGlHeaders)) {
                DB::table('gl_lines')->whereIn('header_id', $existingGlHeaders)->delete();
                DB::table('gl_headers')->whereIn('id', $existingGlHeaders)->delete();
            }

            // 2. Update batchproduction_input
            DB::table('batchproduction_input')->where('id', $id)->update([
                'inventory_id' => $validated['id_origin'],
                'kadar_air' => $validated['kadar_air'],
                'bulk_densitas' => $validated['bulk_densitas'],
                'qty_out' => $validated['qty_out'],
                'catatan' => $validated['catatan'],
              
            ]);

            // 3. Buat GL header baru
            $glHeaderId = DB::table('gl_headers')->insertGetId([
                'ref_module' => 'batch_pic_raw',
                'ref_id' => $id,
                'doc_no' => 'GL-PICK-UPD-' . $id . '-' . now()->format('His'),
                'doc_date' => now(),
                'posting_date' => now(),
                'currency' => 'IDR',
                'total_debit' => $validated['qty_out'],
                'total_credit' => $validated['qty_out'],
                'status' => 'posted',
                'notes' => 'Updated - Ambil bahan baku batch untuk #' . $batchProductionId,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 4. Buat GL lines baru
            DB::table('gl_lines')->insert([
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 1,
                    'account_id' => 8,
                    'debit' => 0,
                    'credit' => $validated['qty_out'],
                    'memo' => 'Persediaan keluar (Updated) - Batch #' . $batchProductionId,
                    'inventory_id' => $validated['id_origin'],
                    'batch_id' => $batchProductionId,
                   
                ],
                [
                    'header_id' => $glHeaderId,
                    'line_no' => 2,
                    'account_id' => 8,
                    'debit' => $validated['qty_out'],
                    'credit' => 0,
                    'memo' => 'COGS (Updated) - Batch #' . $batchProductionId,
                    'inventory_id' => $validated['id_origin'],
                    'batch_id' => $batchProductionId,
                   
                ],
            ]);

            DB::commit();
            
            return redirect()->route('batch.list', $batchProductionId)->with('success', 'Data berhasil diubah.');
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengupdate: ' . $e->getMessage());
        }
    }

    public function deleteBatchInput($id){
         $idBatch = DB::table('batchproduction_input')->where('id',$id)->value('batchproduction_id');
        DB::table('batchproduction_input')->where('id',$id)->delete();
       

        return redirect()->route('batch.list',$idBatch)->with('success', 'Data berhasil dihapus.');

    }


    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $reportData = $this->getBatchProductionData($startDate, $endDate);
        $summary = $this->getBatchProductionSummary($startDate, $endDate);

        return view('batch-productions.report', compact('reportData', 'summary', 'startDate', 'endDate'));
    }

    private function getBatchProductionData($startDate, $endDate)
    {
        return DB::table('batchproduction as bp')
            ->leftJoin('batchproduction_input as bpi', 'bp.id', '=', 'bpi.batchproduction_id')
            ->leftJoin('batchproductionresult as bpr', 'bp.id', '=', DB::raw('bpr.id_bacthproduction'))
            ->leftJoin('level_roast as lr', 'bp.level_roasting_id', '=', 'lr.id')
            ->leftJoin('machines as m', 'bp.id_mesin', '=', 'm.id')
            ->select(
                'bp.id',
                'bp.datetime',
                'bp.status',
                'bp.attention',
                'bp.estimate_expire_date',
                'bp.berat_diroasting_kg',
                'bp.catatan',
                'lr.name as level_roast',
                'm.merk as mesin',
                'm.location',
                DB::raw('SUM(bpi.qty_out) as total_input'),
                DB::raw('AVG(bpi.kadar_air) as avg_kadar_air_input'),
                DB::raw('AVG(bpi.bulk_densitas) as avg_bulk_densitas'),
                DB::raw('SUM(bpr.berat_akhir) as total_output'),
                DB::raw('AVG(bpr.kadar_air) as avg_kadar_air_output'),
                DB::raw('AVG(bpr.agtron) as avg_agtron'),
                DB::raw('AVG(bpr.cupping_score) as avg_cupping_score')
            )
            ->whereBetween('bp.datetime', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(
                'bp.id', 'bp.datetime', 'bp.status', 'bp.attention', 'bp.estimate_expire_date',
                'bp.berat_diroasting_kg', 'bp.catatan', 'lr.name', 'm.merk', 'm.location'
            )
            ->orderBy('bp.datetime', 'desc')
            ->get();
    }

    private function getBatchProductionSummary($startDate, $endDate)
    {
        $base = DB::table('batchproduction as bp')
            ->leftJoin('batchproduction_input as bpi', 'bp.id', '=', 'bpi.batchproduction_id')
            ->leftJoin('batchproductionresult as bpr', 'bp.id', '=', DB::raw('bpr.id_bacthproduction'))
            ->whereBetween('bp.datetime', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        $summary = $base->select([
            DB::raw('COUNT(DISTINCT bp.id) as total_batch'),
            DB::raw('SUM(bpi.qty_out) as total_input'),
            DB::raw('SUM(bpr.berat_akhir) as total_output'),
            DB::raw('AVG(bpr.cupping_score) as avg_cupping_score'),
            DB::raw('AVG(bpr.kadar_air) as avg_kadar_air_output')
        ])->first();

        // Breakdown status
        $statusBreakdown = clone $base;
        $summary->status_breakdown = $statusBreakdown->select(
            'bp.status',
            DB::raw('COUNT(bp.id) as total')
        )->groupBy('bp.status')->get();

        // Breakdown attention
        $attentionBreakdown = clone $base;
        $summary->attention_breakdown = $attentionBreakdown->select(
            'bp.attention',
            DB::raw('COUNT(bp.id) as total')
        )->groupBy('bp.attention')->get();

        return $summary;
    }
}
