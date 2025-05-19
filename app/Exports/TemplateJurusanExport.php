<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;


class TemplateJurusanExport implements FromArray, WithHeadings, WithEvents, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'nama_jurusan',
            'Dibuat Pada',
            'Diperbarui Pada',
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
            'A' => 20,  // ID
            'B' => 40,  // Judul
            'C' => 40,  // Deskripsi
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Header Style
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

                // Border style
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '219ebc'],
                        ],
                    ],
                ]);

                // Column alignment
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setWrapText(true);

                // Row height
                $sheet->getRowDimension(1)->setRowHeight(30);
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(50);
                }
            },

        ];
    }
}
