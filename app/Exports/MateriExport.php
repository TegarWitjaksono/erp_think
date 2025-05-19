<?php

namespace App\Exports;

use App\Models\MasterMateri;
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

class MateriExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return MasterMateri::with(['kelas', 'kategori'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Deskripsi',
            'id Kelas',
            'id Kategori',
            'Logo',
            'File Materi',
            'Status',
            'Durasi',
            'Dibuat Pada',
            'Diperbarui Pada'
        ];
    }

    public function map($materi): array
    {
        $fileMateriDisplay = '';
        if ($materi->kategori) {
            switch ($materi->kategori->nama_kategori) {
                case 'text':
                    $fileMateriDisplay = $materi->file_materi;
                    break;
                case 'video':
                    $fileMateriDisplay = $materi->file_materi;
                    break;
                case 'foto':
                    $fileMateriDisplay = basename($materi->file_materi);
                    break;
                default:
                    $fileMateriDisplay = $materi->file_materi ? basename($materi->file_materi) : '';
            }
        }

        return [
            $materi->id_materi,
            $materi->judul,
            $materi->deskripsi,
            $materi->kelas ? $materi->kelas->nama_kelas : '-',
            $materi->kategori ? $materi->kategori->nama_kategori : '-',
            basename($materi->img),
            $fileMateriDisplay,
            $materi->sts == 1 ? 'Aktif' : 'Tidak Aktif',
            $materi->durasi,
            $materi->created_at->format('d/m/Y H:i'),
            $materi->updated_at->format('d/m/Y H:i'),
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
                $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Wrap text untuk deskripsi
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setWrapText(true);

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(30);
                foreach ($sheet->getRowIterator(2) as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight(50);
                }
            },
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $materi = MasterMateri::all();
        $row = 2;

        foreach ($materi as $data) {
            // Handle Logo
            if ($data->img && file_exists(public_path('uploads/logo/' . $data->img))) {
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo Materi');
                $drawing->setPath(public_path('uploads/logo/' . $data->img));
                $drawing->setHeight(50);
                $drawing->setCoordinates('F' . $row);
                $drawings[] = $drawing;
            }

            // Handle File Materi (hanya untuk kategori foto)
            if (
                $data->kategori &&
                $data->kategori->nama_kategori == 'foto' &&
                $data->file_materi &&
                file_exists(public_path('uploads/materi/' . $data->file_materi)) &&
                in_array(strtolower(pathinfo($data->file_materi, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])
            ) {

                $drawingMateri = new Drawing();
                $drawingMateri->setName('File Materi');
                $drawingMateri->setDescription('File Materi');
                $drawingMateri->setPath(public_path('uploads/materi/' . $data->file_materi));
                $drawingMateri->setHeight(50);
                $drawingMateri->setCoordinates('G' . $row);
                $drawings[] = $drawingMateri;
            }

            $row++;
        }

        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 20,  // Judul
            'C' => 30,  // Deskripsi
            'D' => 10,  // ID Kelas
            'E' => 10,  // ID Kategori
            'F' => 20,  // Logo
            'G' => 30,  // File Materi
            'H' => 15,  // Status
            'I' => 20,  // Created At
            'J' => 20,  // Updated At
            'I' => 20,  // Updated At
        ];
    }
}
