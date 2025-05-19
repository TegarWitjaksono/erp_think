@extends('dashboard')

@section('konten')
<style>
    /* Existing styles... */
    
    /* Responsive table styles */
    .table-responsive {
        margin-bottom: 1rem;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Custom scrollbar for better UX */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Ensure buttons in action column stay in one line */
    .btn-group-sm > .btn, .btn-sm {
        padding: .25rem .5rem;
        font-size: .875rem;
        line-height: 1.5;
        border-radius: .2rem;
    }
    
    /* Responsive text handling */
    .text-nowrap {
        white-space: nowrap !important;
    }
    
    @media screen and (max-width: 768px) {
        .table td, .table th {
            padding: .5rem;
        }
        
        .btn-sm {
            padding: .2rem .4rem;
            font-size: .775rem;
        }
    }
</style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Siswa</h1>
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
                <!-- Tombol Tambah Siswa -->
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahSiswaModal">
                    Tambah Siswa
                </button>
                <!-- Tombol Ekspor -->
                <a href="{{ route('siswa.export') }}" class="btn btn-success mb-3">Export Excel</a>
                <!-- Tombol Impor -->
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#importSiswaModal">
                    Import Excel
                </button>
                <a href="{{ route('siswa.template') }}" class="btn btn-danger mb-3">Template Excel</a>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Siswa
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0">
                            <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th width="15%">Nama</th>
                                        <th width="15%">Alamat</th>
                                        <th width="5%">Jenis Kelamin</th>
                                        <th width="10%">NIP</th>
                                        <th width="10%">NIK</th>
                                        <th width="5%">Foto</th>
                                        <th width="10%">Jurusan</th>
                                        <th width="5%">Email</th>
                                        <th width="10%">Kelas</th>
                                        <th width="10%">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="toggleAllStatus" 
                                                       {{ $siswa->every(function($s) { return $s->sts == 1; }) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="toggleAllStatus">
                                                    <span id="toggleAllStatusText">Status</span>
                                                </label>
                                            </div>
                                        </th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $data)
                                        <tr>
                                            <td>{{ $data->nama_siswa }}</td>
                                            <td>{{ $data->alamat_siswa }}</td>
                                            <td>
                                                {{ $data->jenis_kelamin }}
                                            </td>
                                            <td>{{ $data->nip }}</td>
                                            <td>{{ $data->nik }}</td>
                                            <td>
                                                @if (!empty($data->foto) && file_exists(public_path('uploads/siswa/' . $data->foto)))
                                                    <img src="{{ url('uploads/siswa/' . $data->foto) }}" width="50">
                                                @else
                                                    Belum ada foto
                                                @endif
                                            </td>

                                            <td>{{ $data->jurusan ? $data->jurusan->nama_jurusan : 'Belum ada jurusan' }}
                                            </td>
                                            <td>{{ $data->email ? $data->email : 'belum ada email' }}</td>
                                            <td>{{ $data->kelas ? $data->kelas->nama_kelas : 'belum ada kelas' }}</td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input status-switch" 
                                                           id="statusSwitch{{ $data->id_siswa }}"
                                                           data-id="{{ $data->id_siswa }}"
                                                           data-email="{{ $data->email }}"
                                                           {{ $data->sts == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="statusSwitch{{ $data->id_siswa }}">
                                                        <span class="status-text">{{ $data->sts == 1 ? 'Aktif' : 'Nonaktif' }}</span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('master_siswa.edit', $data->id_siswa) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteModal{{ $data->id_siswa }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $data->id_siswa }}" tabindex="-1"
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
                                                            jika anda menekan tombol hapus maka data akan terhapus
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Kembali</button>
                                                            <form
                                                                action="{{ route('master_siswa.destroy', $data->id_siswa) }}"
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
            </div>
        </section>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade" id="tambahSiswaModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_siswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_siswa">Nama Siswa</label>
                                    <input type="text" name="nama_siswa"
                                        class="form-control @error('nama_siswa') is-invalid @enderror"
                                        value="{{ old('nama_siswa') }}">
                                    @error('nama_siswa')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alamat_siswa">Alamat</label>
                                    <textarea type="text" name="alamat_siswa" class="form-control @error('alamat_siswa') is-invalid @enderror"
                                        value="{{ old('alamat_siswa') }}" rows="4"></textarea>
                                    @error('alamat_siswa')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select name="jenis_kelamin"
                                        class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">email</label>
                                    <input type="text" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" name="nip"
                                        class="form-control @error('nip') is-invalid @enderror"
                                        value="{{ old('nip') }}">
                                    @error('nip')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" name="nik"
                                        class="form-control @error('nik') is-invalid @enderror"
                                        value="{{ old('nik') }}">
                                    @error('nik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group" id="jurusan-group">
                                    <label for="id_jurusan">Jurusan</label>
                                    <select name="id_jurusan" id="jurusan"
                                        class="form-control @error('id_jurusan') is-invalid @enderror">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach ($jurusan as $jrs)
                                            <option value="{{ $jrs->id_jurusan }}"
                                                {{ old('id_jurusan') == $jrs->id_jurusan ? 'selected' : '' }}>
                                                {{ $jrs->nama_jurusan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_jurusan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group" id="jurusan-group">
                                    <label for="id_kelas">Kelas</label>
                                    <select name="id_kelas" id="kelas"
                                        class="form-control @error('id_kelas') is-invalid @enderror">
                                        <option value="">Pilih kelas</option>
                                        @foreach ($kelas as $kelas)
                                            <option value="{{ $kelas->id_kelas }}"
                                                {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kelas')
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

    <!-- Modal Impor Siswa -->
    <div class="modal fade" id="importSiswaModal" tabindex="-1" role="dialog" aria-labelledby="importSiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importSiswaModalLabel">Import Data Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File Excel</label>
                            <input type="file" name="file"
                                class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

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
            $('#tabel-data').DataTable({
                responsive: true,
                scrollX: true,
                autoWidth: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                columnDefs: [
                    { responsivePriority: 1, targets: [0, 1, 8] }, // Columns that will be always visible
                    { responsivePriority: 2, targets: [2, 3] },    // Second priority
                    { responsivePriority: 3, targets: '_all' }     // Rest of the columns
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Preview gambar sebelum upload
            $('input[name="foto"]').change(function(event) {
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
    <script>
        $(document).ready(function() {
            // Handle individual switches
            $('.status-switch').change(function() {
                updateStatus($(this));
            });

            // Handle toggle all switch
            $('#toggleAllStatus').change(function() {
                const isChecked = $(this).prop('checked');
                const switches = $('.status-switch');
                
                // Show confirmation dialog with clear message about affecting ALL records
                if (confirm('Apakah Anda yakin ingin ' + (isChecked ? 'mengaktifkan' : 'menonaktifkan') + ' SEMUA siswa di SELURUH halaman?')) {
                    // Disable all switches during the process
                    switches.prop('disabled', true);
                    $('#toggleAllStatus').prop('disabled', true);
                    $('#toggleAllStatusText').text('Memproses...');
                    
                    // Update all statuses in the database
                    $.ajax({
                        url: "{{ route('master_siswa.update.status.all') }}",
                        type: 'POST',
                        data: {
                            status: isChecked ? 1 : 0,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.success) {
                                // Update all visible switches and labels
                                switches.prop('checked', isChecked);
                                $('.status-text').text(isChecked ? 'Aktif' : 'Nonaktif');
                                $('#toggleAllStatusText').text('Status');
                                toastr.success('Status semua siswa berhasil diperbarui');
                            } else {
                                toastr.error('Terjadi kesalahan: ' + response.message);
                                // Revert toggle all switch
                                $('#toggleAllStatus').prop('checked', !isChecked);
                                $('#toggleAllStatusText').text('Status');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage += ': ' + xhr.responseJSON.message;
                            }
                            toastr.error(errorMessage);
                            // Revert toggle all switch
                            $('#toggleAllStatus').prop('checked', !isChecked);
                            $('#toggleAllStatusText').text('Status');
                        },
                        complete: function() {
                            // Re-enable all switches
                            switches.prop('disabled', false);
                            $('#toggleAllStatus').prop('disabled', false);
                        }
                    });
                } else {
                    // If user cancels, revert the toggle all switch
                    $(this).prop('checked', !isChecked);
                }
            });

            // Function to update individual status
            function updateStatus(element) {
                const id = element.data('id');
                const status = element.prop('checked') ? 1 : 0;
                const statusText = element.closest('td').find('.status-text');

                // Disable the switch during the process
                element.prop('disabled', true);

                $.ajax({
                    url: "{{ route('master_siswa.update.status') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            statusText.text(status == 1 ? 'Aktif' : 'Nonaktif');
                            toastr.success('Status siswa berhasil diperbarui');
                            
                            
                            // Update toggle all checkbox state
                            updateToggleAllState();
                        } else {
                            toastr.error('Terjadi kesalahan');
                            element.prop('checked', !status);
                            statusText.text(status == 0 ? 'Aktif' : 'Nonaktif');
                        }
                    },
                    error: function() {
                        toastr.error('Terjadi kesalahan');
                        element.prop('checked', !status);
                    },
                    complete: function() {
                        // Re-enable the switch
                        element.prop('disabled', false);
                    }
                });
            }

            // Function to update toggle all checkbox state
            function updateToggleAllState() {
                const allSwitches = $('.status-switch');
                const allChecked = allSwitches.length === allSwitches.filter(':checked').length;
                $('#toggleAllStatus').prop('checked', allChecked);
            }

            // Set initial state of toggle all checkbox
            updateToggleAllState();
        });
    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#tambahSiswaModal').modal('show');
            });
        </script>
    @endif
@endsection
