<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    /**
     * Get grades with product count.
     *
     * @param  int  $limit
     * @return array
     */
    public function getGradesWithCount($limit = 4)
    {
        try {
            $grades = DB::table('grade')
                ->leftJoin('finished_products', 'grade.id_grade', '=', 'finished_products.id_grade')
                ->select(
                    'grade.id_grade',
                    'grade.nama_grade',
                    DB::raw('COUNT(finished_products.id) as product_count')
                )
                ->groupBy('grade.id_grade', 'grade.nama_grade')
                ->orderBy('product_count', 'desc')
                ->limit($limit)
                ->get();

            $maxCount = $grades->max('product_count');

            return [
                'grades' => $grades,
                'maxCount' => $maxCount ?: 1 // Prevent division by zero
            ];
        } catch (\Exception $e) {
            \Log::error('Error in getGradesWithCount: ' . $e->getMessage());
            return [
                'grades' => collect([]),
                'maxCount' => 1
            ];
        }
    }
}