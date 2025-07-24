@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
    <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-coffee fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Finished Products</h1>
                                <div style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;"></div>
                                <p class="text-muted mt-2 mb-0">Manage your roasted coffee products inventory</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Finished Products</li>
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
                                <h3 class="card-title">Edit Finished Product</h3>
                            </div>
                            <form action="{{ route('finished_products.update', $product->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="roast_batch_id">Roast Batch ID</label>
                                        <input type="number" name="roast_batch_id" id="roast_batch_id"
                                            class="form-control @error('roast_batch_id') is-invalid @enderror" required
                                            value="{{ old('roast_batch_id', $product->roast_batch_id) }}">
                                        @error('roast_batch_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="weight_final">Final Weight (g)</label>
                                        <input type="number" step="0.01" name="weight_final" id="weight_final"
                                            class="form-control @error('weight_final') is-invalid @enderror" required
                                            value="{{ old('weight_final', $product->weight_final) }}">
                                        @error('weight_final')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="hpp">HPP</label>
                                        <input type="number" step="0.01" name="hpp" id="hpp"
                                            class="form-control @error('hpp') is-invalid @enderror" required
                                            value="{{ old('hpp', $product->hpp) }}">
                                        @error('hpp')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="harga_jual">Selling Price</label>
                                        <input type="number" step="0.01" name="harga_jual" id="harga_jual"
                                            class="form-control @error('harga_jual') is-invalid @enderror" required
                                            value="{{ old('harga_jual', $product->harga_jual) }}">
                                        @error('harga_jual')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="stock_status">Stock Status</label>
                                        <select name="stock_status" id="stock_status"
                                            class="form-control @error('stock_status') is-invalid @enderror" required>
                                            <option value="">Select status</option>
                                            <option value="ready"
                                                {{ old('stock_status', $product->stock_status) == 'ready' ? 'selected' : '' }}>
                                                ready</option>
                                            <option value="sold"
                                                {{ old('stock_status', $product->stock_status) == 'sold' ? 'selected' : '' }}>
                                                Sold</option>
                                            <option value="reserved"
                                                {{ old('stock_status', $product->stock_status) == 'reserved' ? 'selected' : '' }}>
                                                Reserved</option>
                                        </select>
                                        @error('stock_status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-coffee">Update</button>
                                    <a href="{{ route('finished_products.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
