@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        {{-- Header & Breadcrumbs (sama seperti sebelumnya) --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <!-- ... -->
        </div>

        <section class="content">
            <div class="container-fluid">
                 @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Validation Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                        <div class="card shadow-sm">
                            <div class="card-header text-white">
                                <i class="fas fa-table me-1"></i>
                                {{ isset($blend) ? 'Edit' : 'Tambah' }} Penerimaan
                            </div>
                            <div class="card-body">
                            <form action="{{ route('master_penerimaan.update',$masterPenerimaan->id_penerimaan) }}" method="POST">
                                @method('PUT')
                                        @csrf
                                
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> NO PENERIMAAN akan dibuat otomatis dengan format P0001, P0002, dst.
                                    </div>
                                    <div class="form-group">
                                        <label for="id_batch_mp">NO PENERIMAAN</label>
                                        <input type="text" name="id_batch_mp" id="id_batch_mp"
                                            class="form-control @error('id_batch_mp') is-invalid @enderror"
                                            value="{{ old('id_batch_mp', $masterPenerimaan->id_batch_mp ?? '') }}" readonly required>
                                        @error('id_batch_mp')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" required>{{ old('keterangan', $masterPenerimaan->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="cdate">Tanggal</label>
                                        <input type="date" name="cdate" id="cdate" class="form-control @error('cdate') is-invalid @enderror" value="{{ old('cdate', isset($masterPenerimaan->cdate) ? \Carbon\Carbon::parse($masterPenerimaan->cdate)->format('Y-m-d') : '') }}"
                                        required>
                                        @error('cdate')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                        <h4 class="mt-4">Detail Penerimaan</h4>
                               <div id="detail-rows">
                                    @foreach ($details as $index => $detail)
                                    {{-- Tambahkan di sini --}}
    <input type="hidden" name="detail_ids[]" value="{{ $detail->id_detail_penerimaan }}">

                                    <div class="row border p-2 mb-2 detail-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Supplier</label>
                                                <select name="id_suplier[]" class="form-control" required>
                                                    <option value="">Pilih Supplier</option>
                                                    @foreach ($suppliers as $item)
                                                        <option value="{{ $item->id }}" {{ $item->id == $detail->id_suplier ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Jenis -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jenis</label>
                                                <select name="id_jenis[]" class="form-control" required>
                                                    <option value="">Pilih Jenis</option>
                                                    @foreach ($jenis as $item)
                                                        <option value="{{ $item->id_jenis }}" {{ $item->id_jenis == $detail->id_jenis ? 'selected' : '' }}>{{ $item->deskripsi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Varietas -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Varietas</label>
                                                <select name="id_varietas[]" class="form-control" required>
                                                    <option value="">Pilih Varietas</option>
                                                    @foreach ($varietas as $item)
                                                        <option value="{{ $item->id_varietas }}" {{ $item->id_varietas == $detail->id_varietas ? 'selected' : '' }}>{{ $item->deskripsi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Grade -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Grade</label>
                                                <select name="id_grade[]" class="form-control" required>
                                                    <option value="">Pilih Grade</option>
                                                    @foreach ($grade as $item)
                                                        <option value="{{ $item->id_grade }}" {{ $item->id_grade == $detail->id_grade ? 'selected' : '' }}>{{ $item->deskripsi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Baris 2 -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jumlah Karung</label>
                                                <input type="number" name="jumlah_karung[]" class="form-control" value="{{ $detail->jumlah }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Berat/Karung</label>
                                                <input type="number" name="berat_per_karung[]" class="form-control" step="0.01" value="{{ $detail->berat }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Origin</label>
                                                <select name="origin[]" class="form-control" required>
                                                    <option value="">Pilih Origin</option>
                                                    @foreach ($origin as $item)
                                                        <option value="{{ $item->id_origin }}" {{ $item->id_origin == $detail->id_origin ? 'selected' : '' }}>{{ $item->deskripsi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Bulk -->
                                        @php
                                            $bulkParts = explode(' ', $detail->bulk); // ["12", "kg"]
                                            $bulkValue = $bulkParts[0] ?? '';
                                            $bulkUnit = $bulkParts[1] ?? '';
                                        @endphp

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Bulk</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="bulk_value[]" class="form-control" value="{{ $bulkValue }}" required>
                                                    <div class="input-group-append">
                                                        <select name="bulk_unit[]" class="form-control" required>
                                                            <option value="kg" {{ $bulkUnit == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                                            <option value="liter" {{ $bulkUnit == 'liter' ? 'selected' : '' }}>Liter</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lain-lain -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Kadar Air %</label>
                                                <input type="number" step="0.01" name="kadar_air[]" class="form-control" value="{{ $detail->kadar_air }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Size</label>
                                                <input type="number" name="size[]" class="form-control" value="{{ $detail->size }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Harga/Kg</label>
                                                <input type="number" name="harga_per_kg[]" class="form-control" value="{{ $detail->harga_per_kg }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Kemasan</label>
                                                <input type="number" name="kemasan[]" class="form-control" value="{{ $detail->id_kemasan }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                

                        
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('master_penerimaan.index') }}" class="btn btn-secondary">Kembali</a>
                                        <button type="submit" class="btn btn-coffee">Simpan</button>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="add-detail">+ Tambah Baris</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
<script>
document.getElementById('add-detail').addEventListener('click', function () {
    const container = document.getElementById('detail-rows');
    const firstRow = container.querySelector('.detail-row');
    const newRow = firstRow.cloneNode(true);

    // Reset semua input dan select
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

    container.appendChild(newRow);
});
</script>





   
@endsection
