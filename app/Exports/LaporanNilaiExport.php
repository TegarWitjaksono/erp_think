<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanNilaiExport implements FromCollection, WithHeadings, WithEvents
{
    protected $id_kelas;
    protected $id;

    public function __construct($id_kelas = 0, $id = 0)
    {
        $this->$id_kelas = $id_kelas ? base64_decode($id_kelas) : 0;
        $this->id = $id ?? 0;
    }

    public function collection()
    {
        $query = DB::table('trans_nilai')
            ->join('master_siswa', 'trans_nilai.id_siswa', '=', 'master_siswa.id_siswa')
            ->leftJoin('master_materi', 'trans_nilai.id_materi', '=', 'master_materi.id_materi')
            ->leftJoin('master_quiz', 'trans_nilai.id_quiz', '=', 'master_quiz.id_quiz')
            ->join('master_kelas', 'trans_nilai.id_kelas', '=', 'master_kelas.id_kelas')
            ->select(
                'master_siswa.nama_siswa',
                DB::raw("COALESCE(master_materi.judul, '-') AS materi"),
                DB::raw("COALESCE(master_quiz.nama_quiz, '-') AS quiz"),
                'master_kelas.nama_kelas',
                'trans_nilai.jum_soal',
                'trans_nilai.benar',
                'trans_nilai.salah',
                'trans_nilai.score'
            );

        // **Cek apakah filter ID Siswa diberikan**
        if (!empty($this->id_kelas) && $this->id_kelas != 0) {
            $query->where('trans_nilai.id_kelas', $this->id_kelas);
            \Log::info('Filter applied: id_kelas = ' . $this->id_kelas);
        }

        // **Cek apakah ID Materi atau ID Quiz diberikan**
        if (!empty($this->id) && $this->id != 0) {
            $query->where(function ($q) {
                $q->where('trans_nilai.id_materi', $this->id)
                    ->orWhere('trans_nilai.id_quiz', $this->id);
            });
            \Log::info('Filter applied: id_materi/quiz = ' . $this->id);
        }

        // Cek apakah query berhasil menyaring data
        $data = $query->get();
        \Log::info('Exported data count: ' . count($data));

        return $data;
    }


    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Materi',
            'Quiz',
            'Kelas',
            'Jml Soal',
            'Jml Benar',
            'Jml Salah',
            'Score'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // Styling Header
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4A90E2']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Border untuk semua data
                $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Alignment kolom
                foreach (['D', 'E', 'F', 'G', 'H'] as $col) {
                    $sheet->getStyle($col . '2:' . $col . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // **Conditional Formatting untuk Score**
                for ($row = 2; $row <= $lastRow; $row++) {
                    $scoreCell = 'H' . $row;
                    $scoreValue = $sheet->getCell($scoreCell)->getValue();

                    if (is_numeric($scoreValue)) {
                        if ($scoreValue >= 80) {
                            $color = '28A745'; // Hijau untuk nilai tinggi
                        } elseif ($scoreValue < 60) {
                            $color = 'DC3545'; // Merah untuk nilai rendah
                        } else {
                            continue; // Abaikan jika nilainya di antara
                        }

                        $sheet->getStyle($scoreCell)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                            'font' => ['color' => ['rgb' => 'FFFFFF']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    }
                }
            },
        ];
    }
}
