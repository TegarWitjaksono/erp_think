<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DetailNilaiAllExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    protected $id_kelas;
    protected $id_quiz;
    protected $id_materi;
    protected $data;

    public function __construct($id_kelas, $id_quiz = 0, $id_materi = 0)
    {
        $this->id_kelas = base64_decode($id_kelas);
        $this->id_quiz = $id_quiz != 0 ? base64_decode($id_quiz) : null;
        $this->id_materi = $id_materi != 0 ? base64_decode($id_materi) : null;

        $this->loadData();
    }

    protected function loadData()
    {
        $query = DB::table('detail_nilai')
            ->join('trans_nilai', 'trans_nilai.id_trans', '=', 'detail_nilai.id_trans_nilai')
            ->join('master_soal', 'master_soal.id_soal', '=', 'detail_nilai.id_soal')
            ->join('master_siswa', 'trans_nilai.id_siswa', '=', 'master_siswa.id_siswa')
            ->join('master_kelas', 'trans_nilai.id_kelas', '=', 'master_kelas.id_kelas')
            ->leftJoin('master_materi', 'trans_nilai.id_materi', '=', 'master_materi.id_materi')
            ->leftJoin('master_quiz', 'trans_nilai.id_quiz', '=', 'master_quiz.id_quiz')
            ->select(
                'master_siswa.nama_siswa',
                'master_kelas.nama_kelas',
                'master_materi.judul as materi',
                'master_quiz.nama_quiz as quiz',
                'detail_nilai.id_trans_nilai',
                'detail_nilai.id_soal',
                'detail_nilai.pilihan',
                'master_soal.jawaban',
                'master_soal.soal',
                'master_soal.pilihan_1',
                'master_soal.pilihan_2',
                'master_soal.pilihan_3',
                'master_soal.pilihan_4',
                DB::raw("CASE 
                    WHEN master_materi.id_materi IS NOT NULL THEN 'Materi'
                    WHEN master_quiz.id_quiz IS NOT NULL THEN 'Quiz'
                    ELSE 'Tidak Diketahui'
                END AS tipe")
            )
            ->where('trans_nilai.id_kelas', $this->id_kelas);

        if ($this->id_quiz) {
            $query->where('trans_nilai.id_quiz', $this->id_quiz);
        }

        if ($this->id_materi) {
            $query->where('trans_nilai.id_materi', $this->id_materi);
        }

        $this->data = $query->orderBy('master_siswa.nama_siswa')->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            "NO",
            "Nama Siswa",
            "Kelas",
            "Tipe",
            "Materi/Quiz",
            "Soal",
            "Pilihan Siswa",
            "Jawaban Benar",
            "Status"
        ];
    }

    public function map($item): array
    {
        static $number = 1;

        $jawabanSiswa = $item->{'pilihan_' . $item->pilihan} ?? $item->pilihan;
        $jawabanBenar = $item->{'pilihan_' . $item->jawaban} ?? $item->jawaban;
        $status = $item->pilihan == $item->jawaban ? 'Benar' : 'Salah';
        $materiQuiz = $item->tipe === 'Materi' ? $item->materi : $item->quiz;

        return [
            $number++,
            $item->nama_siswa,
            $item->nama_kelas,
            $item->tipe,
            $materiQuiz,
            $item->soal,
            $jawabanSiswa,
            $jawabanBenar,
            $status,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // Set fixed row height for all rows
                $sheet->getDefaultRowDimension()->setRowHeight(40);

                // Styling Header
                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '219ebc']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                ]);

                // Center all content and set word wrap
                $sheet->getStyle('A2:I' . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Border untuk semua data
                $sheet->getStyle('A1:I' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Format kolom status (Benar -> Biru, Salah -> Merah)
                for ($row = 2; $row <= $lastRow; $row++) {
                    $statusCell = 'I' . $row;
                    $statusValue = $sheet->getCell($statusCell)->getValue();

                    $color = ($statusValue == 'Benar') ? '219ebc' : 'FF0000';

                    $sheet->getStyle($statusCell)->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                        'font' => ['color' => ['rgb' => 'FFFFFF']],
                    ]);
                }

                // Auto-size columns for better fit
                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(false);
                }
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,  // NO
            'B' => 25, // Nama Siswa
            'C' => 15, // Kelas
            'D' => 10, // Tipe
            'E' => 25, // Materi/Quiz
            'F' => 40, // Soal
            'G' => 25, // Pilihan Siswa
            'H' => 25, // Jawaban Benar
            'I' => 15, // Status
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2; // Mulai dari baris kedua setelah header

        foreach ($this->data as $item) {
            // Jika soal adalah gambar
            if ($this->isImage($item->soal)) {
                $drawings[] = $this->createDrawing('F' . $row, $item->soal);
            }

            // Jika jawaban benar adalah gambar
            $jawabanBenar = $item->{'pilihan_' . $item->jawaban} ?? $item->jawaban;
            if ($this->isImage($jawabanBenar)) {
                $drawings[] = $this->createDrawing('H' . $row, $jawabanBenar);
            }

            // Jika jawaban siswa adalah gambar
            $jawabanSiswa = $item->{'pilihan_' . $item->pilihan} ?? $item->pilihan;
            if ($this->isImage($jawabanSiswa)) {
                $drawings[] = $this->createDrawing('G' . $row, $jawabanSiswa);
            }

            $row++;
        }

        return $drawings;
    }

    private function isImage($path)
    {
        if (!is_string($path)) return false;
        return strpos($path, 'uploads/') !== false && file_exists(public_path($path));
    }

    private function createDrawing($cell, $imagePath)
    {
        $drawing = new Drawing();
        $drawing->setName('Gambar');
        $drawing->setDescription('Gambar Soal atau Jawaban');
        $drawing->setPath(public_path($imagePath));
        $drawing->setCoordinates($cell);

        // Set fixed height and maintain aspect ratio
        $maxHeight = 35; // Slightly less than row height to fit within cell
        $imageSize = getimagesize(public_path($imagePath));
        $width = $imageSize[0] ?? 100;
        $height = $imageSize[1] ?? 100;

        // Calculate dimensions to fit within cell
        $ratio = $maxHeight / $height;
        $drawing->setHeight($maxHeight);
        $drawing->setWidth($width * $ratio);

        // Center the image in the cell
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(2);

        return $drawing;
    }
}
