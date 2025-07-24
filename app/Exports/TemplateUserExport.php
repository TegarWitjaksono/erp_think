<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateUserExport implements FromArray, WithHeadings, WithColumnWidths, WithEvents
{
    public function array(): array
    {
        // Kosongkan data, hanya buat 15 baris kosong sebagai template
        return [];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Role',
            'ID Jurusan',
            'ID Kelas',
            'Password'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'C' => 20,
            'D' => 25,
            'E' => 25,
            'F' => 25
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastColumn = 'F';
                $lastRow = 16; // 1 heading + 15 baris kosong

                // Styling header
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

                // Border dan warna selang-seling baris kosong
                for ($i = 2; $i <= $lastRow; $i++) {
                    $fillColor = $i % 2 === 0 ? 'F8F9FA' : 'E9ECEF';

                    $sheet->getStyle("A{$i}:{$lastColumn}{$i}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'CCCCCC'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    $sheet->getRowDimension($i)->setRowHeight(24);
                }

                // Set tinggi header
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Freeze header
                $sheet->freezePane('A2');
            }
        ];
    }
}
