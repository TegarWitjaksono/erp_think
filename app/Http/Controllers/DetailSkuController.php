<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DetailSkuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $id_sku
     * @return \Illuminate\Http\Response
     */
    public function index($id_sku)
    {
        $id_sku = base64_decode($id_sku);
        
        // Get SKU data
        $sku = DB::table('master_sku')
            ->leftJoin('master_barang', 'master_barang.id_barang', '=', 'master_sku.id_barang')
            ->select('master_sku.*', 'master_barang.nama_barang')
            ->where('master_sku.id_sku', $id_sku)
            ->first();
            
        // Get detail SKU data
        $details = DB::table('detail_stok_sku')
            ->where('id_sku', $id_sku)
            ->orderBy('cdate', 'desc')
            ->get();
            
        // Get the latest record ID for edit permission check
        $latestRecordId = null;
        if(count($details) > 0) {
            $latestRecordId = $details->first()->id_detail_sku;
        }
            
        return view('detail_sku.index', compact('sku', 'details', 'latestRecordId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_sku)
    {
        $id_sku = base64_decode($id_sku);
        
        // Get SKU data
        $sku = DB::table('master_sku')
            ->leftJoin('master_barang', 'master_barang.id_barang', '=', 'master_sku.id_barang')
            ->select('master_sku.*', 'master_barang.nama_barang')
            ->where('master_sku.id_sku', $id_sku)
            ->first();
            
        return view('detail_sku.create', compact('sku'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sku' => 'required|integer',
            'stok_awal' => 'required|integer',
            'stok_masuk' => 'required|integer',
            'stok_keluar' => 'required|integer',
        ]);
        
        // Calculate stok_akhir
        $validated['stok_akhir'] = $validated['stok_awal'] + $validated['stok_masuk'] - $validated['stok_keluar'];
        
        // Add current date and user_id with explicit timezone
        $validated['cdate'] = now()->setTimezone('Asia/Jakarta');
        $validated['user_id'] = Auth::id();
        
        // Generate a new id_detail_sku (get max id and increment)
        $maxId = DB::table('detail_stok_sku')->max('id_detail_sku') ?? 0;
        $validated['id_detail_sku'] = $maxId + 1;
        
        // Insert into detail_sku
        DB::table('detail_stok_sku')->insert($validated);
        
        // Update master_sku qty
        DB::table('master_sku')
            ->where('id_sku', $validated['id_sku'])
            ->update(['qty' => $validated['stok_akhir']]);
            
        return redirect()->route('detail_sku.index', base64_encode($validated['id_sku']))
            ->with('success', 'Detail SKU created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        
        // Get detail SKU data
        $detail = DB::table('detail_stok_sku')
            ->where('id_detail_sku', $id)
            ->first();
            
        // Get SKU data
        $sku = DB::table('master_sku')
            ->leftJoin('master_barang', 'master_barang.id_barang', '=', 'master_sku.id_barang')
            ->select('master_sku.*', 'master_barang.nama_barang')
            ->where('master_sku.id_sku', $detail->id_sku)
            ->first();
            
        return view('detail_sku.edit', compact('detail', 'sku'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = base64_decode($id);
        
        $validated = $request->validate([
            'stok_awal' => 'required|integer',
            'stok_masuk' => 'required|integer',
            'stok_keluar' => 'required|integer',
        ]);
        
        // Calculate stok_akhir
        $validated['stok_akhir'] = $validated['stok_awal'] + $validated['stok_masuk'] - $validated['stok_keluar'];
        
        // Get detail SKU data
        $detail = DB::table('detail_stok_sku')
            ->where('id_detail_sku', $id)
            ->first();
            
        // Update detail_sku
        DB::table('detail_stok_sku')
            ->where('id_detail_sku', $id)
            ->update($validated);
            
        // Update master_sku qty
        DB::table('master_sku')
            ->where('id_sku', $detail->id_sku)
            ->update(['qty' => $validated['stok_akhir']]);
            
        return redirect()->route('detail_sku.index', base64_encode($detail->id_sku))
            ->with('success', 'Detail SKU updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);
        
        // Get detail SKU data
        $detail = DB::table('detail_stok_sku')
            ->where('id_detail_sku', $id)
            ->first();
            
        if (!$detail) {
            return redirect()->back()->with('error', 'Record not found');
        }
        
        // Get all records for this SKU ordered by date
        $allRecords = DB::table('detail_stok_sku')
            ->where('id_sku', $detail->id_sku)
            ->orderBy('cdate', 'desc')
            ->get();
            
        // Check if this is the latest record
        $isLatestRecord = $allRecords->first()->id_detail_sku == $id;
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Delete the record
            DB::table('detail_stok_sku')
                ->where('id_detail_sku', $id)
                ->delete();
                
            // If it's the latest record, update master_sku qty to the previous record's final stock
            if ($isLatestRecord) {
                $newQty = 0;
                
                // If there are still records left, use the new latest record's final stock
                if (count($allRecords) > 1) {
                    $newLatestRecord = DB::table('detail_stok_sku')
                        ->where('id_sku', $detail->id_sku)
                        ->orderBy('cdate', 'desc')
                        ->first();
                        
                    $newQty = $newLatestRecord->stok_akhir;
                }
                
                // Update master_sku qty
                DB::table('master_sku')
                    ->where('id_sku', $detail->id_sku)
                    ->update(['qty' => $newQty]);
            }
            
            DB::commit();
            
            return redirect()->route('detail_sku.index', base64_encode($detail->id_sku))
                ->with('success', 'Stock movement record deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}