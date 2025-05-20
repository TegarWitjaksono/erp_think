<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $data = DB::table('grade')->get();
        return view('grade.index', compact('data'));
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
            'deskripsi' => 'required',
        ]);

        DB::table('grade')->insert([
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('master_grade.index')->with('success', 'Grade added successfully');
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
        $data = DB::table('grade')->where('id_grade', $id)->first();
        return view('grade.edit', compact('data'));
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
            'deskripsi' => 'required',
        ]);

        DB::table('grade')->where('id_grade', $id)->update([
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('master_grade.index')->with('success', 'Grade updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::table('grade')->where('id_grade', $id)->delete();
        return redirect()->route('master_grade.index')->with('success', 'Grade deleted successfully');
    }
}