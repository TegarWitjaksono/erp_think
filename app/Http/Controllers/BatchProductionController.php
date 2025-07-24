<?php 

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\BatchProductionRequest;
use Illuminate\Http\Request;

class BatchProductionController extends Controller
{
    public function index()
    {
        // $items = DB::table('batch_production')
        //     ->join('machines', 'batch_production.mesin_id', '=', 'machines.id')
        //     ->join('methods', 'batch_production.method_id', '=', 'methods.id')
        //     ->selectRaw('batch_production.*, machines.nama as mesin_nama, methods.deskripsi as method_deskripsi')
        //     ->paginate(15);

        // return view('batch-productions.index', compact('items'));

        return view('batch-productions.index');    }

    public function create()
    {
        $machines      = DB::table('machines')->pluck('merk','id');
      $methods       = DB::table('method')->pluck('deskripsi','id');
        $profiles      = DB::table('roast_profile')->pluck('deskripsi','id');
        $levels        = DB::table('level_roast')->pluck('name','id');
        $statuses      = ['open'=>'Open','on process'=>'On Process','closing'=>'Closing','cancel'=>'Cancel'];
        $attentions    = ['normal'=>'Normal','priority'=>'Priority'];

        return view('batch-productions.create', compact(
            'machines','methods','profiles','levels','statuses','attentions'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        DB::table('batch_production')->insert($data);

        return redirect()->route('batch-productions.index')->with('success','Batch berhasil dibuat');
    }

    public function edit($id)
    {
        $batch = DB::table('batch_production')->find($id);
        $machines      = DB::table('machines')->pluck('merk','id');
        $methods       = DB::table('method')->pluck('deskripsi','id');
        $profiles      = DB::table('roast_profile')->pluck('deskripsi','id');
        $levels        = DB::table('level_roast')->pluck('name','id');
        $statuses   = ['open'=>'Open','on process'=>'On Process','closing'=>'Closing','cancel'=>'Cancel'];
        $attentions = ['normal'=>'Normal','priority'=>'Priority'];

        return view('batch-productions.edit', compact(
            'batch','machines','methods','profiles','levels','statuses','attentions'
        ));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        DB::table('batch_production')->where('id',$id)->update($data);

        return redirect()->route('batch-productions.index')->with('success','Batch berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::table('batch_production')->where('id',$id)->update(['is_active'=>false,'updated_by'=>auth()->id()]);
        return redirect()->route('batch-productions.index')->with('success','Batch dinonaktifkan');
    }
}
