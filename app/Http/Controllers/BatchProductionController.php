<?php 

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\BatchProductionRequest;
use Illuminate\Http\Request;

class BatchProductionController extends Controller
{
    public function index()
    {
        $items = DB::table('batchproduction')
            ->join('machines', 'batchproduction.id_mesin', '=', 'machines.id')
            ->join('method', 'batchproduction.id_method', '=', 'method.id')
            ->selectRaw('batchproduction.*, machines.nama as mesin_nama, method.deskripsi as method_deskripsi')
            ->paginate(15);

        return view('batch-productions.index', compact('items'));

        //return view('batch-productions.index');  
      }

    public function create()
    {
        $machines      = DB::table('machines')->pluck('merk','id');
         $method       = DB::table('method')->pluck('deskripsi','id');
        $profiles      = DB::table('roast_profile')->pluck('deskripsi','id');
        $levels        = DB::table('level_roast')->pluck('name','id');
        $statuses      = ['open'=>'Open','on process'=>'On Process','closing'=>'Closing','cancel'=>'Cancel'];
        $attentions    = ['normal'=>'Normal','priority'=>'Priority'];

        return view('batch-productions.create', compact(
            'machines','method','profiles','levels','statuses','attentions'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        DB::table('batchproduction')->insert($data);

        return redirect()->route('batch-productions.index')->with('success','Batch berhasil dibuat');
    }

    public function edit($id)
    {
        $batch = DB::table('batchproduction')->find($id);
        $machines      = DB::table('machines')->pluck('merk','id');
        $method       = DB::table('method')->pluck('deskripsi','id');
        $profiles      = DB::table('roast_profile')->pluck('deskripsi','id');
        $levels        = DB::table('level_roast')->pluck('name','id');
        $statuses   = ['open'=>'Open','on process'=>'On Process','closing'=>'Closing','cancel'=>'Cancel'];
        $attentions = ['normal'=>'Normal','priority'=>'Priority'];

        return view('batch-productions.edit', compact(
            'batch','machines','method','profiles','levels','statuses','attentions'
        ));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        DB::table('batchproduction')->where('id',$id)->update($data);

        return redirect()->route('batch-productions.index')->with('success','Batch berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::table('batchproduction')->where('id',$id)->update(['is_active'=>false,'updated_by'=>auth()->id()]);
        return redirect()->route('batch-productions.index')->with('success','Batch dinonaktifkan');
    }
}
