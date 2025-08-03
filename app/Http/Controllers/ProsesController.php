<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProsesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = DB::table('m_proses')->get();
        return view('proses.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_proses' => 'required|string|max:255',
        ]);

        DB::table('m_proses')->insert([
            'nama_proses' => $request->nama_proses,
        ]);

        return redirect()->route('master_proses.index')->with('success', 'Proses berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $decoded_id = base64_decode($id);
        $data = DB::table('m_proses')->where('id', $decoded_id)->first();
        return view('proses.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_proses' => 'required|string|max:255',
        ]);

        DB::table('m_proses')->where('id', $id)->update([
            'nama_proses' => $request->nama_proses,
        ]);

        return redirect()->route('master_proses.index')->with('success', 'Proses berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::table('m_proses')->where('id', $id)->delete();
        return redirect()->route('master_proses.index')->with('success', 'Proses berhasil dihapus.');
    }
}