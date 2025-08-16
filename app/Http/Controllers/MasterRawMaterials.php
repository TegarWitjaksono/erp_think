<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MasterRawMaterials extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $origin = DB::table('origin')->get();
        $varietas = DB::table('varietas')->get();
        $jenis = DB::table('jenis')->get();
        $proses = DB::table('m_proses')->get();

        $data = DB::table('master_rm')
                ->join('origin','origin.id_origin','=','master_rm.id_origin')
                ->join('jenis','jenis.id_jenis','=','master_rm.id_jenis')
                ->join('varietas','varietas.id_varietas','=','master_rm.id_varietas')
               ->join('m_proses','m_proses.id','=','master_rm.id_proses')
               ->select('master_rm.*','origin.deskripsi as origin_desc',
               'varietas.deskripsi as varietas_desc',
               'jenis.deskripsi as jenis_desc',
               'm_proses.nama_proses as nama_proses'
               )
               ->get();
        return view('master_rm.index',compact('origin','varietas','jenis','proses','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|string',
            'id_origin' => 'required',
             'id_jenis' => 'required',
              'id_varietas' => 'required',
               'id_proses' => 'required'
        ]);

        DB::table('master_rm')->insert($validate);
        return redirect()->back()->with(
            'success',
            'Berhasil Menambah Raw Material Baru'
        );
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
         $origin = DB::table('origin')->get();
        $varietas = DB::table('varietas')->get();
        $jenis = DB::table('jenis')->get();
        $proses = DB::table('m_proses')->get();
        $idBase = base64_decode($id);
        $data = DB::table('master_rm')->find($idBase);
         return view('master_rm.edit',compact('origin','varietas','jenis','proses','data'));
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
         $validate = $request->validate([
            'nama' => 'required|string',
            'id_origin' => 'required',
             'id_jenis' => 'required',
              'id_varietas' => 'required',
               'id_proses' => 'required'
        ]);

        DB::table('master_rm')->where('id',$id)->update($validate);
        return redirect()->route('master_raw.index')->with(
            'success',
            'Berhasil Mengubah Raw Material'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('master_rm')->where('id',$id)->delete();
         return redirect()->back()->with(
            'success',
            'Berhasil Menghapus Raw Material'
        );
    }
}
