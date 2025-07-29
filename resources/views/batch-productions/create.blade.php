@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                @if ($errors->any())
                    <div class="bg-danger border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Terjadi Kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-tags fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Create Batch Production</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Update your product Batch Production details</p>
                            </div>
                        </div>
                        @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
               @endif
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
                                        <a href="{{ route('batch-productions.index') }}" style="color: #79523B;">Batch Production</a>
                                    </li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Create</li>
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
                                <h3 class="card-title">Create Batch Production</h3>
                            </div>
                            <form action="{{route('batch-productions.store')}}" method="POST">
                                @csrf
                                 <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> ID Batch akan dibuat otomatis dengan format P0001, P0002, dst.
                                </div>
                                 
                                
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_batch">NO Batch</label>
                                                <input type="text" name="no_batch" id="no_batch"
                                                    class="form-control @error('no_batch') is-invalid @enderror"
                                                    value="{{ old('no_batch', $nextBatchId ?? '') }}" readonly required>
                                                @error('no_batch')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                
                                            <div class="form-group">
                                                <label>Mesin</label>
                                                <select name="id_mesin" class="form-control">
                                                    @foreach($machines as $k => $v)
                                                        <option value="{{ $k }}" {{ (old('id_mesin', $batch->mesin_id ?? '') == $k) ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Method</label>
                                                <select name="method_id" class="form-control">
                                                    @foreach($methods as $k => $v)
                                                        <option value="{{ $k }}" {{ (old('method_id', $batch->method_id ?? '') == $k) ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Roast Profile</label>
                                                <select name="roast_profile_id" class="form-control">
                                                    @foreach($profiles as $k => $v)
                                                        <option value="{{ $k }}" {{ (old('roast_profile_id', $batch->roast_profile_id ?? '') == $k) ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Level Roast</label>
                                                <select name="level_roast_id" class="form-control">
                                                    @foreach($levels as $k => $v)
                                                        <option value="{{ $k }}" {{ (old('level_roast_id', $batch->level_roast_id ?? '') == $k) ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                    <label>Berat Diroasting (kg)</label>
                                                    <input
                                                        type="number"
                                                        step="0.001"
                                                        name="berat_diroasting"
                                                        class="form-control"
                                                        value="{{ old('berat_diroasting', $batch->berat_diroasting ?? '') }}"
                                                    >
                                            </div>

                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    @foreach($statuses as $k => $v)
                                                        <option value="{{ $k }}" {{ (old('status', $batch->status ?? '') == $k) ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Attention</label>
                                                <select name="attention" class="form-control">
                                                    @foreach($attentions as $k => $v)
                                                        <option value="{{ $k }}" {{ (old('attention', $batch->attention ?? '') == $k) ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <label>Estimate Expire Date</label>
                                                <input
                                                    type="date"
                                                    name="estimate_expire_date"
                                                    class="form-control"
                                                    value="{{ old('estimate_expire_date', $batch->estimate_expire_date ?? '') }}"
                                                >
                                            </div>

                                            <div class="form-group">
                                                <label>Catatan</label>
                                                <textarea name="catatan" class="form-control">{{ old('catatan', $batch->catatan ?? '') }}</textarea>
                                            </div>

                                        </div>
                                    </div>

                                     <h4 class="mt-4">Detail Batch Production</h4>
                                            <div id="detail-rows">
                                                    <div class="row border p-2 mb-2 detail-row">
                                                        {{-- Baris 1 --}}
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="id_inventory[]">Inventory</label>
                                                                <select name="id_inventory[]" class="form-control inventory-select" required>
                                                                    <option value="">Pilih Inventory</option>
                                                                    @foreach ($inventory as $item)
                                                                        <option 
                                                                            value="{{ $item->id }}"
                                                                            data-kadar-air="{{ $item->kadar_air }}"
                                                                            data-bulk-densitas="{{ $item->bulk_densitas }}"
                                                                        >
                                                                            {{ $item->catatan }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        

                                                        {{-- Baris 2 --}}
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="kadar_air[]">Kadar Air</label>
                                                                <input step="0.01" type="number" name="kadar_air[]" class="form-control kadar-air-input" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="bulk_densitas[]">Bulk Densitas</label>
                                                                <input type="number" step="0.01" name="bulk_densitas[]" class="form-control bulk-densitas-input" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="qty_out[]">Qty Out</label>
                                                                <input type="number" step="0.01" name="qty_out[]" class="form-control" required>
                                                            </div>
                                                        </div>

                                                       <div class="col-md-4">
                                                            <label for="catatan">Catatan</label>
                                                            <div class="form-group">
                                                            
                                                                <textarea name="catatan_detail[]" class="form-control"></textarea>
                                                            </div>
                                                               
                                                                
                                                            </div>
                                                        </div>
                                                      </div>

                                                      <div class="col-md-4">
                                                                <button type="button" class="btn btn-primary" id="add-detail">+ Tambah Baris</button>
                                                        </div>
                                                    </div>

                                                        
                                            </div>
                                   
                                    

                                </div>
                                <div class="card-footer">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        onclick="window.history.back();"
                                    >
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-coffee">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.inventory-select').forEach(function (selectElement) {
            selectElement.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];

                const kadarAir = selectedOption.getAttribute('data-kadar-air');
                const bulkDensitas = selectedOption.getAttribute('data-bulk-densitas');

                const detailRow = this.closest('.detail-row');

                const kadarAirInput = detailRow.querySelector('.kadar-air-input');
                const bulkDensitasInput = detailRow.querySelector('.bulk-densitas-input');

                if (kadarAirInput) kadarAirInput.value = kadarAir || '';
                if (bulkDensitasInput) bulkDensitasInput.value = bulkDensitas || '';
            });
        });
    });
</script>


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
