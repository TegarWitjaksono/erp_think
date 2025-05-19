<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Exports\TemplateKelasExport;
use App\Imports\KelasImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KelasExportImportController extends Controller
{
    public function export()
    {
        return Excel::download(new KelasExport, 'kelas.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new KelasImport, $request->file('file'));

        return redirect()->route('master_kelas.index')->with('success', 'Data Kelas berhasil diimpor.');
    }
    public function exportTemplate()
    {
        return Excel::download(new TemplateKelasExport, 'kelas_template.xlsx');
    }
}
