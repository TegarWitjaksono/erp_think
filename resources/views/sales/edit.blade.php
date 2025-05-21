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
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Sales Records</h1>
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

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Sale Record</h3>
                            </div>
                            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="finished_product_id">Product</label>
                                        <select name="finished_product_id" id="finished_product_id"
                                            class="form-control @error('finished_product_id') is-invalid @enderror" required>
                                            <option value="">Select a product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                    {{ old('finished_product_id', $sale->finished_product_id) == $product->id ? 'selected' : '' }}>
                                                    Product #{{ $product->id }} - {{ number_format($product->harga_jual, 2) }}
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
                                            value="{{ old('qty_sold', $sale->qty_sold) }}">
                                        @error('qty_sold')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="total_price">Total Price</label>
                                        <input type="number" step="0.01" name="total_price" id="total_price"
                                            class="form-control @error('total_price') is-invalid @enderror" required
                                            value="{{ old('total_price', $sale->total_price) }}" readonly>
                                        @error('total_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="sale_date">Sale Date</label>
                                        <input type="date" name="sale_date" id="sale_date"
                                            class="form-control @error('sale_date') is-invalid @enderror" required
                                            value="{{ old('sale_date', date('Y-m-d', strtotime($sale->sale_date))) }}">
                                        @error('sale_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-coffee">Update</button>
                                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script type="text/javascript">
            $(document).ready(function() {
                // Initial calculation on page load
                calculateTotal();

                // Recalculate when product or quantity changes
                $('#finished_product_id, #qty_sold').on('change input', function() {
                    calculateTotal();
                });

                function calculateTotal() {
                    var productId = $('#finished_product_id').val();
                    var quantity = $('#qty_sold').val();
                    
                    if(productId && quantity) {
                        // Get product data using AJAX
                        $.ajax({
                            url: "{{ route('sales.getProductPrice', ':id') }}".replace(':id', productId),
                            type: 'GET',
                            success: function(response) {
                                if(response.harga_jual) {
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
    </div>
@endsection