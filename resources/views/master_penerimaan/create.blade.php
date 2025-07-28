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
                 <form action="{{ route('master_penerimaan.store') }}" method="POST">
                    @csrf
                    
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No Penerimaan akan dibuat otomatis dengan format P0001, P0002, dst.
                        </div>
                        <div class="form-group">
                            <label for="id_batch_mp">NO PENERIMAAN</label>
                            <input type="text" name="id_batch_mp" id="id_batch_mp"
                                class="form-control @error('id_batch_mp') is-invalid @enderror"
                                value="{{ old('id_batch_mp', $nextBatchId ?? '') }}" readonly required>
                            @error('id_batch_mp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="cdate">Tanggal</label>
                            <input type="date" name="cdate" id="cdate" class="form-control @error('cdate') is-invalid @enderror" value="{{ old('cdate', date('Y-m-d')) }}" required>
                            @error('cdate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                          <h4 class="mt-4">Detail Penerimaan</h4>
                   <div id="detail-rows">
                        <div class="row border p-2 mb-2 detail-row">
                            {{-- Baris 1 --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_suplier[]">Supplier</label>
                                    <select name="id_suplier[]" class="form-control" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach ($suppliers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_jenis[]">Jenis</label>
                                    <select name="id_jenis[]" class="form-control" required>
                                        <option value="">Pilih Jenis</option>
                                        @foreach ($jenis as $item)
                                            <option value="{{ $item->id_jenis }}">{{ $item->deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_varietas[]">Varietas</label>
                                    <select name="id_varietas[]" class="form-control" required>
                                        <option value="">Pilih Varietas</option>
                                        @foreach ($varietas as $item)
                                            <option value="{{ $item->id_varietas }}">{{ $item->deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_grade[]">Grade</label>
                                    <select name="id_grade[]" class="form-control" required>
                                        <option value="">Pilih Grade</option>
                                        @foreach ($grade as $item)
                                            <option value="{{ $item->id_grade }}">{{ $item->deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Baris 2 --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="jumlah_karung[]">Jumlah Karung</label>
                                    <input type="number" name="jumlah_karung[]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="berat_per_karung[]">Berat/Karung (kg)</label>
                                    <input type="number" step="0.01" name="berat_per_karung[]" class="form-control" required>
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label for="origin[]">Origin</label>
                                    <select name="origin[]" class="form-control" required>
                                        <option value="">Pilih Origin</option>
                                        @foreach ($origin as $item)
                                            <option value="{{ $item->id_origin }}">{{ $item->deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bulk">Bulk</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="bulk_value[]" class="form-control" required placeholder="Value">
                                        <div class="input-group-append">
                                            <select name="bulk_unit[]" class="form-control" required>
                                                <option value="kg">Kilogram (kg)</option>
                                                <option value="liter">Liter (L)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="kadar_air[]">Kadar Air %</label>
                                            <input type="number" step="0.01" name="kadar_air[]" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="size[]">Size</label>
                                            <input type="number"  name="size[]" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="harga_per_kg[]">Harga/Kg</label>
                                            <input type="number"  name="harga_per_kg[]" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="kemasan[]">Kemasan</label>
                                            <input type="number"  name="kemasan[]" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
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
