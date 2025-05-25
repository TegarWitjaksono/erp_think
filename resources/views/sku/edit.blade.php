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
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit SKU</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Update your product SKU details</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('sku.index') }}" style="color: #79523B;">SKU</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Edit</li>
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
                                <h3 class="card-title">Edit SKU</h3>
                            </div>
                            <form action="{{ route('sku.update', $data->id_sku_asli) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama_sku">SKU Name (Optional)</label>
                                        <input type="text" name="nama_sku" id="nama_sku"
                                            class="form-control @error('nama_sku') is-invalid @enderror"
                                            value="{{ old('nama_sku', $data->nama_sku) }}">
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
                                                    {{ old('id_barang', $data->id_barang) == $item->id_barang ? 'selected' : '' }}>
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
                                                    {{ old('id_varietas', $data->id_varietas) == $item->id_varietas ? 'selected' : '' }}>
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
                                                    {{ old('id_supplier', $data->id_supplier) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_supplier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
                                    <button type="submit" class="btn btn-coffee">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection