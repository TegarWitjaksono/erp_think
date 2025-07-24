<?php

namespace App\Exports;

use App\Models\MasterSiswa;
use App\Models\MasterJurusan;
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

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return MasterSiswa::with('jurusan', 'kelas')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Alamat',
            'Jenis Kelamin',
            'NIP',
            'NIK',
            'Foto',
            'Jurusan',
            'Email',
            'kelas',
            'Dibuat Pada',
            'Diperbarui Pada'
        ];
    }

    public function map($siswa): array
    {
        return [
            $siswa->nama_siswa,
            $siswa->alamat_siswa,
            $siswa->jenis_kelamin,
            $siswa->nip,
            $siswa->nik,
            basename($siswa->foto),
            $siswa->jurusan ? $siswa->jurusan->nama_jurusan : '-',
            $siswa->email ?? 'Belum ada email',
            $siswa->kelas->nama_kelas ?? 'Tidak ada kelas',
            $siswa->created_at->format('d/m/Y H:i'),
            $siswa->updated_at->format('d/m/Y H:i'),
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
                $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Wrap text untuk alamat
                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setWrapText(true);

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
            'A' => 25,  // Nama
            'B' => 45,  // Alamat
            'C' => 15,  // Jenis Kelamin
            'D' => 15,  // NIP
            'E' => 20,  // NIK
            'F' => 25,  // Foto
            'G' => 20,  // Jurusan
            'H' => 25,  // Email
            'I' => 20,  // Created At
            'J' => 20,  // Updated At
            'K' => 20
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;

        foreach ($this->collection() as $siswa) {
            if ($siswa->foto && file_exists(public_path('uploads/siswa/' . $siswa->foto))) {
                $drawing = new Drawing();
                $drawing->setName('Foto');
                $drawing->setDescription('Foto Siswa');
                $drawing->setPath(public_path('uploads/siswa/' . $siswa->foto));
                $drawing->setHeight(45);
                $drawing->setWidth(45);
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
