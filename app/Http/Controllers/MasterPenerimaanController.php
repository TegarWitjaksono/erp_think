<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterPenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $data = DB::table('master_penerimaan')->get();
        $nextBatchId = $this->generateBatchId();
        return view('master_penerimaan.index', compact('data', 'nextBatchId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Generate next ID Penerimaan with format P0000
     */

    private function generateBatchId()
    {
        $last = DB::table('master_penerimaan')->orderBy('id_batch_mp', 'desc')->first();
        if (!$last || !$last->id_batch_mp) {
            return 'P0001';
        }
        $lastNumber = (int) substr($last->id_batch_mp, 1);
        $nextNumber = $lastNumber + 1;
        return 'P' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'cdate' => 'required|date',
            'id_batch_mp' => 'required|string|max:50', // tambahkan validasi
        ]);

        $idPen = $this->generateBatchId();
        $cdate = strtotime($request->cdate);

        DB::table('master_penerimaan')->insert([
            'id_batch_mp' => $request->id_batch_mp, // simpan batch
            'keterangan' => $request->keterangan,
            'cdate' => $cdate,
        ]);

        return redirect()->route('master_penerimaan.index')
            ->with('success', 'Master Penerimaan created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $data = DB::table('master_penerimaan')->where('id_penerimaan', $id)->first();
        
        if (!$data) {
            return redirect()->route('master_penerimaan.index')
                ->with('error', 'Master Penerimaan not found.');
        }

        return view('master_penerimaan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'id_batch_mp' => 'required|string|max:50', // tambahkan validasi
        ]);

        DB::table('master_penerimaan')
            ->where('id_penerimaan', $id)
            ->update([
                'keterangan' => $request->keterangan,
                'id_batch_mp' => $request->id_batch_mp, // update batch
            ]);

        return redirect()->route('master_penerimaan.index')
            ->with('success', 'Master Penerimaan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::table('master_penerimaan')->where('id_penerimaan', $id)->delete();

        return redirect()->route('master_penerimaan.index')
            ->with('success', 'Master Penerimaan deleted successfully.');
    }
}