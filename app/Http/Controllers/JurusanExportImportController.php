<?php

namespace App\Http\Controllers;

use App\Exports\JurusanExport;
use App\Imports\JurusanImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JurusanExportImportController extends Controller
{
    public function export()
    {
        return Excel::download(new JurusanExport, 'jurusan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new JurusanImport, $request->file('file'));

        return redirect()->route('master_jurusan.index')->with('success', 'Data Jurusan berhasil diimpor.');
    }
}