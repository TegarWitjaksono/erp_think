@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Kelas</h1>
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
                <!-- Tombol Tambah Kelas -->
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahKelasModal">
                    Tambah Kelas
                </button>
                <!-- Tombol Ekspor -->
                <a href="{{ route('kelas.export') }}" class="btn btn-success mb-3">Export Excel</a>
                <!-- Tombol Impor -->
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#importKelasModal">
                    Import Excel
                </button>
                <a href="{{ route('kelas.template') }}" class="btn btn-danger mb-3">Template Excel</a>


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Kelas
                    </div>
                    <div class="card-body">
                        <table id="tabel-data" class="table datatable table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Nama Kelas</th>
                                    <th>Jurusan</th> <!-- Tambahkan kolom jurusan -->
                                    <th>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="toggleAllStatus" 
                                                   {{ $kelas->every(function($k) { return $k->sts == 1; }) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="toggleAllStatus">
                                                <span id="toggleAllStatusText">Status</span>
                                            </label>
                                        </div>
                                    </th>
                                    <th>Foto</th> <!-- Tambahkan kolom foto -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelas as $kls)
                                    <tr>
                                        <td>{{ $kls->nama_kelas }}</td>
                                        <td>{{ $kls->jurusan->nama_jurusan ?? 'N/A' }}</td> <!-- Tampilkan nama jurusan -->
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input status-switch" 
                                                       id="statusSwitch{{ $kls->id_kelas }}"
                                                       data-id="{{ $kls->id_kelas }}"
                                                       {{ $kls->sts == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="statusSwitch{{ $kls->id_kelas }}">
                                                    <span class="status-text">{{ $kls->sts == 1 ? 'Aktif' : 'Nonaktif' }}</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($kls->foto)
                                                <img src="{{ url('uploads/kelas/' . $kls->foto) }}" alt="Foto Kelas"
                                                    style="width: 50px; height: auto;">
                                            @else
                                                Tidak ada foto
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('master_kelas.edit', $kls->id_kelas) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal{{ $kls->id_kelas }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $kls->id_kelas }}" tabindex="-1"
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
                                                            <form
                                                                action="{{ route('master_kelas.destroy', $kls->id_kelas) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
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

    <!-- Modal Tambah Kelas -->
    <div class="modal fade" id="tambahKelasModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_kelas.store') }}" method="POST" enctype="multipart/form-data">
                    <!-- Tambahkan enctype -->
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_kelas">Nama Kelas</label>
                            <input type="text" name="nama_kelas"
                                class="form-control @error('nama_kelas') is-invalid @enderror"
                                value="{{ old('nama_kelas') }}" required>
                            @error('nama_kelas')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_jurusan">Jurusan</label>
                            <select name="id_jurusan" class="form-control @error('id_jurusan') is-invalid @enderror"
                                required>
                                <option value="">Pilih Jurusan</option>
                                @foreach ($jurusan as $jrs)
                                    <option value="{{ $jrs->id_jurusan }}"
                                        {{ old('id_jurusan') == $jrs->id_jurusan ? 'selected' : '' }}>
                                        {{ $jrs->nama_jurusan }}</option>
                                @endforeach
                            </select>
                            @error('id_jurusan')
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
                            <label for="foto">Foto</label>
                            <input type="file" name="foto"
                                class="form-control @error('foto') is-invalid @enderror">
                            @error('foto')
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

    <!-- Modal Impor Kelas -->
    <div class="modal fade" id="importKelasModal" tabindex="-1" role="dialog" aria-labelledby="importKelasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importKelasModalLabel">Import Data Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kelas.import') }}" method="POST" enctype="multipart/form-data">
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
                                <a href="{{ route('kelas.template') }}">
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
        $('.datatable').DataTable({
            responsive: true
        });
    </script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.floating-alert').alert('close');
            }, 2000);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Handle individual status toggle
            $(document).on('change', '.status-switch', function() {
                const element = $(this);
                const id = element.data('id');
                const status = element.prop('checked') ? 1 : 0;
                const label = element.siblings('label').find('.status-text');

                if(confirm('Apakah Anda yakin ingin ' + (status ? 'mengaktifkan' : 'menonaktifkan') + ' kelas ini? Hal ini akan mempengaruhi status siswa yang terdaftar di kelas ini.')) {
                    element.prop('disabled', true);

                    $.ajax({
                        url: "{{ route('master_kelas.update.status') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.success) {
                                label.text(status ? 'Aktif' : 'Nonaktif');
                                toastr.success('Status kelas dan siswa berhasil diperbarui');
                                updateToggleAllState();
                            } else {
                                element.prop('checked', !status);
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            element.prop('checked', !status);
                            toastr.error('Terjadi kesalahan sistem');
                        },
                        complete: function() {
                            element.prop('disabled', false);
                        }
                    });
                } else {
                    element.prop('checked', !status);
                }
            });

            // Handle toggle all status
            $('#toggleAllStatus').change(function() {
                const isChecked = $(this).prop('checked');
                const switches = $('.status-switch:visible');
                
                if(confirm('Apakah Anda yakin ingin ' + (isChecked ? 'mengaktifkan' : 'menonaktifkan') + ' semua kelas? Hal ini akan mempengaruhi status semua siswa yang terdaftar.')) {
                    $(this).prop('disabled', true);
                    switches.prop('disabled', true);

                    $.ajax({
                        url: "{{ route('master_kelas.update.status.bulk') }}",
                        type: 'POST',
                        data: {
                            status: isChecked ? 1 : 0,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.success) {
                                switches.prop('checked', isChecked);
                                $('.status-text').text(isChecked ? 'Aktif' : 'Nonaktif');
                                toastr.success('Status semua kelas dan siswa berhasil diperbarui');
                            } else {
                                $('#toggleAllStatus').prop('checked', !isChecked);
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            $('#toggleAllStatus').prop('checked', !isChecked);
                            toastr.error('Terjadi kesalahan sistem');
                        },
                        complete: function() {
                            $('#toggleAllStatus').prop('disabled', false);
                            switches.prop('disabled', false);
                        }
                    });
                } else {
                    $(this).prop('checked', !isChecked);
                }
            });

            function updateToggleAllState() {
                const visibleSwitches = $('.status-switch:visible');
                const allChecked = visibleSwitches.length === visibleSwitches.filter(':checked').length;
                $('#toggleAllStatus').prop('checked', allChecked);
            }

            updateToggleAllState();
        });
    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#tambahKelasModal').modal('show');
            });
        </script>
    @endif
@endsection
