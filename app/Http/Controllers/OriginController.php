<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OriginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('origin')->get();
        return view('origin.index', compact('data'));
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
            'deskripsi' => 'required',
        ]);

        DB::table('origin')->insert([
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('master_origin.index')->with('success', 'Origin added successfully');
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
        $data = DB::table('origin')->where('id_origin', $id)->first();
        return view('origin.edit', compact('data'));
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
            'deskripsi' => 'required',
        ]);

        DB::table('origin')->where('id_origin', $id)->update([
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('master_origin.index')->with('success', 'Origin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('origin')->where('id_origin', $id)->delete();
        return redirect()->route('master_origin.index')->with('success', 'Origin deleted successfully');
    }
}