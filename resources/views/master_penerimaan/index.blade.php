@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-truck-loading fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Master Penerimaan</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage penerimaan barang</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Master Penerimaan</li>
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
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addModalPenerimaan">
                    <i class="fas fa-plus-circle mr-2"></i> Add Penerimaan
                </button>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Penerimaan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="penerimaan-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Batch</th>
                                        <th>Supplier</th>
                                        <th>Jenis</th>
                                        <th>Varietas</th>
                                        <th>Grade</th>
                                        <th>Origin</th>
                                        <th>Kadar Air</th>
                                        <th>Bulk</th>
                                        <th>Kemasan</th>
                                        <th>Berat</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Size</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->id_batch ?? 'N/A' }}</td>
                                            <td>{{ $item->nama_suplier ?? 'N/A' }}</td>
                                            <td>{{ $item->nama_jenis ?? 'N/A' }}</td>
                                            <td>{{ $item->nama_varietas ?? 'N/A' }}</td>
                                            <td>{{ $item->nama_grade ?? 'N/A' }}</td>
                                            <td>{{ $item->nama_origin ?? 'N/A' }}</td>
                                            <td>{{ $item->kadar_air ?? 'N/A' }}%</td>
                                            <td>{{ $item->bulk ?? 'N/A' }}</td>
                                            <td>{{ $item->id_kemasan ?? 'N/A' }}</td>
                                            <td>{{ $item->berat ?? 'N/A' }}</td>
                                            <td>{{ $item->jumlah ?? 'N/A' }}</td>
                                            <td>{{ $item->jumlah_tot ?? 'N/A' }}</td>
                                            <td>{{ $item->size ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('master_penerimaan.edit', $item->id_penerimaan) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $item->id_penerimaan }}" data-toggle="modal"
                                                    data-target="#deleteModal">
                                                    <i class="fas fa-trash"></i>
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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Penerimaan Modal -->
    <div class="modal fade" id="addModalPenerimaan" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add New Penerimaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_penerimaan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_suplier">Supplier</label>
                                    <select name="id_suplier" id="id_suplier"
                                        class="form-control @error('id_suplier') is-invalid @enderror" required>
                                        <option value="">Select Supplier</option>
                                        @foreach ($suppliers as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('id_suplier') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_suplier')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_jenis">Jenis</label>
                                    <select name="id_jenis" id="id_jenis"
                                        class="form-control @error('id_jenis') is-invalid @enderror" required>
                                        <option value="">Select Jenis</option>
                                        @foreach ($jenis as $item)
                                            <option value="{{ $item->id_jenis }}"
                                                {{ old('id_jenis') == $item->id_jenis ? 'selected' : '' }}>
                                                {{ $item->deskripsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_jenis')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_varietas">Varietas</label>
                                    <select name="id_varietas" id="id_varietas"
                                        class="form-control @error('id_varietas') is-invalid @enderror" required>
                                        <option value="">Select Varietas</option>
                                        @foreach ($varietas as $item)
                                            <option value="{{ $item->id_varietas }}"
                                                {{ old('id_varietas') == $item->id_varietas ? 'selected' : '' }}>
                                                {{ $item->deskripsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_varietas')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_grade">Grade</label>
                                    <select name="id_grade" id="id_grade"
                                        class="form-control @error('id_grade') is-invalid @enderror" required>
                                        <option value="">Select Grade</option>
                                        @foreach ($grade as $item)
                                            <option value="{{ $item->id_grade }}"
                                                {{ old('id_grade') == $item->id_grade ? 'selected' : '' }}>
                                                {{ $item->deskripsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_grade')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_origin">Origin</label>
                                    <select name="id_origin" id="id_origin"
                                        class="form-control @error('id_origin') is-invalid @enderror" required>
                                        <option value="">Select Origin</option>
                                        @foreach ($origin as $item)
                                            <option value="{{ $item->id_origin }}"
                                                {{ old('id_origin') == $item->id_origin ? 'selected' : '' }}>
                                                {{ $item->deskripsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_origin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="kadar_air">Kadar Air (%)</label>
                                    <input type="number" step="0.01" name="kadar_air" id="kadar_air"
                                        class="form-control @error('kadar_air') is-invalid @enderror"
                                        value="{{ old('kadar_air') }}" required>
                                    @error('kadar_air')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="bulk">Bulk</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="bulk_value" id="bulk_value"
                                            class="form-control @error('bulk_value') is-invalid @enderror"
                                            value="{{ old('bulk_value') }}" required placeholder="Value">
                                        <div class="input-group-append">
                                            <select name="bulk_unit" id="bulk_unit" class="form-control @error('bulk_unit') is-invalid @enderror" required>
                                                <option value="kg" {{ old('bulk_unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                                <option value="liter" {{ old('bulk_unit') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                            </select>
                                        </div>
                                    </div>
                                    @error('bulk_value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('bulk_unit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_kemasan">Kemasan</label>
                                    <input type="text" name="id_kemasan" id="id_kemasan"
                                        class="form-control @error('id_kemasan') is-invalid @enderror"
                                        value="{{ old('id_kemasan') }}" required>
                                    @error('id_kemasan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="berat">Berat</label>
                                    <input type="number" step="0.01" name="berat" id="berat"
                                        class="form-control @error('berat') is-invalid @enderror"
                                        value="{{ old('berat') }}" required>
                                    @error('berat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror"
                                        value="{{ old('jumlah') }}" required>
                                    @error('jumlah')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    <input type="text" name="size" id="size"
                                        class="form-control @error('size') is-invalid @enderror"
                                        value="{{ old('size') }}" required>
                                    @error('size')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });

            // Handle delete button click
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                $('#deleteForm').attr('action', `{{ url('master_penerimaan') }}/${id}`);
            });

            // Auto calculate jumlah_tot when berat or jumlah changes
            $('#berat, #jumlah').on('input', function() {
                const berat = parseFloat($('#berat').val()) || 0;
                const jumlah = parseInt($('#jumlah').val()) || 0;
                const jumlah_tot = berat * jumlah;
                // If you want to display the total somewhere
                // $('#jumlah_tot').val(jumlah_tot);
            });
        });
    </script>
    @endsection
@endsection