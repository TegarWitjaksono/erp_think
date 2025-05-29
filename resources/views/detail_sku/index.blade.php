@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-list-alt fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Detail Stok Barang</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage your stock details</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('master_sku.index') }}" style="color: #79523B;">Stok Barang</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Detail Stok Barang</li>
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
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i>
                        Stok Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <td>{{ $sku->nama_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Current Stock</th>
                                        <td>{{ $sku->qty }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addDetailSkuModal">
                    <i class="fas fa-plus-circle mr-2"></i> Add Stock Movement
                </button>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Stock Movement History
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="details-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Initial Stock</th>
                                        <th>Stock In</th>
                                        <th>Stock Out</th>
                                        <th>Final Stock</th>
                                        <th>Keterangan</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                @php
                                    $total = count($details);
                                @endphp
                                <tbody>
                                    @foreach ($details as $index => $detail)
                                        <tr>
                                            <td>{{ $total - $index }}</td>
                                            <td>{{ date('d M Y H:i', strtotime($detail->cdate)) }}</td>
                                            <td>{{ $detail->stok_awal }}</td>
                                            <td>{{ $detail->stok_masuk }}</td>
                                            <td>{{ $detail->stok_keluar }}</td>
                                            <td>{{ $detail->stok_akhir }}</td>
                                            <td>{{ $detail->keterangan ?? '-' }}</td>
                                            <td>
                                                @if($detail->id_detail_sku == $latestRecordId)
                                                    <a href="{{ route('detail_sku.edit', base64_encode($detail->id_detail_sku)) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-secondary btn-sm" disabled title="Only the latest record can be edited">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $detail->id_detail_sku }}" data-toggle="modal"
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

    <!-- Delete Modal -->
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
                    Are you sure you want to delete this stock movement record? This action cannot be undone.
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

    <!-- Add Detail SKU Modal -->
    <div class="modal fade" id="addDetailSkuModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add Stock Movement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('detail_sku.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_sku" value="{{ $sku->id_sku }}">
                        
                        <div class="form-group">
                            <label for="stok_awal">Initial Stock</label>
                            <input type="number" name="stok_awal" id="stok_awal"
                                class="form-control @error('stok_awal') is-invalid @enderror" required
                                value="{{ $sku->qty }}" readonly>
                            <small class="text-muted">This value is automatically set to the current stock level</small>
                            @error('stok_awal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="stok_masuk">Stock In</label>
                            <input type="number" name="stok_masuk" id="stok_masuk"
                                class="form-control @error('stok_masuk') is-invalid @enderror" required
                                value="0">
                            @error('stok_masuk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="stok_keluar">Stock Out</label>
                            <input type="number" name="stok_keluar" id="stok_keluar"
                                class="form-control @error('stok_keluar') is-invalid @enderror" required
                                value="0">
                            @error('stok_keluar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="stok_akhir">Final Stock (Calculated)</label>
                            <input type="number" id="stok_akhir" class="form-control" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Tambahkan keterangan (opsional)"></textarea>
                            @error('keterangan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                order: [[1, 'desc']]
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var url = "{{ route('detail_sku.destroy', ':id') }}";
                url = url.replace(':id', btoa(id));
                $('#deleteForm').attr('action', url);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // Calculate final stock
            $('#stok_awal, #stok_masuk, #stok_keluar').on('input', function() {
                var stokAwal = parseInt($('#stok_awal').val()) || 0;
                var stokMasuk = parseInt($('#stok_masuk').val()) || 0;
                var stokKeluar = parseInt($('#stok_keluar').val()) || 0;
                
                var stokAkhir = stokAwal + stokMasuk - stokKeluar;
                $('#stok_akhir').val(stokAkhir);
            });
            
            // Trigger calculation on page load
            $('#stok_awal').trigger('input');
        });
    </script>
@endsection