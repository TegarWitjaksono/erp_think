<?php

namespace App\Exports;

use App\Models\MasterKategori;
use App\Models\MasterMateri;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SoalTemplateExport implements FromArray, WithTitle, WithHeadings, WithColumnWidths, WithEvents
{
    public function title(): string
    {
        return 'Template Soal';
    }

    public function headings(): array
    {
        return [
            'Soal',
            'Materi',
            'Kategori Soal',
            'Pilihan 1',
            'Pilihan 2',
            'Pilihan 3',
            'Pilihan 4',
            'Jawaban',
            'Type',
            'Bobot',
            'Status'
        ];
    }

    public function array(): array
    {
        // Kosong, tapi bisa tambahkan 1 baris contoh jika mau
        return [
            // ['1', 'Judul Contoh', 'Deskripsi...', '1', '2', 'logo.png', 'file.pdf', 'Aktif', '01/01/2024 10:00', '01/01/2024 10:00'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,  // Soal
            'B' => 20,  // Materi
            'C' => 15,  // Kategori Soal
            'D' => 20,  // Pilihan 1
            'E' => 20,  // Pilihan 2
            'F' => 20,  // Pilihan 3
            'G' => 20,  // Pilihan 4
            'H' => 12,  // Jawaban
            'I' => 10,  // Bobot
            'J' => 15,  // Status
            'K' => 20
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastColumn = 'K';

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

                // Tambahkan beberapa baris kosong dengan border


                // Set tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}
