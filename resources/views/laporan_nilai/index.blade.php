@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Nilai</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if ($results->isNotEmpty())
                    <a href="{{ route('laporan_nilai.export', ['id_kelas' => request('id_kelas'), 'id_materi' => request('id_materi'), 'id_quiz' => request('id_quiz')]) }}"
                        class="btn btn-success mb-3">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @endif


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Data Nilai
                    </div>
                    <div class="card-body">
                        <!-- Form Filter -->
                        <form method="GET" action="{{ route('laporan_nilai.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="id_kelas">Nama Kelas:</label>
                                    <select name="id_kelas" id="id_kelas" class="form-control">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ base64_encode($kelas->id_kelas) }}"
                                                {{ request('id_kelas') == base64_encode($kelas->id_kelas) ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="tipe">Tipe :</label>
                                    <select name="tipe" id="tipe" class="form-control">
                                        <option value="">Pilih Tipe</option>
                                        <option value="materi">Materi</option>
                                        <option value="quiz">Quiz</option>
                                    </select>
                                </div>

                                <div class="col-md-3" id="materi-container" style="display: none;">
                                    <label for="id_materi">Materi:</label>
                                    <select name="id_materi" id="id_materi" class="form-control">
                                        <option value="">-- Pilih Materi --</option>
                                        @foreach ($materiList as $materi)
                                            <option value="{{ base64_encode($materi->id_materi) }}"
                                                {{ request('id') == base64_encode($materi->id_materi) ? 'selected' : '' }}>
                                                {{ $materi->judul }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3" id="quiz-container" style="display: none;">
                                    <label for="id_quiz">Quiz:</label>
                                    <select name="id_quiz" id="id_quiz" class="form-control">
                                        <option value="">-- Pilih Quiz --</option>
                                        @foreach ($quizList as $quiz)
                                            <option value="{{ base64_encode($quiz->id_quiz) }}"
                                                {{ request('id') == base64_encode($quiz->id_quiz) ? 'selected' : '' }}>
                                                {{ $quiz->nama_quiz }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button type="submit" class="btn btn-primary mt-2">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                    <a href="{{ route('laporan_nilai.detail_all', ['id_kelas' => request('id_kelas'), 'id_quiz' => request('id_quiz'), 'id_materi' => request('id_materi')]) }}"
                                        class="btn btn-success mt-2">
                                        <i class="fas fa-eye"></i>
                                    </a>



                                    <a href="{{ route('laporan_nilai.index') }}" class="btn btn-secondary mt-2">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Tampilkan tabel hanya jika data tersedia -->
                        @if ($results->isNotEmpty())
                            <div class="table-responsive">
                                <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Materi</th>
                                            <th>Quiz</th>
                                            <th>Kelas</th>
                                            <th>Jml Soal</th>
                                            <th>Jml Benar</th>
                                            <th>Jml Salah</th>
                                            <th>Score</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $index => $data)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $data->nama_siswa }}</td>
                                                <td>{{ $data->judul ?? '-' }}</td>
                                                <td>{{ $data->nama_quiz ?? '-' }}</td>
                                                <td>{{ $data->nama_kelas }}</td>
                                                <td>{{ $data->jum_soal }}</td>
                                                <td>{{ $data->benar }}</td>
                                                <td>{{ $data->salah }}</td>
                                                <td>{{ $data->score }}</td>
                                                <td>
                                                    <a href="{{ route('laporan_nilai.detail', base64_encode($data->id_trans)) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">Silakan pilih filter untuk melihat data.</p>
                        @endif
                    </div>
                </div>


        </section>
    </div>



    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#tipe').change(function() {
                var selectedType = $(this).val();
                if (selectedType === 'materi') {
                    $('#materi-container').show();
                    $('#quiz-container').hide();
                    $('#id_quiz').val(''); // Reset nilai quiz saat memilih materi
                } else if (selectedType === 'quiz') {
                    $('#quiz-container').show();
                    $('#materi-container').hide();
                    $('#id_materi').val(''); // Reset nilai materi saat memilih quiz
                } else {
                    $('#materi-container, #quiz-container').hide();
                    $('#id_materi, #id_quiz').val(''); // Reset keduanya jika tidak dipilih
                }
            });
        });
    </script>

@endsection
