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
        return view('master_penerimaan.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'cdate' => 'required|date',
        ]);

        // Konversi tanggal ke timestamp
        $cdate = strtotime($request->cdate);

        DB::table('master_penerimaan')->insert([
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
     * @return \Illuminate\Http\Response
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
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
        ]);

        DB::table('master_penerimaan')
            ->where('id_penerimaan', $id)
            ->update([
                'keterangan' => $request->keterangan,
            ]);

        return redirect()->route('master_penerimaan.index')
            ->with('success', 'Master Penerimaan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('master_penerimaan')->where('id_penerimaan', $id)->delete();

        return redirect()->route('master_penerimaan.index')
            ->with('success', 'Master Penerimaan deleted successfully.');
    }
}