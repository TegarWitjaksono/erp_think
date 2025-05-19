@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Detail Master Jadwal</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Tombol Tambah Jadwal -->
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahJadwalModal">
                    Tambah Detail Jadwal
                </button>


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Detail Jadwal
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Nama Jadwal</th>
                                        <th>Materi</th>
                                        <th>Quiz</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->jadwal->nama_jadwal }}</td>
                                            <td>
                                                {{ $jadwal->materi->judul ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $jadwal->quiz->nama_quiz ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $jadwal->jam_in }}
                                            </td>
                                            <td>
                                                {{ $jadwal->jam_out }}
                                            </td>
                                            <td>{{ $jadwal->sts == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                                            <td>
                                                <a href="{{ route('master_jadwal.detail.edit', base64_encode($jadwal->id_detail_jadwal)) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteModal{{ $jadwal->id_detail_jadwal }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $jadwal->id_detail_jadwal }}"
                                                    tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Jika Anda menekan tombol hapus maka data akan terhapus.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Kembali</button>
                                                                <form
                                                                    action="{{ route('master_jadwal.detail.destroy', base64_encode($jadwal->id_detail_jadwal)) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="tambahJadwalModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_jadwal.detail-store', base64_encode($id)) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="type">Pilih Tipe</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                                required>
                                <option value="0">Pilih</option>
                                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Materi</option>
                                <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Quiz</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" id="materiField">
                            <label for="id_materi">Materi</label>
                            <select name="id_materi" class="form-control @error('id_materi') is-invalid @enderror">
                                <option value="">Pilih Materi</option>
                                @foreach ($materi as $mtr)
                                    <option value="{{ $mtr->id_materi }}">{{ $mtr->judul }}</option>
                                @endforeach
                            </select>
                            @error('id_materi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" id="quizField">
                            <label for="id_quiz">Quiz</label>
                            <select name="id_quiz" class="form-control @error('id_quiz') is-invalid @enderror">
                                <option value="">Pilih quiz</option>
                                @foreach ($quiz as $quiz)
                                    <option value="{{ $quiz->id_quiz }}">{{ $quiz->nama_quiz }}</option>
                                @endforeach
                            </select>
                            @error('id_quiz')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jam_in">Jam Masuk</label>
                            <input type="time" name="jam_in"
                                class="form-control @error('jam_in') is-invalid @enderror" value="{{ old('jam_in') }}"
                                required>
                            @error('jam_in')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jam_out">Jam Keluar</label>
                            <input type="time" name="jam_out"
                                class="form-control @error('jam_out') is-invalid @enderror" value="{{ old('jam_out') }}"
                                required>
                            @error('jam_out')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Impor Jadwal -->
    <div class="modal fade" id="importJadwalModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Impor Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_jadwal.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                required>
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.datatable').DataTable({
            responsive: true
        });

        $('#type').change(function() {
            var type = $(this).val();
            if (type == '1') {
                $('#materiField').show();
                $('#quizField').hide();
            } else if (type == '2') {
                $('#materiField').hide();
                $('#quizField').show();
            } else {
                $('#materiField').hide();
                $('#quizField').hide();
            }
        });

        $(document).ready(function() {
            var type = $('#type').val();
            $('#materiField').hide();
            $('#quizField').hide();
            if (type == '1') {
                $('#materiField').show();
                $('#quizField').hide();
            } else if (type == '2') {
                $('#materiField').hide();
                $('#quizField').show();
            }
        });

        setTimeout(function() {
            $('.floating-alert').alert('close');
        }, 2000);

        @if ($errors->any())
            $(document).ready(function() {
                $('#tambahJadwalModal').modal('show');
            });
        @endif
    </script>
@endsection
