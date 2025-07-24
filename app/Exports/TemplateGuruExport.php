<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateGuruExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return collect([
            [null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null],
        ]);
    }

    public function headings(): array
    {
        return [
            'nama_guru',
            'alamat',
            'jenis_kelamin',
            'NIP',
            'NIK',
            'Email',
            'Foto',
            'Dibuat Pada',
            'Diperbarui Pada'
        ];
    }

    public function map($row): array
    {
        return $row;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = 4; // 1 header + 3 data rows
                $lastColumn = 'I';

                // Set row heights - TAMBAHKAN HEIGHT DI SINI
                $sheet->getRowDimension(1)->setRowHeight(30); // Header lebih tinggi
                $sheet->getRowDimension(2)->setRowHeight(40); // Data row
                $sheet->getRowDimension(3)->setRowHeight(40); // Data row 
                $sheet->getRowDimension(4)->setRowHeight(40); // Data row

                // Header style
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12, // Perbesar font header
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

                // Data rows style
                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'font' => [
                        'size' => 11, // Ukuran font data
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Tambahkan padding dengan menambah width kolom
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(40);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Diperlebar
            'B' => 30,
            'C' => 40,
            'D' => 20,  // Diperlebar
            'E' => 20,
            'F' => 25,
            'G' => 30,
            'H' => 20,
            'I' => 25,
        ];
    }
}
