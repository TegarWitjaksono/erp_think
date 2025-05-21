<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $data = DB::table('master_barang')->get();
        return view('barang.index', compact('data'));
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
            'nama_barang' => 'required',
        ]);

        DB::table('master_barang')->insert([
            'nama_barang' => $request->nama_barang,
        ]);

        return redirect()->route('master_barang.index')->with('success', 'Barang added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $data = DB::table('master_barang')->where('id_barang', $id)->first();
        return view('barang.edit', compact('data'));
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
            'nama_barang' => 'required',
        ]);

        DB::table('master_barang')->where('id_barang', $id)->update([
            'nama_barang' => $request->nama_barang,
        ]);

        return redirect()->route('master_barang.index')->with('success', 'Barang updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::table('master_barang')->where('id_barang', $id)->delete();
        return redirect()->route('master_barang.index')->with('success', 'Barang deleted successfully');
    }

    /**
     * Get barang with product count.
     *
     * @param  int  $limit
     * @return array
     */
    public function getBarangWithCount($limit = 4)
    {
        try {
            $barang = DB::table('master_barang')
                ->select(
                    'barang.id_barang',
                    'barang.nama_barang',
                    DB::raw('0 as product_count')
                )
                ->limit($limit)
                ->get();

            return [
                'barang' => $barang,
                'maxCount' => 1
            ];
        } catch (\Exception $e) {
            \Log::error('Error in getBarangWithCount: ' . $e->getMessage());
            return [
                'barang' => collect([]),
                'maxCount' => 1
            ];
        }
    }
}