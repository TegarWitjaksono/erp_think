@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Detail Nilai</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Tombol Ekspor -->
                <a href="{{ route('detail_nilai.export', ['id' => base64_encode($detail->first()->id_trans_nilai)]) }}"
                    class="btn btn-success mb-3"> <i class="fas fa-file-excel"></i>Export
                    Excel</a>
                <!-- Tombol Impor -->
                <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list"></i> Detail Jawaban Siswa
                    </div>
                    <div class="card-body">
                        @if ($detail->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap text-center align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 30%;">Soal</th>
                                            <th style="width: 20%;">Jawaban Benar</th>
                                            <th style="width: 20%;">Jawaban Siswa</th>
                                            <th style="width: 10%;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            function renderMedia($content)
                                            {
                                                $ext = strtolower(pathinfo($content, PATHINFO_EXTENSION));

                                                if (strpos($content, 'uploads/') !== false) {
                                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        return '<img src="' .
                                                            asset($content) .
                                                            '" alt="Gambar" width="100" class="img-fluid d-block mx-auto">';
                                                    }

                                                    if (in_array($ext, ['mp3', 'wav', 'ogg'])) {
                                                        return '
                    <audio controls class="d-block mx-auto" style="max-width: 200px;">
                        <source src="' .
                                                            asset($content) .
                                                            '" type="audio/' .
                                                            $ext .
                                                            '">
                        Browser tidak mendukung pemutar audio.
                    </audio>';
                                                    }

                                                    if (in_array($ext, ['pdf'])) {
                                                        return '
                    <a href="' .
                                                            asset($content) .
                                                            '" target="_blank" class="btn btn-sm btn-outline-primary d-block mx-auto">
                        <i class="fas fa-file-pdf"></i> Lihat Dokumen
                    </a>';
                                                    }

                                                    return '<a href="' .
                                                        asset($content) .
                                                        '" target="_blank">Lihat File</a>';
                                                }

                                                return '<p class="m-0">' . e($content) . '</p>';
                                            }
                                        @endphp

                                        @foreach ($detail as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>

                                                <!-- Soal -->
                                                <td class="text-start" style="word-wrap: break-word; white-space: normal;">
                                                    {!! renderMedia($item->soal) !!}
                                                </td>

                                                <!-- Jawaban Benar -->
                                                <td class="text-start" style="word-wrap: break-word; white-space: normal;">
                                                    @php
                                                        $jawabanBenar = $item->{'pilihan_' . $item->jawaban};
                                                    @endphp
                                                    {!! renderMedia($jawabanBenar) !!}
                                                </td>

                                                <!-- Jawaban Siswa -->
                                                <td class="text-start" style="word-wrap: break-word; white-space: normal;">
                                                    @php
                                                        $jawabanSiswa = $item->{'pilihan_' . $item->pilihan};
                                                    @endphp
                                                    {!! renderMedia($jawabanSiswa) !!}
                                                </td>

                                                <!-- Status -->
                                                <td>
                                                    @if ($item->pilihan == $item->jawaban)
                                                        <span class="badge bg-success">Benar</span>
                                                    @else
                                                        <span class="badge bg-danger">Salah</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">Tidak ada data yang tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
