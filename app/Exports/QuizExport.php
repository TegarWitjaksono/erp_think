<?php

namespace App\Exports;

use App\Models\MasterQuiz;
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

class QuizExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return MasterQuiz::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Quiz',
            'Deskripsi',
            'Tipe',
            'Icon',
            'Durasi',
            'Status',
            'Dibuat Pada',
            'Diperbarui Pada'
        ];
    }

    public function map($quiz): array
    {
        return [
            $quiz->id_quiz,
            $quiz->nama_quiz,
            $quiz->desc,
            $quiz->typ == 1 ? 'Quiz' : 'Ujian',
            basename($quiz->icon), // Menampilkan nama file icon
            $quiz->durasi,
            $quiz->sts == 1 ? 'Aktif' : 'Tidak Aktif',
            $quiz->created_at->format('d/m/Y H:i'),
            $quiz->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
                // Styling untuk header
                $sheet->getStyle('A1:'.$lastColumn.'1')->applyFromArray([
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
                $sheet->getStyle('A1:'.$lastColumn.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '219ebc'],
                        ],
                    ],
                ]);

                // Alignment untuk kolom tertentu
                $sheet->getStyle('A2:A'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D2:D'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F2:F'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Wrap text untuk deskripsi
                $sheet->getStyle('C2:C'.$lastRow)->getAlignment()->setWrapText(true);
                
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
            'B' => 25,  // Nama Quiz
            'C' => 45,  // Deskripsi
            'D' => 12,  // Tipe
            'E' => 25,  // Icon
            'F' => 12,  // Durasi
            'G' => 12,  // Status
            'H' => 20,  // Created At
            'I' => 20,  // Updated At
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->collection() as $quiz) {
            if ($quiz->icon && file_exists(public_path('uploads/quiz/' . $quiz->icon))) {
                $drawing = new Drawing();
                $drawing->setName('Icon');
                $drawing->setDescription('Quiz Icon');
                $drawing->setPath(public_path('uploads/quiz/' . $quiz->icon));
                $drawing->setHeight(45);
                $drawing->setWidth(45);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setCoordinates('E' . $row);
                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }
}