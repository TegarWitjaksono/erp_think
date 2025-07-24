<?php

namespace App\Http\Controllers;

use App\Exports\TemplateUserExport;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UsersExportImportController extends Controller
{
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->route('master_user.index')->with('success', 'Data Users berhasil diimpor.');
    }

    public function exportTemplate()
    {
        return Excel::download(new TemplateUserExport, 'users_template.xlsx');
    }
}
