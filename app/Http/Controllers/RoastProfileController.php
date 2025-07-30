<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RoastProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = DB::table('roast_profile')->get();
       
        return view('roastprofile.index',compact('items'));
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
        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'charge_temp' => 'required',
            'tp_temp' => 'required',
            'tp_time_sec' => 'required',
            'de_temp' => 'required',
            'de_time_sec' => 'required',
            'fcs_temp' => 'required',
            'fcs_time_sec' => 'required',
            'drop_time_sec' => 'required'
        ]);

        DB::table('roast_profile')->insert($validated);

        return redirect()->back()->with('success','Menambahkan Roast Profile !');
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
        $idEn = base64_decode($id);
        $data = DB::table('roast_profile')->where('id',$idEn)->first();
        

        return view('roastprofile.edit',compact('data'));
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
        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'charge_temp' => 'required',
            'tp_temp' => 'required',
            'tp_time_sec' => 'required',
            'de_temp' => 'required',
            'de_time_sec' => 'required',
            'fcs_temp' => 'required',
            'fcs_time_sec' => 'required',
            'drop_time_sec' => 'required'
        ]);
        
        DB::table('roast_profile')->where('id',$id)->update($validated);

        return redirect()->route('roast_profile.index')->with('success','Mengubah Roast Profile !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('roast_profile')->where('id',$id)->delete();
        return redirect()->back()->with('success','Menghapus Roast Profile');
    }
}
