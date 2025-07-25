<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMachinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('machines')->get();
        return view('machines.index', compact('data'));
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
            'merk' => 'required',
            'location' => 'required',
            'serial_number' => 'required',
            'status' => 'required|in:active,inactive',
            'type' => 'required',
            'kapasitas' => 'required',
            'plc_support' => 'required|in:1,0'
        ]);


        DB::table('machines')
            ->insert($validated);
        return redirect()->route('machines.index')
            ->with('success', 'Machines created successfully');
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
        $machine = DB::table('machines')
            ->where('id', base64_decode($id))
            ->first();
        return view('machines.edit', compact('machine'));
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
            'merk' => 'required',
            'location' => 'required',
            'serial_number' => 'required',
            'status' => 'required|in:active,inactive',
            'type' => 'required',
            'kapasitas' => 'required',
            'plc_support' => 'required|in:1,0'
        ]);


        DB::table('machines')
            ->where('id', $id)
            ->update($validated);
        return redirect()->route('machines.index')
            ->with('success', 'Machines Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('machines')
            ->where('id', $id)
            ->delete();
        return redirect()->route('machines.index')
            ->with('success', 'Machines Delete successfully');
    }
}
