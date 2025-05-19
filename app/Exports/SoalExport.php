<?php

namespace App\Exports;

use App\Models\MasterSoal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Str;

class SoalExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    protected $soals;

    public function __construct()
    {
        $this->soals = MasterSoal::with(['materi', 'kategoriSoal', 'kategoriJawaban'])->get();
    }

    public function collection()
    {
        return $this->soals;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Soal',
            'Materi',
            'Kategori Soal',
            'Kategori Jawaban',
            'Pilihan 1',
            'Pilihan 2',
            'Pilihan 3',
            'Pilihan 4',
            'Jawaban',
            'Bobot',
            'Status',
            'Type',
            'Created At',
            'Updated At'
        ];
    }

    public function map($soal): array
    {
        // Logika untuk menampilkan soal
        $soalDisplay = '';
        if ($soal->kategoriSoal) {
            if ($soal->kategoriSoal->nama_kategori == 'foto') {
                $soalDisplay = basename($soal->soal); // Ambil nama file saja
            } else {
                $soalDisplay = $soal->soal;
            }
        }

        // Logika untuk menampilkan pilihan jawaban
        $pilihanDisplay = [];
        for ($i = 1; $i <= 4; $i++) {
            $pilihan = 'pilihan_' . $i;
            if ($soal->kategoriJawaban && $soal->kategoriJawaban->nama_kategori == 'foto') {
                $pilihanDisplay[] = basename($soal->$pilihan); // Ambil nama file saja
            } else {
                $pilihanDisplay[] = $soal->$pilihan;
            }
        }

        return [
            $soal->id_soal,
            $soalDisplay,
            $soal->materi ? $soal->materi->judul : 'N/A',
            $soal->kategoriSoal ? $soal->kategoriSoal->nama_kategori : 'N/A',
            $soal->kategoriJawaban ? $soal->kategoriJawaban->nama_kategori : 'N/A',
            $pilihanDisplay[0],
            $pilihanDisplay[1],
            $pilihanDisplay[2],
            $pilihanDisplay[3],
            $soal->jawaban,
            $soal->bobot,
            $soal->sts == 1 ? 'Aktif' : 'Tidak Aktif',
            $soal->type,
            $soal->created_at,
            $soal->updated_at,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Styling untuk header
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '219ebc'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Border untuk seluruh data
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '219ebc'],
                        ],
                    ],
                ]);

                // Alignment untuk kolom tertentu
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J2:L' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Wrap text untuk soal dan pilihan
                $sheet->getStyle('B2:I' . $lastRow)->getAlignment()->setWrapText(true);

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(30);
                foreach ($sheet->getRowIterator(2) as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight(100); // Increased height for images
                }
            },
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->soals as $soal) {
            // Handle soal foto
            if (
                $soal->kategoriSoal &&
                $soal->kategoriSoal->nama_kategori == 'foto' &&
                $soal->soal
            ) {

                $soalPath = public_path($soal->soal);
                if (file_exists($soalPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Soal Foto');
                    $drawing->setDescription('Foto Soal');
                    $drawing->setPath($soalPath);
                    $drawing->setHeight(80);  // Increased size
                    $drawing->setWidth(80);   // Increased size
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates('B' . $row);
                    $drawings[] = $drawing;
                }
            }

            // Handle pilihan foto for each answer choice
            if ($soal->kategoriJawaban && $soal->kategoriJawaban->nama_kategori == 'foto') {
                $pilihan_columns = ['F', 'G', 'H', 'I']; // Columns for answer choices

                for ($i = 1; $i <= 4; $i++) {
                    $pilihan = 'pilihan_' . $i;
                    $pilihanPath = '';

                    // Handle different path formats
                    if (Str::startsWith($soal->$pilihan, 'uploads/')) {
                        $pilihanPath = public_path($soal->$pilihan);
                    } else if (
                        preg_match('/^\d+_\d+\.(jpg|jpeg|png|gif)$/i', $soal->$pilihan) ||
                        preg_match('/^\d+_[1-4]\.(jpg|jpeg|png|gif)$/i', $soal->$pilihan)
                    ) {
                        // If it's just a filename, assume it's in uploads/jawaban directory
                        $pilihanPath = public_path('uploads/jawaban/' . $soal->$pilihan);
                    } else {
                        $pilihanPath = public_path($soal->$pilihan);
                    }

                    // Add drawing if file exists
                    if (file_exists($pilihanPath)) {
                        $drawing = new Drawing();
                        $drawing->setName('Pilihan ' . $i);
                        $drawing->setDescription('Foto Pilihan ' . $i);
                        $drawing->setPath($pilihanPath);
                        $drawing->setHeight(80);  // Increased size
                        $drawing->setWidth(80);   // Increased size
                        $drawing->setOffsetX(5);
                        $drawing->setOffsetY(5);
                        $drawing->setCoordinates($pilihan_columns[$i - 1] . $row);
                        $drawings[] = $drawing;
                    }
                }
            }

            $row++;
        }

        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 30,  // Soal
            'C' => 20,  // Materi
            'D' => 15,  // Kategori Soal
            'E' => 15,  // Kategori Jawaban
            'F' => 20,  // Pilihan 1
            'G' => 20,  // Pilihan 2
            'H' => 20,  // Pilihan 3
            'I' => 20,  // Pilihan 4
            'J' => 10,  // Jawaban
            'K' => 10,  // Bobot
            'L' => 15,  // Status
            'M' => 20,  // Created At
            'N' => 20,  // Updated At
            'O' => 20,
        ];
    }
}
