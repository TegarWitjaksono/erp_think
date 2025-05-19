<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterQuiz;
use Illuminate\Http\Request;
use App\Exports\QuizExport;
use App\Exports\TemplateQuizExport;
use App\Imports\QuizImport;
use Maatwebsite\Excel\Facades\Excel;


class MasterQuizController extends Controller
{
    public function index()
    {
        $quizs = MasterQuiz::all();
        return view('master_quiz.index', compact('quizs'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_quiz' => 'required|string',
            'desc' => 'required|string',
            'typ' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'durasi' => 'required|integer',
        ]);

        $data = $request->all();
        $data['sts'] = 1;

        if ($request->hasFile('icon')) {
            $fileName = time() . '.' . $request->icon->extension();
            $request->icon->move(public_path('uploads/quiz'), $fileName);
            $data['icon'] = $fileName;
        }

        MasterQuiz::create($data);

        return redirect()->route('master_quiz.index')->with('success', 'Data quiz baru berhasil ditambahkan.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $quiz = MasterQuiz::findOrFail($id);
        return view('master_quiz.edit', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_quiz' => 'required|string',
            'desc' => 'required|string',
            'typ' => 'required',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'durasi' => 'required|integer',
            'sts' => 'required'
        ]);

        $quiz = MasterQuiz::findOrFail($id);

        $quiz->nama_quiz = $request->nama_quiz;
        $quiz->desc = $request->desc;
        $quiz->typ = $request->typ;
        $quiz->sts = $request->sts;
        $quiz->durasi = $request->durasi;

        if ($request->hasFile('icon')) {
            if ($quiz->icon && file_exists(public_path('uploads/quiz/' . $quiz->icon))) {
                unlink(public_path('uploads/quiz/' . $quiz->icon));
            }

            $fileName = time() . '.' . $request->icon->extension();
            $request->icon->move(public_path('uploads/quiz'), $fileName);
            $quiz->icon = $fileName;
        }

        $quiz->save();

        return redirect()->route('master_quiz.index')->with('success', 'Data quiz berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $quiz = MasterQuiz::findOrFail($id);

        if ($quiz->icon && file_exists(public_path('uploads/quiz/' . $quiz->icon))) {
            unlink(public_path('uploads/quiz/' . $quiz->icon));
        }

        $quiz->delete();

        return redirect()->route('master_quiz.index')->with('success', 'Data quiz berhasil dihapus.');
    }

    public function export()
    {
        try {
            return Excel::download(new QuizExport, 'MasterQuiz_' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return redirect()->route('master_quiz.index')
                ->with('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new QuizImport, $request->file('file'));
            return redirect()->route('master_quiz.index')
                ->with('success', 'Data quiz berhasil diimpor');
        } catch (\Exception $e) {
            return redirect()->route('master_quiz.index')
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
    public function exportTemplate()
    {
        return Excel::download(new TemplateQuizExport, 'template_quiz.xlsx');
    }
}
