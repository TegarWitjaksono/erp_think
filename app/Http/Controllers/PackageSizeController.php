<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = DB::table('m_package_size')->get();
        return view('package_size.index', compact('data'));
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
            'deskripsi' => 'required|string|max:255',
        ]);

        DB::table('m_package_size')->insert([
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('master_package_size.index')->with('success', 'Package size berhasil ditambahkan.');
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
        $data = DB::table('m_package_size')->where('id', $decoded_id)->first();
        return view('package_size.edit', compact('data'));
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
            'deskripsi' => 'required|string|max:255',
        ]);

        DB::table('m_package_size')->where('id', $id)->update([
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('master_package_size.index')->with('success', 'Package size berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::table('m_package_size')->where('id', $id)->delete();
        return redirect()->route('master_package_size.index')->with('success', 'Package size berhasil dihapus.');
    }
}