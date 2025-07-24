@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-shopping-cart fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Sales Records</h1>
                                <div style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;"></div>
                                <p class="text-muted mt-2 mb-0">Track and manage your coffee sales transactions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Sales Records</li>
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
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addSaleModal">
                    <i class="fas fa-plus-circle mr-2"></i> Add Sale Record
                </button>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Sales Data
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="sales-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Product</th>
                                        <th>Quantity Sold</th>
                                        <th>Total Price</th>
                                        <th>Sale Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $sale)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $sale->product_name ?? $sale->finished_product_id }}</td>
                                            <td>{{ $sale->qty_sold }}</td>
                                            <td>{{ number_format($sale->total_price, 2) }}</td>
                                            <td>{{ date('d M Y', strtotime($sale->sale_date)) }}</td>
                                            <td>
                                                <a href="{{ route('sales.edit', base64_encode($sale->id)) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Updated Delete Button -->
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $sale->id }}" data-toggle="modal"
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

    <!-- Add Sale Modal -->
    <div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add New Sale Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="finished_product_id">Product</label>
                            <select name="finished_product_id" id="finished_product_id"
                                class="form-control @error('finished_product_id') is-invalid @enderror" required>
                                <option value="">Select a product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('finished_product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->product_name ?? ($product->name ?? ($product->title ?? 'Product #' . $product->id)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('finished_product_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="qty_sold">Quantity Sold</label>
                            <input type="number" name="qty_sold" id="qty_sold"
                                class="form-control @error('qty_sold') is-invalid @enderror" required
                                value="{{ old('qty_sold') }}">
                            @error('qty_sold')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="total_price">Total Price</label>
                            <input type="number" step="0.01" name="total_price" id="total_price"
                                class="form-control @error('total_price') is-invalid @enderror" required
                                value="{{ old('total_price') }}" readonly>
                            @error('total_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sale_date">Sale Date</label>
                            <input type="date" name="sale_date" id="sale_date"
                                class="form-control @error('sale_date') is-invalid @enderror" required
                                value="{{ old('sale_date', date('Y-m-d')) }}">
                            @error('sale_date')
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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this sale record? This action cannot be undone.
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var url = "{{ route('sales.destroy', ':id') }}";
                url = url.replace(':id', id);
                $('#deleteForm').attr('action', url);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // Get product price and calculate total
            $('#finished_product_id, #qty_sold').on('change input', function() {
                calculateTotal();
            });

            function calculateTotal() {
                var productId = $('#finished_product_id').val();
                var quantity = $('#qty_sold').val();

                if (productId && quantity) {
                    // Get product data using AJAX
                    $.ajax({
                        url: "{{ route('sales.getProductPrice', ':id') }}".replace(':id', productId),
                        type: 'GET',
                        success: function(response) {
                            if (response.harga_jual) {
                                var total = parseFloat(response.harga_jual) * parseFloat(quantity);
                                $('#total_price').val(total.toFixed(2));
                            }
                        },
                        error: function(xhr) {
                            console.log('Error getting product price');
                        }
                    });
                }
            }
        });
    </script>
@endsection
