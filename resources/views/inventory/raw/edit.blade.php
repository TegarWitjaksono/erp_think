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
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Inventory Bahan Baku</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Edit Inventory Bahan Baku</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="/home" style="color: #79523B;">
                                            <i class="fas fa-home"></i> Home
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('sku.index') }}" style="color: #79523B;">SKU</a>
                                    </li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Inventory Bahan Baku</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            @if ($errors->any())
                <div class="alert alert-danger mx-3 mt-3">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Terjadi Kesalahan:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Inventory Bahan Baku</h3>
                            </div>

                            <form action="{{ route('inventory.update', $data->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Penerima</label>
                                        <select name="penerimaan_id" class="form-control">
                                            @foreach($penerimaanList as $k)
                                                <option value="{{ $k->id_penerimaan }}" {{ (old('penerimaan_id', $data->penerimaan_id) == $k->id_penerimaan) ? 'selected' : '' }}>
                                                    {{ $k->keterangan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Detail Penerima</label>
                                        <select name="id_detail_penerimaan" class="form-control">
                                            @foreach($details as $k)
                                                <option value="{{ $k->id_detail_penerimaan }}" {{ (old('id_detail_penerimaan', $data->id_detail_penerimaan) == $k->id_detail_penerimaan) ? 'selected' : '' }}>
                                                    {{ $k->harga_per_kg }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Pilih Karung</label>
                                        <select name="karung_id" class="form-control">
                                            @foreach($karung as $k)
                                                <option value="{{ $k->id }}" {{ (old('karung_id', $data->karung_id) == $k->id) ? 'selected' : '' }}>
                                                    {{ $k->kode_karung }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Catatan</label>
                                        <textarea name="catatan" class="form-control">{{ old('catatan', $data->catatan) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="kadar_air">Kadar Air (%)</label>
                                        <input type="number" step="0.01" name="kadar_air" id="kadar_air"
                                            class="form-control @error('kadar_air') is-invalid @enderror"
                                            value="{{ old('kadar_air', $data->kadar_air) }}" required>
                                        @error('kadar_air')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="bulk_densitas">Bulk Densitas</label>
                                        <input type="number" step="0.01" name="bulk_densitas" id="bulk_densitas"
                                            class="form-control @error('bulk_densitas') is-invalid @enderror"
                                            value="{{ old('bulk_densitas', $data->bulk_densitas) }}" required>
                                        @error('bulk_densitas')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="debit_qty">Debit Qty</label>
                                        <input type="number" step="0.01" name="debit_qty" id="debit_qty"
                                            class="form-control @error('debit_qty') is-invalid @enderror"
                                            value="{{ old('debit_qty', $data->debit_qty) }}" required>
                                        @error('debit_qty')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="credit_qty">Credit Qty</label>
                                        <input type="number" step="0.01" name="credit_qty" id="credit_qty"
                                            class="form-control @error('credit_qty') is-invalid @enderror"
                                            value="{{ old('credit_qty', $data->credit_qty) }}" required>
                                        @error('credit_qty')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
                                    <button type="submit" class="btn btn-coffee">UPDATE</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
