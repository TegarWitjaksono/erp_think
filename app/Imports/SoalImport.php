<?php

namespace App\Imports;

use App\Models\MasterKategori;
use App\Models\MasterMateri;
use App\Models\MasterSoal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class SoalImport implements ToModel, WithHeadingRow
{
    private static $rowIndex = 2; // Mulai dari 2 jika ada header

    public function model(array $row)
    {
        try {
            // Skip baris kosong
            if (empty(array_filter($row))) {
                return null;
            }

            // Gunakan nilai default untuk materi (ambil materi pertama)
            $materi = MasterMateri::first();
            $idMateri = $materi ? $materi->id_materi : 14;

            // Jika ada kolom materi di Excel, coba cari materi yang sesuai
            if (isset($row['materi']) && !empty($row['materi'])) {
                $materiByName = MasterMateri::where('judul', $row['materi'])->first();
                if ($materiByName) {
                    $idMateri = $materiByName->id_materi;
                }
            }

            // Gunakan nilai default untuk kategori soal (ambil kategori pertama)
            $kategoriSoal = MasterKategori::where('nama_kategori', 'text')->first();
            if (!$kategoriSoal) {
                $kategoriSoal = MasterKategori::first();
            }
            $idKategoriSoal = $kategoriSoal ? $kategoriSoal->id_kategori : 1;

            // Jika ada kolom kategori_soal di Excel, coba cari kategori yang sesuai
            if (isset($row['kategori_soal']) && !empty($row['kategori_soal'])) {
                $kategoriByName = MasterKategori::where('nama_kategori', $row['kategori_soal'])->first();
                if ($kategoriByName) {
                    $idKategoriSoal = $kategoriByName->id_kategori;
                }
            }

            // Cek kategori jawaban, cari berdasarkan nama atau gunakan default
            $kategoriJawaban = MasterKategori::where('nama_kategori', 'text')->first();
            if (!$kategoriJawaban) {
                $kategoriJawaban = MasterKategori::first();
            }
            $idKategoriJawaban = $kategoriJawaban ? $kategoriJawaban->id_kategori : 1;

            // Jika ada kolom kategori_jawaban di Excel, coba cari kategori yang sesuai
            if (isset($row['kategori_jawaban']) && !empty($row['kategori_jawaban'])) {
                $kategoriJawabanByName = MasterKategori::where('nama_kategori', $row['kategori_jawaban'])->first();
                if ($kategoriJawabanByName) {
                    $idKategoriJawaban = $kategoriJawabanByName->id_kategori;
                }
            }

            // Pastikan jawaban memiliki nilai default (1 jika tidak ada)
            $jawaban = isset($row['jawaban']) && !empty($row['jawaban']) ? $row['jawaban'] : 1;

            // Cek apakah ada soal yang valid
            if (!isset($row['soal']) || empty($row['soal'])) {
                return null; // Skip baris tanpa soal
            }

            // Simpan data soal ke database dengan nilai null untuk gambar terlebih dahulu
            $soal = MasterSoal::create([
                'id_materi' => $idMateri,
                'id_kategori_soal' => $idKategoriSoal,
                'id_kategori_jawaban' => $idKategoriJawaban,
                'soal' => null, // Simpan null untuk gambar soal
                'pilihan_1' => null, // Simpan null untuk gambar pilihan 1
                'pilihan_2' => null, // Simpan null untuk gambar pilihan 2
                'pilihan_3' => null, // Simpan null untuk gambar pilihan 3
                'pilihan_4' => null, // Simpan null untuk gambar pilihan 4
                'sts' => isset($row['status']) && ($row['status'] == 'Aktif' || $row['status'] == 1) ? 1 : 0,
                'bobot' => $row['bobot'] ?? 0,
                'jawaban' => $jawaban,
                'type' => $row['type']
            ]);

            // Ambil file Excel yang sedang diimport
            $spreadsheet = IOFactory::load(request()->file('file'));
            $worksheet = $spreadsheet->getActiveSheet();

            // Ambil nomor baris dari data yang sedang diproses
            $rowNumber = $this->getCurrentRowIndex();

            // Array untuk menyimpan kolom yang perlu diupdate
            $updates = [];

            // Flag untuk menandai apakah ada gambar di pilihan jawaban
            $hasImageInOptions = false;

            // Mapping kolom Excel ke field database
            $columnMappings = [
                'soal' => 'soal',
                'pilihan 1' => 'pilihan_1',
                'pilihan 2' => 'pilihan_2',
                'pilihan 3' => 'pilihan_3',
                'pilihan 4' => 'pilihan_4'
            ];

            // Loop melalui semua gambar di worksheet
            foreach ($worksheet->getDrawingCollection() as $drawing) {
                // Ambil koordinat gambar (misal: B2 → dapat angka 2 sebagai baris)
                preg_match('/([A-Z]+)(\d+)/', $drawing->getCoordinates(), $matches);

                if (isset($matches[1]) && isset($matches[2])) {
                    $column = $matches[1];
                    $drawingRowNumber = (int) $matches[2];

                    // Jika gambar ada di baris data yang sedang diproses
                    if ($drawingRowNumber === $rowNumber) {
                        $imageContents = null;
                        $extension = null;

                        // Ambil konten gambar
                        if ($drawing instanceof MemoryDrawing) {
                            // Handle gambar dari MemoryDrawing
                            ob_start();
                            call_user_func(
                                $drawing->getRenderingFunction(),
                                $drawing->getImageResource()
                            );
                            $imageContents = ob_get_contents();
                            ob_end_clean();

                            // Tentukan ekstensi berdasarkan mime type
                            switch ($drawing->getMimeType()) {
                                case MemoryDrawing::MIMETYPE_PNG:
                                    $extension = 'png';
                                    break;
                                case MemoryDrawing::MIMETYPE_GIF:
                                    $extension = 'gif';
                                    break;
                                case MemoryDrawing::MIMETYPE_JPEG:
                                    $extension = 'jpg';
                                    break;
                            }
                        } else {
                            // Handle gambar dari file sistem
                            $zipReader = fopen($drawing->getPath(), 'r');
                            $imageContents = '';
                            while (!feof($zipReader)) {
                                $imageContents .= fread($zipReader, 1024);
                            }
                            fclose($zipReader);
                            $extension = $drawing->getExtension();
                        }

                        // Tentukan field name berdasarkan kolom
                        $fieldName = null;

                        // Cari field name berdasarkan header kolom
                        foreach ($columnMappings as $excelColumn => $dbField) {
                            $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($column);
                            $fieldColumnLetter = $worksheet->getCellByColumnAndRow($columnIndex, 1)->getColumn();

                            if ($fieldColumnLetter === $column) {
                                $headerValue = $worksheet->getCellByColumnAndRow($columnIndex, 1)->getValue();
                                $headerValue = strtolower(trim($headerValue));

                                if (
                                    $headerValue === $excelColumn ||
                                    str_replace('_', '', $headerValue) === str_replace('_', '', $excelColumn)
                                ) {
                                    $fieldName = $dbField;
                                    break;
                                }
                            }
                        }

                        // Jika fieldName ditemukan, simpan gambar
                        if ($fieldName) {
                            // Tentukan direktori berdasarkan jenis field
                            $baseDirectory = 'uploads/';
                            if ($fieldName === 'soal') {
                                $directory = public_path($baseDirectory . 'soal/');
                            } else {
                                $directory = public_path($baseDirectory . 'jawaban/');
                            }

                            // Buat direktori jika belum ada
                            if (!file_exists($directory)) {
                                mkdir($directory, 0777, true);
                            }

                            // Generate nama file unik
                            $fileName = time() . '_' . $fieldName . '_' . uniqid() . '.' . $extension;
                            $filePath = $directory . $fileName;

                            // Simpan gambar ke direktori
                            file_put_contents($filePath, $imageContents);

                            // Simpan path relatif ke database
                            $relativePath = $baseDirectory . ($fieldName === 'soal' ? 'soal/' : 'jawaban/') . $fileName;
                            $updates[$fieldName] = $relativePath;

                            // Jika gambar ditemukan di pilihan jawaban, tandai
                            if (in_array($fieldName, ['pilihan_1', 'pilihan_2', 'pilihan_3', 'pilihan_4'])) {
                                $hasImageInOptions = true;
                                \Log::info('Gambar ditemukan di pilihan jawaban: ' . $fieldName);
                            }
                        }
                    }
                }
            }

            // Jika ada gambar di pilihan jawaban, update kategori jawaban ke 'foto'
            if ($hasImageInOptions) {
                $kategoriJawabanFoto = MasterKategori::where('nama_kategori', 'foto')->first();
                if ($kategoriJawabanFoto) {
                    $updates['id_kategori_jawaban'] = $kategoriJawabanFoto->id_kategori;
                    \Log::info('Mengubah kategori jawaban ke foto untuk soal ID: ' . $soal->id_soal);
                } else {
                    \Log::warning('Kategori jawaban "foto" tidak ditemukan di database');
                }
            }

            // Jika ada gambar di soal, update kategori soal ke 'foto'
            if (isset($updates['soal'])) {
                $kategoriSoalFoto = MasterKategori::where('nama_kategori', 'foto')->first();
                if ($kategoriSoalFoto) {
                    $updates['id_kategori_soal'] = $kategoriSoalFoto->id_kategori;
                    \Log::info('Mengubah kategori soal ke foto untuk soal ID: ' . $soal->id_soal);
                }
            }

            // Jika ada update, update record
            if (!empty($updates)) {
                $soal->update($updates);
                \Log::info('Soal berhasil diupdate dengan data: ' . json_encode($updates));
            }

            return $soal;
        } catch (\Exception $e) {
            \Log::error('Error importing soal: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    private function getCurrentRowIndex()
    {
        return self::$rowIndex++;
    }
}
