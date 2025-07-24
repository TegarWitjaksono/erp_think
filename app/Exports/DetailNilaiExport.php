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

class DetailNilaiExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    protected $id;
    protected $data;

    public function __construct($id)
    {
        $this->id = base64_decode($id);
        $this->data = DB::table('detail_nilai')
            ->join('trans_nilai', 'trans_nilai.id_trans', '=', 'detail_nilai.id_trans_nilai')
            ->join('master_soal', 'master_soal.id_soal', '=', 'detail_nilai.id_soal')
            ->select(
                'detail_nilai.id_trans_nilai',
                'detail_nilai.id_soal',
                'detail_nilai.pilihan',
                'master_soal.jawaban',
                'master_soal.soal',
                'master_soal.pilihan_1',
                'master_soal.pilihan_2',
                'master_soal.pilihan_3',
                'master_soal.pilihan_4'
            )
            ->where('detail_nilai.id_trans_nilai', $this->id)
            ->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ["NO", "Soal", "Pilihan Siswa", "Jawaban Benar", "Status"];
    }

    public function map($item): array
    {
        static $number = 1; // Inisialisasi nomor urut

        $jawabanSiswa = $item->{'pilihan_' . $item->pilihan};
        $jawabanBenar = $item->{'pilihan_' . $item->jawaban};
        $status = $item->pilihan == $item->jawaban ? 'Benar' : 'Salah';

        return [
            $number++, // Nomor urut
            $item->soal, // Bisa berupa teks atau path gambar
            $jawabanSiswa, // Bisa berupa teks atau path gambar
            $jawabanBenar, // Bisa berupa teks atau path gambar
            $status,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // Styling Header
                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '219ebc']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Border untuk semua data
                $sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Format kolom status (Benar -> Biru, Salah -> Merah)
                for ($row = 2; $row <= $lastRow; $row++) {
                    $statusCell = 'F' . $row;
                    $statusValue = $sheet->getCell($statusCell)->getValue();

                    $color = ($statusValue == 'Benar') ? '219ebc' : 'FF0000';

                    $sheet->getStyle($statusCell)->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                        'font' => ['color' => ['rgb' => 'FFFFFF']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // ID Transaksi
            'B' => 15, // ID Soal
            'C' => 40, // Soal (Ukuran otomatis jika ada gambar)
            'D' => 25, // Pilihan Siswa (Ukuran otomatis jika ada gambar)
            'E' => 25, // Jawaban Benar (Ukuran otomatis jika ada gambar)
            'F' => 15, // Status
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2; // Mulai dari baris kedua setelah header

        foreach ($this->data as $item) {
            // Jika soal adalah gambar
            if ($this->isImage($item->soal)) {
                $drawings[] = $this->createDrawing('C' . $row, $item->soal);
            }

            // Jika jawaban benar adalah gambar
            $jawabanBenar = $item->{'pilihan_' . $item->jawaban};
            if ($this->isImage($jawabanBenar)) {
                $drawings[] = $this->createDrawing('E' . $row, $jawabanBenar);
            }

            // Jika jawaban siswa adalah gambar
            $jawabanSiswa = $item->{'pilihan_' . $item->pilihan};
            if ($this->isImage($jawabanSiswa)) {
                $drawings[] = $this->createDrawing('D' . $row, $jawabanSiswa);
            }

            $row++;
        }

        return $drawings;
    }

    private function isImage($path)
    {
        return strpos($path, 'uploads/') !== false && file_exists(public_path($path));
    }

    private function createDrawing($cell, $imagePath)
    {
        $drawing = new Drawing();
        $drawing->setName('Gambar');
        $drawing->setDescription('Gambar Soal atau Jawaban');
        $drawing->setPath(public_path($imagePath));
        $drawing->setHeight(80);
        $drawing->setCoordinates($cell);
        return $drawing;
    }
}
