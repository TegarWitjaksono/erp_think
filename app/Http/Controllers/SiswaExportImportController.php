<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Exports\TemplateSiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaExportImportController extends Controller
{
    public function export()
    {
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->route('master_siswa.index')->with('success', 'Data Siswa berhasil diimpor.');
    }

    public function exportTemplate()
    {
        return Excel::download(new TemplateSiswaExport, 'siswa_template.xlsx');
    }
}
