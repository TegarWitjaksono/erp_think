<?php 

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return view('batch-productions.create', compact(
            'machines','methods','profiles','levels','statuses','attentions'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        DB::table('batchproduction')->insert($data);
        // Validasi request
        $data = $request->validate([
            'id_mesin' => 'required',
            'method_id' => 'required',
            'roast_profile_id' => 'required',
            'level_roast_id' => 'required',
            'berat_diroasting' => 'required',
            'status' => 'required',
            'attention' => 'required',
            'estimate_expire_date' => 'required',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Siapkan data insert ke tabel batch_production
        $insertData = [
            'timestamp' => now(),
            'datetime' => now(),
            'estimate_expire_date' => $data['estimate_expire_date'],
            'id_mesin' => $data['id_mesin'],
            'id_method' => $data['method_id'],
            'id_roastprofile' => $data['roast_profile_id'],
            'id_level_rosting' => $data['level_roast_id'],
            'berat_diroasting' => $data['berat_diroasting'],
            'status' => $data['status'],
            'attention' => $data['attention'],
            'catatan' => $data['catatan'] ?? null,
        ];

        // Simpan ke database
        DB::table('batchproduction')->insert($insertData);

        return redirect()->route('batch-productions.index')->with('success', 'Batch berhasil dibuat');
    }


    public function edit($id)
    {
        $batch = DB::table('batchproduction')->find($id);
        $idBatch = base64_decode($id);
        $batch = DB::table('batchproduction')->find($idBatch);
    
        $machines      = DB::table('machines')->pluck('merk','id');
        $method       = DB::table('method')->pluck('deskripsi','id');
        $profiles      = DB::table('roast_profile')->pluck('deskripsi','id');
        $levels        = DB::table('level_roast')->pluck('name','id');
        $statuses   = ['open'=>'Open','on process'=>'On Process','closing'=>'Closing','cancel'=>'Cancel'];
        $attentions = ['normal'=>'Normal','priority'=>'Priority'];

        return view('batch-productions.edit', compact(
            'batch','machines','method','profiles','levels','statuses','attentions'
        ));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        DB::table('batchproduction')->where('id',$id)->update($data);

     
        // Validasi request
        $data = $request->validate([
            'id_mesin' => 'required',
            'method_id' => 'required',
            'roast_profile_id' => 'required',
            'level_roast_id' => 'required',
            'berat_diroasting' => 'required',
            'status' => 'required',
            'attention' => 'required',
            'estimate_expire_date' => 'required',
            'catatan' => 'nullable|string|max:255',
        ]);


        // Siapkan data insert ke tabel batch_production
        $updateData = [
            'timestamp' => now(),
            'datetime' => now(),
            'estimate_expire_date' => $data['estimate_expire_date'],
            'id_mesin' => $data['id_mesin'],
            'id_method' => $data['method_id'],
            'id_roastprofile' => $data['roast_profile_id'],
            'id_level_rosting' => $data['level_roast_id'],
            'berat_diroasting' => $data['berat_diroasting'],
            'status' => $data['status'],
            'attention' => $data['attention'],
            'catatan' => $data['catatan'] ?? null,
        ];

        // Simpan ke database
        DB::table('batchproduction')->where('id',$id)->update($updateData);

        return redirect()->route('batch-productions.index')->with('success', 'BatchProduction berhasil diubah');
    }

    public function destroy($id)
    {

        DB::table('batchproduction')->where('id',$id)->update(['is_active'=>false,'updated_by'=>auth()->id()]);
        DB::table('batchproduction')->where('id',$id)->delete();
        return redirect()->route('batch-productions.index')->with('success','Batch dinonaktifkan');
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

        // Ambil hasil roasting dari tabel result
        $results = DB::table('batchproduction_input')
            ->where('batchproduction_id', $id)
            ->get();

        if ($results->isEmpty()) {
            throw new Exception('Hasil roasting belum tersedia.');
        }

        $totalQtyHasil = 0;
        $inventoryFinishId = null;

        foreach ($results as $result) {
            if (!$result->berat_akhir) {
                throw new Exception('Data berat akhir belum lengkap.');
            }

            $totalQtyHasil += $result->berat_akhir;

            // Ambil salah satu inventory_id sebagai acuan (misalnya dari karung pertama)
            if (!$inventoryFinishId && $result->inventory_id) {
                $inventoryFinishId = $result->inventory_id;
            }
        }

        if (!$inventoryFinishId) {
            $inventoryFinishId = 999; // fallback jika tidak ada
        }

        // Update status batch ke 'closed'
        DB::table('batchproduction')->where('id', $id)->update([
            'status' => 'closed',
            'updated_at' => now(),
        ]);

        // Buat jurnal hasil produksi
        $glHeaderId = DB::table('gl_headers')->insertGetId([
            'ref_module' => 'batch_close',
            'ref_id' => $id,
            'doc_no' => 'GL-CLOSE-' . str_pad($id, 4, '0', STR_PAD_LEFT),
            'doc_date' => now(),
            'posting_date' => now(),
            'currency' => 'IDR',
            'total_debit' => $totalQtyHasil,
            'total_credit' => $totalQtyHasil,
            'status' => 'posted',
            'notes' => 'Penyelesaian batch #' . $id,
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lineNo = 1;

        // Debit - Inventory Finish Goods
        DB::table('gl_lines')->insert([
            'header_id' => $glHeaderId,
            'line_no' => $lineNo++,
            'account_id' => 9, // Inventory Finish Goods
            'debit' => $totalQtyHasil,
            'credit' => 0,
            'memo' => 'Masuk hasil roasting - Batch #' . $id,
            'inventory_id' => $inventoryFinishId,
            'batch_id' => $id,
        ]);

        // Credit - WIP (Work in Process)
        DB::table('gl_lines')->insert([
            'header_id' => $glHeaderId,
            'line_no' => $lineNo++,
            'account_id' => 10, // HPP atau akun WIP
            'debit' => 0,
            'credit' => $totalQtyHasil,
            'memo' => 'Keluar bahan dalam proses - Batch #' . $id,
            'inventory_id' => $inventoryFinishId,
            'batch_id' => $id,
        ]);

        // Tambahkan ke inventory hasil jadi
        DB::table('inventory')
            ->where('id', $inventoryFinishId)
            ->increment('debit_qty', $totalQtyHasil);

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

        DB::table('batchproduction_input')->where('id',$id)->update([
            'inventory_id' => $validated['id_origin'],
            'kadar_air' => $validated['kadar_air'],
            'bulk_densitas' => $validated['bulk_densitas'],
            'qty_out' => $validated['qty_out'],
            'catatan' => $validated['catatan'],
        ]);

        $idBatch = DB::table('batchproduction_input')->where('id',$id)->value('batchproduction_id');

        return redirect()->route('batch.list',$idBatch)->with('success', 'Data berhasil diubah.');
    }

    public function deleteBatchInput($id){
         $idBatch = DB::table('batchproduction_input')->where('id',$id)->value('batchproduction_id');
        DB::table('batchproduction_input')->where('id',$id)->delete();
       

        return redirect()->route('batch.list',$idBatch)->with('success', 'Data berhasil dihapus.');

    }
}
