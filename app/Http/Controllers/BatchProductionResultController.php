<?php 

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\BatchProductionResultRequest;
use Illuminate\Http\Request;

class BatchProductionResultController extends Controller
{
    public function index()
    {
        $items = DB::table('batchproductionresult')
            ->join('batchproduction', 'batchproductionresult.id_bacthproduction', '=', 'batchproduction.id')
            ->join('level_roast', 'batchproductionresult.level_roasting', '=', 'level_roast.id')
            ->select(
                'batchproductionresult.*',
                'batchproduction.estimate_expire_date as batch_date',
                'level_roast.name as level_name'
            )
            ->get();
        

        return view('batch-results.index', compact('items'));
    }

    public function create()
    {
        $batches = DB::table('batchproduction')->get();
        $levels  = DB::table('level_roast')->get();

        return view('batch-results.create', compact('batches','levels'));
    }

    public function store(Request $request)
    {
       $data = $request->validate([
            'id_bacthproduction' => 'required',
            'level_roasting' => 'required',
            'berat_akhir' => 'required',
            'kadar_air' => 'required',
            'agtron' => 'required',
            'cupping_score' => 'required',
            'note_flavour' => 'required|string'
        ]);

        DB::table('batchproductionresult')->insert($data);

        return redirect()->route('batch-results.index')->with('success','Hasil batch berhasil disimpan');
    }

    public function edit($id)
    {
        $result = DB::table('batchproductionresult')->find($id);
        $batches = DB::table('batchproduction')->pluck('id','id');
        $levels  = DB::table('level_roast')->pluck('name','id');

        return view('batch-results.edit', compact('result','batches','levels'));
    }

    public function update(Request $request, $id)
    {
         $data = $request->validate([
            'id_bacthproduction' => 'required',
            'level_roasting' => 'required',
            'berat_akhir' => 'required',
            'kadar_air' => 'required',
            'agtron' => 'required',
            'cupping_score' => 'required',
            'note_flavour' => 'required|string'
        ]);

        DB::table('batchproductionresult')->where('id',$id)->update($data);

        return redirect()->route('batch-results.index')->with('success','Hasil batch diperbarui');
    }

    public function destroy($id)
    {
        DB::table('batchproductionresult')->where('id',$id)->delete();

        return redirect()->route('batch-results.index')->with('success','Hasil batch dinonaktifkan');
    }

}