@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-tags fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">SKU Management</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage your product SKUs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">SKU</li>
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

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addModalSku">
                    <i class="fas fa-plus-circle mr-2"></i> Add SKU
                </button>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        SKU Data
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="sku-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SKU Name</th>
                                        <th>Product</th>
                                        <th>Varietas</th>
                                        <th>Supplier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $sku)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $sku->nama_sku ?? 'N/A' }}</td>
                                            <td>{{ $sku->nama_barang ?? 'N/A' }}</td>
                                            <td>{{ $sku->nama_varietas ?? 'N/A' }}</td>
                                            <td>{{ $sku->nama_supplier ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('sku.edit', base64_encode($sku->id_sku_asli)) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $sku->id_sku_asli }}" data-toggle="modal"
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
                    Are you sure you want to delete this SKU? This action cannot be undone.
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

    <!-- Add SKU Modal -->
    <div class="modal fade" id="addModalSku" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add New SKU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sku.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_sku">SKU Name (Optional)</label>
                            <input type="text" name="nama_sku" id="nama_sku"
                                class="form-control @error('nama_sku') is-invalid @enderror"
                                value="{{ old('nama_sku') }}">
                            @error('nama_sku')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_barang">Product</label>
                            <select name="id_barang" id="id_barang"
                                class="form-control @error('id_barang') is-invalid @enderror" required>
                                <option value="">Select Product</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id_barang }}"
                                        {{ old('id_barang') == $item->id_barang ? 'selected' : '' }}>
                                        {{ $item->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_barang')
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
                            <label for="id_supplier">Supplier</label>
                            <select name="id_supplier" id="id_supplier"
                                class="form-control @error('id_supplier') is-invalid @enderror" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('id_supplier') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_supplier')
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
                responsive: true
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var url = "{{ route('sku.destroy', ':id') }}";
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