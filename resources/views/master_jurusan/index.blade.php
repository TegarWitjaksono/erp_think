@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Jurusan</h1>
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
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahJurusanModal">
                    Tambah Jurusan
                </button>
                <!-- Tombol Ekspor -->
                <a href="{{ route('jurusan.export') }}" class="btn btn-success mb-3">Export Excel</a>
                <!-- Tombol Impor -->
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#importJurusanModal">
                    Import Excel
                </button>
                <a href="{{ route('jurusan.template') }}" class="btn btn-danger mb-3">Template Excel</a>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Jurusan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID Jurusan</th>
                                        <th>Nama Jurusan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jurusan as $j)
                                        <tr>
                                            <td>{{ $j->id_jurusan }}</td>
                                            <td>{{ $j->nama_jurusan }}</td>
                                            <td>
                                                <a href="{{ route('master_jurusan.edit', $j->id_jurusan) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteModal{{ $j->id_jurusan }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $j->id_jurusan }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                                                                    action="{{ route('master_jurusan.destroy', $j->id_jurusan) }}"
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
        </section>
    </div>

    <!-- Modal Tambah Jurusan -->
    <div class="modal fade" id="tambahJurusanModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Jurusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_jurusan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_jurusan">Nama Jurusan</label>
                            <input type="text" name="nama_jurusan" class="form-control" required>
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

    <!-- Modal Impor Jurusan -->
    <div class="modal fade" id="importJurusanModal" tabindex="-1" role="dialog" aria-labelledby="importJurusanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importJurusanModalLabel">Import Data Jurusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('jurusan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File Excel</label>
                            <input type="file" name="file"
                                class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Download template:
                                <a href="{{ route('jurusan.template') }}">
                                    <i class="fas fa-download"></i> Template Excel
                                </a>
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
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });
        });
    </script>
@endsection
