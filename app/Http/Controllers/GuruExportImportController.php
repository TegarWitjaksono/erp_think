<?php

namespace App\Http\Controllers;

use App\Exports\GuruExport;
use App\Exports\TemplateGuruExport;
use App\Imports\GuruImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GuruExportImportController extends Controller
{
    public function export()
    {
        return Excel::download(new GuruExport, 'guru.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new GuruImport, $request->file('file'));

        return redirect()->route('master_guru.index')->with('success', 'Data Guru berhasil diimpor.');
    }
    public function exportTemplate()
    {
        return Excel::download(new TemplateGuruExport, 'template_guru.xlsx');
    }
}
