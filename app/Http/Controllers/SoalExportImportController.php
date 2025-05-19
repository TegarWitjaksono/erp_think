<?php

namespace App\Http\Controllers;

use App\Exports\SoalExport;
use App\Imports\SoalImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SoalTemplateExport;
use Illuminate\Support\Facades\Log;
use App\Models\MasterSoal;

class SoalExportImportController extends Controller
{
    public function exportTemplate()
    {
        return Excel::download(new SoalTemplateExport, 'template_soal.xlsx');
    }

    public function export()
    {
        return Excel::download(new SoalExport, 'soal.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Log::info('Mulai import soal');
            Excel::import(new SoalImport, $request->file('file'));
            Log::info('Import soal selesai');
            return redirect()->back()->with('success', 'Data Soal berhasil diimpor.');
        } catch (\Exception $e) {
            Log::error('Error saat import soal: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'id_materi' => 'required',
            'id_kategori_soal' => 'required',
            'id_kategori_jawaban' => 'required',
            'soal' => 'required',
            'pilihan_1' => 'required',
            'pilihan_2' => 'required',
            'pilihan_3' => 'required',
            'pilihan_4' => 'required',
            'jawaban' => 'required',
            'sts' => 'required',
            'bobot' => 'required',
        ]);

        // Handle soal upload
        if ($request->hasFile('soal')) {
            $soalFile = $request->file('soal');
            $soalFileName = time() . '_soal_' . uniqid() . '.' . $soalFile->getClientOriginalExtension();
            $soalPath = 'uploads/soal/';
            
            // Make sure the directory exists
            if (!file_exists(public_path($soalPath))) {
                mkdir(public_path($soalPath), 0777, true);
            }
            
            $soalFile->move(public_path($soalPath), $soalFileName);
            $validated['soal'] = $soalPath . $soalFileName;
        }

        // Handle pilihan uploads
        $pilihanFields = ['pilihan_1', 'pilihan_2', 'pilihan_3', 'pilihan_4'];
        foreach ($pilihanFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'uploads/jawaban/';
                
                // Make sure the directory exists
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }
                
                $file->move(public_path($path), $fileName);
                $validated[$field] = $path . $fileName;
            }
        }

        // Create the record
        MasterSoal::create($validated);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan');
    }
}