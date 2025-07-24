<?php

// Controller: app/Http/Controllers/GlController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GlController extends Controller
{
    // List all GL headers
    public function index()
    {
        $headers = DB::table('gl_headers')->get();
        return response()->json($headers);
    }

    // Show specific header with lines
    public function show($id)
    {
        $header = DB::table('gl_headers')->where('id', $id)->first();
        if (!$header) {
            return response()->json(['message'=>'Not Found'], 404);
        }
        $lines = DB::table('gl_lines')->where('header_id', $id)->get();
        return response()->json(['header'=>$header, 'lines'=>$lines]);
    }

    // Create new header + lines
    public function store(Request $request)
    {
        $data = $request->validate([
            'ref_module'=>'required|string',
            'ref_id'=>'required|integer',
            'doc_no'=>'required|string',
            'doc_date'=>'required|date',
            'posting_date'=>'required|date',
            'currency'=>'required|string',
            'rate'=>'required|numeric',
            'total_debit'=>'required|numeric',
            'total_credit'=>'required|numeric',
            'status'=>'required|string',
            'notes'=>'nullable|string',
            'created_by'=>'required|integer',
            'posted_by'=>'nullable|integer',
        ]);
        $id = DB::table('gl_headers')->insertGetId($data);

        $lines = $request->input('lines', []);
        foreach ($lines as $line) {
            $line['header_id'] = $id;
            DB::table('gl_lines')->insert($line);
        }

        return response()->json(['id'=>$id], 201);
    }

    // Update header + lines
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'ref_module','ref_id','doc_no','doc_date','posting_date',
            'currency','rate','total_debit','total_credit','status','notes','created_by','posted_by'
        ]);
        DB::table('gl_headers')->where('id',$id)->update($data);
        DB::table('gl_lines')->where('header_id',$id)->delete();

        $lines = $request->input('lines', []);
        foreach ($lines as $line) {
            $line['header_id'] = $id;
            DB::table('gl_lines')->insert($line);
        }

        return response()->json(null,204);
    }

    // Delete header + cascade lines
    public function destroy($id)
    {
        DB::table('gl_headers')->where('id',$id)->delete();
        return response()->json(null,204);
    }
}