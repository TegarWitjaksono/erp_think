@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Finished Products</h1>
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
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addProductModal">
                    <i class="fas fa-plus-circle mr-2"></i> Add Finished Product
                </button>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Finished Products Data
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="products-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Roast Batch ID</th>
                                        <th>Final Weight (g)</th>
                                        <th>HPP</th>
                                        <th>Selling Price</th>
                                        <th>Stock Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $product)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $product->roast_batch_id }}</td>
                                            <td>{{ $product->weight_final }}</td>
                                            <td>{{ number_format($product->hpp, 2) }}</td>
                                            <td>{{ number_format($product->harga_jual, 2) }}</td>
                                            <td>
                                                @if($product->stock_status == 'avail')
                                                    <span class="badge badge-success">Available</span>
                                                @elseif($product->stock_status == 'sold')
                                                    <span class="badge badge-danger">Sold</span>
                                                @elseif($product->stock_status == 'resv')
                                                    <span class="badge badge-warning">Reserved</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $product->stock_status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('finished_products.edit', base64_encode($product->id)) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                                    data-id="{{ $product->id }}" 
                                                    data-toggle="modal" 
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
                    Are you sure you want to delete this finished product? This action cannot be undone.
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add New Finished Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('finished_products.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="roast_batch_id">Roast Batch ID</label>
                            <input type="number" name="roast_batch_id" id="roast_batch_id"
                                class="form-control @error('roast_batch_id') is-invalid @enderror" required
                                value="{{ old('roast_batch_id') }}">
                            @error('roast_batch_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="weight_final">Final Weight (g)</label>
                            <input type="number" step="0.01" name="weight_final" id="weight_final"
                                class="form-control @error('weight_final') is-invalid @enderror" required
                                value="{{ old('weight_final') }}">
                            @error('weight_final')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="hpp">HPP</label>
                            <input type="number" step="0.01" name="hpp" id="hpp"
                                class="form-control @error('hpp') is-invalid @enderror" required
                                value="{{ old('hpp') }}">
                            @error('hpp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_jual">Selling Price</label>
                            <input type="number" step="0.01" name="harga_jual" id="harga_jual"
                                class="form-control @error('harga_jual') is-invalid @enderror" required
                                value="{{ old('harga_jual') }}">
                            @error('harga_jual')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stock_status">Stock Status</label>
                            <select name="stock_status" id="stock_status"
                                class="form-control @error('stock_status') is-invalid @enderror" required>
                                <option value="">Select status</option>
                                <option value="avail" {{ old('stock_status') == 'avail' ? 'selected' : '' }}>Available</option>
                                <option value="sold" {{ old('stock_status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="resv" {{ old('stock_status') == 'resv' ? 'selected' : '' }}>Reserved</option>
                            </select>
                            @error('stock_status')
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
                var url = "{{ route('finished_products.destroy', ':id') }}";
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