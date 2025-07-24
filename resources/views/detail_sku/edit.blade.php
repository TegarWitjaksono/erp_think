@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-edit fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Stock Movement</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Edit stock movement for {{ $sku->nama_barang }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('master_sku.index') }}" style="color: #79523B;">SKU</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('detail_sku.index', base64_encode($sku->id_sku)) }}" style="color: #79523B;">Detail SKU</a></li>
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
                                <h3 class="card-title">Edit Stock Movement</h3>
                            </div>
                            <form action="{{ route('detail_sku.update', base64_encode($detail->id_detail_sku)) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="stok_awal">Initial Stock</label>
                                        <input type="number" name="stok_awal" id="stok_awal"
                                            class="form-control @error('stok_awal') is-invalid @enderror" required
                                            value="{{ old('stok_awal', $detail->stok_awal) }}" readonly>
                                        <small class="text-muted">Initial stock cannot be modified</small>
                                        @error('stok_awal')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="stok_masuk">Stock In</label>
                                        <input type="number" name="stok_masuk" id="stok_masuk"
                                            class="form-control @error('stok_masuk') is-invalid @enderror" required
                                            value="{{ old('stok_masuk', $detail->stok_masuk) }}">
                                        @error('stok_masuk')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="stok_keluar">Stock Out</label>
                                        <input type="number" name="stok_keluar" id="stok_keluar"
                                            class="form-control @error('stok_keluar') is-invalid @enderror" required
                                            value="{{ old('stok_keluar', $detail->stok_keluar) }}">
                                        @error('stok_keluar')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="stok_akhir">Final Stock (Calculated)</label>
                                        <input type="number" id="stok_akhir" class="form-control" readonly
                                            value="{{ $detail->stok_akhir }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Tambahkan keterangan (opsional)">{{ old('keterangan', $detail->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('detail_sku.index', base64_encode($sku->id_sku)) }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-coffee">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
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