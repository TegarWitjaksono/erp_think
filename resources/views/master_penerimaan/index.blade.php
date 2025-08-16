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
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Penerimaan Bahan Baku</h1>
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
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Penerimaan Bahan
                                        Baku
                                    </li>
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
                <a href="{{ route('master_penerimaan.create') }}" class="btn btn-coffee mb-3">
                    <i class="fas fa-plus-circle mr-2"></i> Add Penerimaan
                </a>

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

                                        <th>No Penerimaan</th> <!-- Tambahkan ini -->
                                        <th>Keterangan</th>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>

                                            <td>{{ $item->id_batch_mp ?? '-' }}</td> <!-- Tampilkan batch -->
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ date('Y-m-d', $item->cdate) }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                {{-- <a href="{{ route('detail_penerimaan.index', ['id_penerimaan' => $item->id_penerimaan]) }}"
                                                    class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-list"></i>
                                                </a> --}}
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
    <!-- Add Penerimaan Modal -->
    <div class="modal fade" id="addModalPenerimaan" tabindex="-1" aria-labelledby="addModalPenerimaanLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalPenerimaanLabel">Add Master Penerimaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_penerimaan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> ID Batch akan dibuat otomatis dengan format P0001, P0002,
                            dst.
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="cdate">Tanggal</label>
                            <input type="date" name="cdate" id="cdate"
                                class="form-control @error('cdate') is-invalid @enderror"
                                value="{{ old('cdate', date('Y-m-d')) }}" required>
                            @error('cdate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_batch_mp">ID Batch</label>
                            <input type="text" name="id_batch_mp" id="id_batch_mp"
                                class="form-control @error('id_batch_mp') is-invalid @enderror"
                                value="{{ old('id_batch_mp', $nextBatchId ?? '') }}" readonly required>
                            @error('id_batch_mp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        });
    </script>
@endsection
@endsection
