<?php

namespace App\Exports;

use App\Models\MasterJadwal;
use App\Models\MasterGuru;
use App\Models\MasterSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JadwalExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return MasterJadwal::with(['guru', 'siswa', 'kelas'])->get();
    }

    public function headings(): array
    {
        return [
            'ID Jadwal',
            'Hari',
            'Nama Guru',
            'Nama Siswa',
            'Nama Kelas',
            'Jam Masuk',
            'Jam Keluar',
            'Nama Jadwal',
            'Status',
            'Tipe',
        ];
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->id_jadwal,
            $jadwal->hari,
            $jadwal->guru ? $jadwal->guru->nama_guru : '-',
            $jadwal->siswa ? $jadwal->siswa->nama_siswa : '-',
            $jadwal->kelas ? $jadwal->kelas->nama_kelas : 'Kelas Dihapus',
            $jadwal->jam_in,
            $jadwal->jam_out,
            $jadwal->nama_jadwal,
            $jadwal->sts == 1 ? 'Aktif' : 'Tidak Aktif',
            $jadwal->type == 1 ? 'Guru' : 'Siswa',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
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

                $sheet->getStyle('A1:'.$lastColumn.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '219ebc'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A2:A'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B2:B'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F2:G'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I2:J'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
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
            'A' => 10,  // ID
            'B' => 15,  // Hari
            'C' => 25,  // Nama Guru
            'D' => 25,  // Nama Siswa
            'E' => 20,  // Nama Kelas
            'F' => 15,  // Jam Masuk
            'G' => 15,  // Jam Keluar
            'H' => 25,  // Nama Jadwal
            'I' => 12,  // Status
            'J' => 12,  // Tipe
        ];
    }
}