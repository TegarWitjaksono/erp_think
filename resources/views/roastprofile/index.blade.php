@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-box fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Data Roast Profile</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage your coffee bean Data Roast Profile</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Data Roast
                                        Profile</li>
                                </ol>
                            </nav>
                        </div>
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
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#tambahSuppliers">
                    <i class="fas fa-plus-circle mr-2"></i> Add Roast Profile
                </button>


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Roast Profile
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Charge Temp</th>
                                        <th>TP Temp</th>
                                        <th>TP Time Sec</th>
                                        <th>Aksi</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($items as $s)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $s->deskripsi }}</td>
                                            <td>{{ $s->charge_temp }}</td>
                                            <td>{{ $s->tp_temp }}</td>
                                            <td>{{ $s->tp_time_sec }}</td>
                                            <td>
                                                <a href="{{ route('roast_profile.edit', base64_encode($s->id)) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $s->id }}" data-toggle="modal"
                                                    data-target="#deleteModal">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
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
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Machines machines? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Tambah Jurusan -->
    <div class="modal fade" id="tambahSuppliers" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add The Roast Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('roast_profile.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="charge_temp">Charge Temp (°C)</label>
                                <input type="number" step="0.01" name="charge_temp" id="charge_temp"
                                    class="form-control" value="{{ old('charge_temp') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="tp_temp">Turning Point Temp (°C)</label>
                                <input type="number" step="0.01" name="tp_temp" id="tp_temp" class="form-control"
                                    value="{{ old('tp_temp') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="tp_time_sec">TP Time (sec)</label>
                                <input type="number" name="tp_time_sec" id="tp_time_sec" class="form-control"
                                    value="{{ old('tp_time_sec') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="de_temp">Dry End Temp (°C)</label>
                                <input type="number" step="0.01" name="de_temp" id="de_temp" class="form-control"
                                    value="{{ old('de_temp') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="de_time_sec">DE Time (sec)</label>
                                <input type="number" name="de_time_sec" id="de_time_sec" class="form-control"
                                    value="{{ old('de_time_sec') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="fcs_temp">First Crack Start Temp (°C)</label>
                                <input type="number" step="0.01" name="fcs_temp" id="fcs_temp"
                                    class="form-control" value="{{ old('fcs_temp') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="fcs_time_sec">FCS Time (sec)</label>
                                <input type="number" name="fcs_time_sec" id="fcs_time_sec" class="form-control"
                                    value="{{ old('fcs_time_sec') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="drop_temp">Drop Temp (°C)</label>
                                <input type="number" step="0.01" name="drop_temp" id="drop_temp"
                                    class="form-control" value="{{ old('drop_temp') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="drop_time_sec">Drop Time (sec)</label>
                            <input type="number" name="drop_time_sec" id="drop_time_sec" class="form-control"
                                value="{{ old('drop_time_sec') }}">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-coffee">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delete Modal -->


    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var url = "{{ route('roast_profile.destroy', ':id') }}";
                url = url.replace(':id', id);
                $('#deleteForm').attr('action', url);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@endsection
