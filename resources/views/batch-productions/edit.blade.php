@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong class="font-bold">Terjadi Kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-edit fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Batch Production</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Update your batch production details</p>
                            </div>
                        </div>
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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
                                        <a href="{{ route('batch-productions.index') }}" style="color: #79523B;">Batch
                                            Production</a>
                                    </li>
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
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-edit mr-2"></i>Edit Batch Production
                                </h3>
                            </div>
                            <form action="{{ route('batch-productions.update', base64_encode($batch->id)) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <div class="alert alert-warning mx-3 mt-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Anda sedang mengedit batch production dengan No: <strong>{{ $batch->no_batch }}</strong>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_batch">NO Batch</label>
                                                <input type="text" name="no_batch" id="no_batch"
                                                    class="form-control @error('no_batch') is-invalid @enderror"
                                                    value="{{ old('no_batch', $batch->no_batch) }}" readonly required>
                                                @error('no_batch')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Method</label>
                                                <select name="method_id" id="method_select"
                                                    class="form-control @error('method_id') is-invalid @enderror">
                                                    <option value="">Pilih Method</option>
                                                    @foreach ($methods as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('method_id', $batch->id_method) == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('method_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Roast Profile</label>
                                                <select name="roast_profile_id"
                                                    class="form-control @error('roast_profile_id') is-invalid @enderror">
                                                    <option value="">Pilih Roast Profile</option>
                                                    @foreach ($profiles as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('roast_profile_id', $batch->id_roastprofile) == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('roast_profile_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Mesin</label>
                                                <select name="id_mesin"
                                                    class="form-control @error('id_mesin') is-invalid @enderror">
                                                    <option value="">Pilih Mesin</option>
                                                    @foreach ($machines as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('id_mesin', $batch->id_mesin) == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_mesin')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Attention</label>
                                                <select name="attention"
                                                    class="form-control @error('attention') is-invalid @enderror">
                                                    @foreach ($attentions as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('attention', $batch->attention) == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('attention')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product</label>
                                                <select name="id_product"
                                                    class="form-control @error('id_product') is-invalid @enderror">
                                                    <option value="">Pilih Product</option>
                                                    @foreach ($products as $k)
                                                        <option value="{{ $k->id_barang }}"
                                                            {{ old('id_product', $batch->id_product) == $k->id_barang ? 'selected' : '' }}>
                                                            {{ $k->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_product')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Level Roast</label>
                                                <select name="level_roast_id"
                                                    class="form-control @error('level_roast_id') is-invalid @enderror">
                                                    <option value="">Pilih Level Roast</option>
                                                    @foreach ($levels as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('level_roast_id', $batch->id_level_rosting) == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('level_roast_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status"
                                                    class="form-control @error('status') is-invalid @enderror">
                                                    @foreach ($statuses as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('status', $batch->status) == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Berat Diroasting (kg)</label>
                                                <input type="number" step="0.1" name="berat_diroasting" readonly
                                                    id="berat_diroasting"
                                                    class="form-control @error('berat_diroasting') is-invalid @enderror"
                                                    value="{{ old('berat_diroasting', $batch->berat_diroasting) }}">
                                                @error('berat_diroasting')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Estimate Expire Date</label>
                                                <input type="date" name="estimate_expire_date"
                                                    class="form-control @error('estimate_expire_date') is-invalid @enderror"
                                                    value="{{ old('estimate_expire_date', $batch->estimate_expire_date) }}">
                                                @error('estimate_expire_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Catatan</label>
                                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $batch->catatan) }}</textarea>
                                                @error('catatan')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">
                                            <i class="fas fa-list"></i> Detail Batch Production
                                        </h4>
                                        <button type="button" class="btn btn-success btn-sm" id="add-detail">
                                            <i class="fas fa-plus"></i> Tambah Baris
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="detail-table">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th width="25%">Inventory</th>
                                                    <th width="15%">Kadar Air (%)</th>
                                                    <th width="15%">Bulk Densitas</th>
                                                    <th width="15%">Qty Out</th>
                                                    <th width="25%">Catatan</th>
                                                    <th width="5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detail-rows">
                                                @foreach ($details as $index => $detail)
                                                    <tr class="detail-row">
                                                        <input type="hidden" name="detail_ids[]"
                                                            value="{{ $detail->id }}">
                                                        <td>
                                                            <select name="id_inventory[]"
                                                                class="form-control inventory-select" required>
                                                                <option value="">Pilih Inventory</option>
                                                                @foreach ($inventory as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ old('id_inventory.' . $index, $detail->inventory_id) == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->catatan }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input step="0.01" type="number" name="kadar_air[]"
                                                                class="form-control kadar-air-input"
                                                                value="{{ old('kadar_air.' . $index, $detail->kadar_air) }}"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" name="bulk_densitas[]"
                                                                class="form-control bulk-densitas-input"
                                                                value="{{ old('bulk_densitas.' . $index, $detail->bulk_densitas) }}"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" name="qty_out[]"
                                                                class="form-control qty-out-input"
                                                                value="{{ old('qty_out.' . $index, $detail->qty_out) }}"
                                                                required>
                                                        </td>
                                                        <td>
                                                            <textarea name="catatan_detail[]" class="form-control" rows="2">{{ old('catatan_detail.' . $index, $detail->catatan) }}</textarea>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-row">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="bg-light p-3 rounded">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Total Details:</strong> <span
                                                            id="total-details">{{ count($details) }}</span>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        <strong>Total Berat Diroasting:</strong>
                                                        <span id="display-total"
                                                            class="text-success h5">{{ $batch->berat_diroasting }}
                                                            kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('batch-productions.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                        <div>
                                            <button type="button" class="btn btn-warning me-2"
                                                onclick="hitungTotalQtyOut()">
                                                <i class="fas fa-calculator"></i> Hitung Ulang
                                            </button>
                                            <button type="submit" class="btn btn-coffee">
                                                <i class="fas fa-save"></i> Update Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function updateMethodField() {
            const methodSelect = document.getElementById('method_select');
            const detailRows = document.querySelectorAll('.detail-row');

            if (detailRows.length === 1) {
                // Disable <select> dan tambahkan hidden input agar value tetap dikirim
                methodSelect.setAttribute('disabled', true);

                // Tambahkan hidden input jika belum ada
                if (!document.getElementById('method_id_hidden')) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'method_id';
                    hiddenInput.value = methodSelect.value;
                    hiddenInput.id = 'method_id_hidden';
                    methodSelect.parentElement.appendChild(hiddenInput);
                }
            } else {
                // Enable <select> dan hapus hidden input jika ada
                methodSelect.removeAttribute('disabled');

                const hidden = document.getElementById('method_id_hidden');
                if (hidden) hidden.remove();
            }
        }

        function hitungTotalQtyOut() {
            let total = 0;
            document.querySelectorAll('input[name="qty_out[]"]').forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) total += val;
            });

            const totalFormatted = total.toFixed(3);
            document.getElementById('berat_diroasting').value = totalFormatted;
            document.getElementById('display-total').textContent = totalFormatted + ' kg';
        }

        function updateTotalDetails() {
            const totalDetails = document.querySelectorAll('.detail-row').length;
            document.getElementById('total-details').textContent = totalDetails;
        }

        // Saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateMethodField();
            hitungTotalQtyOut();
            updateTotalDetails();

            // Event pada qty_out saat halaman pertama kali dimuat
            document.querySelectorAll('input[name="qty_out[]"]').forEach(input => {
                input.addEventListener('input', hitungTotalQtyOut);
            });

            // Add detail row
            document.getElementById('add-detail').addEventListener('click', function() {
                const container = document.getElementById('detail-rows');
                const firstRow = container.querySelector('.detail-row');
                const newRow = firstRow.cloneNode(true);

                // Reset hidden input untuk detail baru
                newRow.querySelector('input[name="detail_ids[]"]').value = '';

                // Reset semua input dan select
                newRow.querySelectorAll('input:not([name="detail_ids[]"])').forEach(input => input.value =
                    '');
                newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                newRow.querySelectorAll('textarea').forEach(textarea => textarea.value = '');

                container.appendChild(newRow);

                // Tambahkan event ke input qty_out baru
                newRow.querySelector('input[name="qty_out[]"]').addEventListener('input',
                    hitungTotalQtyOut);

                updateMethodField();
                updateTotalDetails();
            });

            // Event delegation untuk tombol hapus
            document.getElementById('detail-rows').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                    const row = e.target.closest('tr');
                    const rows = document.querySelectorAll('.detail-row');
                    if (rows.length > 1) {
                        row.remove();
                        hitungTotalQtyOut();
                        updateMethodField();
                        updateTotalDetails();
                    } else {
                        alert('Minimal harus ada 1 detail batch production!');
                    }
                }
            });
        });
    </script>

@endsection
