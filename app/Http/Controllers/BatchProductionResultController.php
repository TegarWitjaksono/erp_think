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
            ->join('level_roast', 'batchproductionresult.level_roast_id', '=', 'level_roast.id')
            ->select(
                'batchproductionresult.*',
                'batchproduction.estimate_expire_date as batch_date',
                'level_roast.nama as level_nama'
            )
            ->paginate(15);

        return view('batch-results.index', compact('items'));
    }

    public function create()
    {
        $batches = DB::table('batchproduction')->pluck('id', 'id');
        $levels  = DB::table('level_roast')->pluck('name', 'id');

        return view('batch-results.create', compact('batches','levels'));
    }

    public function store(Request $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
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
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        DB::table('batchproductionresult')->where('id',$id)->update($data);

        return redirect()->route('batch-results.index')->with('success','Hasil batch diperbarui');
    }

    public function destroy($id)
    {
        DB::table('batchproductionresult')->where('id',$id)->update([
            'is_active'=>false,'updated_by'=>auth()->id()
        ]);

        return redirect()->route('batch-results.index')->with('success','Hasil batch dinonaktifkan');
    }
}