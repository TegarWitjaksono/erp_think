@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Jadwal</h1>
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
                    Tambah Jadwal
                </button>

                <!-- Tombol Ekspor dan Impor -->
                <a href="{{ route('master_jadwal.export') }}" class="btn btn-success mb-3">Ekspor Jadwal</a>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Jadwal
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Guru</th>
                                        <th>Kelas</th>
                                        <th>Nama Jadwal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwals as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->hari }}</td>
                                            <td>
                                                @php
                                                    $guru = App\Models\MasterGuru::find($jadwal->id_guru);
                                                @endphp
                                                {{ $guru ? $guru->nama_guru : '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $kelas = App\Models\MasterKelas::find($jadwal->id_kelas);
                                                @endphp
                                                {{ $kelas ? $kelas->nama_kelas : 'Kelas dihapus' }}
                                            </td>
                                            <td>{{ $jadwal->nama_jadwal }}</td>
                                            <td>{{ $jadwal->sts == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                                            <td>
                                                <a href="{{ route('master_jadwal.edit', $jadwal->id_jadwal) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteModal{{ $jadwal->id_jadwal }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <a href="{{ route('master_jadwal.detail', base64_encode($jadwal->id_jadwal)) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-info-circle"></i>

                                                </a>
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $jadwal->id_jadwal }}"
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
                                                                    action="{{ route('master_jadwal.destroy', $jadwal->id_jadwal) }}"
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
                <form action="{{ route('master_jadwal.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="hari">Hari</label>
                            <select name="hari" class="form-control @error('hari') is-invalid @enderror" required>
                                <option value="">Pilih Hari</option>
                                <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                            </select>
                            @error('hari')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_kelas">Kelas</label>
                            <select name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @php
                                    $kelasData = \App\Models\MasterKelas::all();
                                @endphp
                                @foreach ($kelasData as $kls)
                                    <option value="{{ $kls->id_kelas }}">{{ $kls->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('id_kelas')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_jadwal">Nama Jadwal</label>
                            <input type="text" name="nama_jadwal"
                                class="form-control @error('nama_jadwal') is-invalid @enderror"
                                value="{{ old('nama_jadwal') }}" required>
                            @error('nama_jadwal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sts">Status</label>
                            <select name="sts" class="form-control @error('sts') is-invalid @enderror" required>
                                <option value="1" {{ old('sts') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('sts') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('sts')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_guru">Guru</label>
                            <select name="id_guru" class="form-control @error('id_guru') is-invalid @enderror">
                                <option value="">Pilih Guru</option>
                                @foreach ($gurus as $guru)
                                    <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                                @endforeach
                            </select>
                            @error('id_guru')
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
