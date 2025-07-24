<?php

namespace App\Exports;

use App\Models\MasterGuru;
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

class GuruExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return MasterGuru::all();
    }

    public function headings(): array
    {
        return [
            'ID Guru',
            'Nama Guru',
            'Alamat',
            'Jenis Kelamin',
            'NIP',
            'NIK',
            'Email',
            'Foto',
            'Dibuat Pada',
            'Diperbarui Pada'
        ];
    }

    public function map($guru): array
    {
        return [
            $guru->id_guru,
            $guru->nama_guru,
            $guru->alamat_guru,
            $guru->jenis_kelamin,
            $guru->nip,
            $guru->nik,
            $guru->email ?? 'Belum ada Email',
            basename($guru->foto), // Menampilkan nama file foto
            $guru->created_at->format('d/m/Y H:i'),
            $guru->updated_at->format('d/m/Y H:i'),
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
                $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Wrap text untuk alamat
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setWrapText(true);

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(30);
                foreach ($sheet->getRowIterator(2) as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight(50);
                }
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Nama
            'C' => 45,  // Alamat
            'D' => 15,  // Jenis Kelamin
            'E' => 15,  // NIP
            'F' => 20,  // NIK
            'G' => 25,  // Email
            'H' => 25,  // Foto
            'I' => 20,  // Created At
            'J' => 20,  // Updated At
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->collection() as $guru) {
            if ($guru->foto && file_exists(public_path('uploads/guru/' . $guru->foto))) {
                $drawing = new Drawing();
                $drawing->setName('Foto');
                $drawing->setDescription('Foto Guru');
                $drawing->setPath(public_path('uploads/guru/' . $guru->foto));
                $drawing->setHeight(45);
                $drawing->setWidth(45);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setCoordinates('H' . $row);
                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}
