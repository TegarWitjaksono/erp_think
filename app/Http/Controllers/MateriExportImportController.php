<?php

namespace App\Http\Controllers;

use App\Exports\MateriExport;
use App\Exports\TemplateMateriExport;
use App\Imports\MateriImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MateriExportImportController extends Controller
{
    public function export()
    {
        return Excel::download(new MateriExport, 'materi.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new MateriImport, $request->file('file'));

        return redirect()->route('master_materi.index')->with('success', 'Data Materi berhasil diimpor.');
    }

    public function exportTemplate()
    {
        return Excel::download(new TemplateMateriExport, 'materi_template.xlsx');
    }
}
