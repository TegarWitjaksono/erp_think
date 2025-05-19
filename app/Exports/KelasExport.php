<?php

namespace App\Exports;

use App\Models\MasterKelas;
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

class KelasExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return MasterKelas::with('jurusan')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kelas',
            'Jurusan',
            'Status',
            'Foto',
            'Dibuat Pada',
            'Diperbarui Pada'
        ];
    }

    public function map($kelas): array
    {
        return [
            $kelas->id_kelas,
            $kelas->nama_kelas,
            $kelas->jurusan ? $kelas->jurusan->nama_jurusan : 'N/A',
            $kelas->sts == 1 ? 'Aktif' : 'Tidak Aktif',
            basename($kelas->foto),
            $kelas->created_at->format('d/m/Y H:i'),
            $kelas->updated_at->format('d/m/Y H:i'),
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
                $sheet->getStyle('B2:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
            'A' => 5,   // ID
            'B' => 25,  // Nama Kelas
            'C' => 30,  // Jurusan
            'D' => 15,  // Status
            'E' => 15,  // Durasi
            'F' => 25,  // Foto
            'G' => 20,  // Created At
            'H' => 20,  // Updated At
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->collection() as $kelas) {
            if ($kelas->foto && file_exists(public_path('uploads/kelas/' . $kelas->foto))) {
                $drawing = new Drawing();
                $drawing->setName('Foto');
                $drawing->setDescription('Foto Kelas');
                $drawing->setPath(public_path('uploads/kelas/' . $kelas->foto));
                $drawing->setHeight(50);
                $drawing->setWidth(50);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setCoordinates('F' . $row);
                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}
