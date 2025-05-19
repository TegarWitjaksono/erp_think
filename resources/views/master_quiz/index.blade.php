@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Quiz</h1>
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
                <!-- Tombol Tambah Guru -->
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahquizModal">
                    Tambah Quiz
                </button>
                <!-- Tombol Ekspor -->
                <a href="{{ route('quiz.export') }}" class="btn btn-success mb-3">Export Excel</a>
                <!-- Tombol Impor -->
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#importQuizModal">
                    Import Excel
                </button>
                <a href="{{ route('quiz.template') }}" class="btn btn-danger mb-3">Template Excel</a>


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Quiz Tersedia
                    </div>
                    <div class="card-body">
                        <table id="tabel-data" class="table datatable table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Nama Quiz</th>
                                    <th>Deksripsi</th>
                                    <th>Type</th>
                                    <th>Icon</th>
                                    <th>Durasi</th>
                                    <th>Status</th>

                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizs as $quiz)
                                    <tr>
                                        <td>{{ $quiz->nama_quiz }}</td>
                                        <td>{{ $quiz->desc }}</td>
                                        <td>{{ $quiz->typ == 1 ? 'Quiz' : 'Ujian' }}</td>
                                        <td>
                                            <img src="{{ url('uploads/quiz/' . $quiz->icon) }}" width="50">
                                        </td>
                                        <td>{{ $quiz->durasi }}</td>
                                        <td>{{ $quiz->sts == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                                        <td>
                                            <a href="{{ route('master_quiz.edit', $quiz->id_quiz) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal{{ $quiz->id_quiz }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $quiz->id_quiz }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi</h5>
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
                                                        <form action="{{ route('master_quiz.destroy', $quiz->id_quiz) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah quiz -->
    <div class="modal fade" id="tambahquizModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah quiz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_quiz.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama_quiz">Nama Quiz</label>
                                    <input type="text" name="nama_quiz"
                                        class="form-control @error('nama_quiz') is-invalid @enderror"
                                        value="{{ old('nama_quiz') }}">
                                    @error('nama_quiz')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="desc">Deskripsi</label>
                                    <textarea name="desc" class="form-control @error('desc') is-invalid @enderror" rows="4">{{ old('desc') }}</textarea>
                                    @error('desc')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="typ">Tipe Quiz</label>
                                    <select name="typ" class="form-control @error('typ') is-invalid @enderror">
                                        <option value="1" {{ old('typ') == 'Quiz' ? 'selected' : '' }}>
                                            Quiz
                                        </option>
                                        <option value="2" {{ old('typ') == 'Ujian' ? 'selected' : '' }}>
                                            Ujian
                                        </option>
                                    </select>
                                    @error('typ')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="durasi">Durasi Quiz/Ujian</label>
                                    <input type="number" name="durasi"
                                        class="form-control @error('durasi') is-invalid @enderror"
                                        value="{{ old('durasi') }}">
                                    @error('durasi')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="icon">icon</label>
                                    <input type="file" name="icon"
                                        class="form-control @error('icon') is-invalid @enderror">
                                    @error('icon')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <img id="fotoPreview" src="" class="mt-2" width="200"
                                        style="display: none; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                                </div>
                            </div>




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

    <!-- Modal Import Quiz -->
    <div class="modal fade" id="importQuizModal" tabindex="-1" role="dialog" aria-labelledby="importQuizModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importQuizModalLabel">Import Data Quiz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('quiz.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File Excel</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                required>
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Download template:
                                <a href="{{ route('quiz.template') }}">
                                    <i class="fas fa-download"></i> Template Excel
                                </a>
                            </small>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                Format file: .xlsx, .xls
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.datatable').DataTable({
            responsive: true
        });
    </script>
    <script>
        $(document).ready(function() {
            // Preview gambar sebelum upload
            $('input[name="icon"]').change(function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('fotoPreview');
                    output.src = reader.result;
                    output.style.display = 'block';
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        });
        setTimeout(function() {
            $('.floating-alert').alert('close');
        }, 2000);
    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#tambahquizModal').modal('show');
            });
        </script>
    @endif
@endsection
