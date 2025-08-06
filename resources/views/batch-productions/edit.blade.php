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
                                                    value="{{ old('estimate_expire_date', \Carbon\Carbon::parse($batch->estimate_expire_date)->format('Y-m-d')) }}">
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

                                                            <!-- Hidden input untuk menyimpan nilai yang dipilih -->
                                                            <input type="hidden" name="id_inventory[]"
                                                                class="inventory-id" value="{{ $detail->inventory_id }}"
                                                                required>

                                                            <!-- Input untuk menampilkan teks yang dipilih -->
                                                            <input type="text" class="form-control inventory-display"
                                                                placeholder="Pilih Inventory" readonly data-toggle="modal"
                                                                data-target="#inventoryModal"
                                                                value="{{ $detail->catatan }}">

                                                            <input type="text"
                                                                class="form-control detail-penerimaan-display"
                                                                placeholder="Detail Penerimaan terpilih" readonly
                                                                value="{{ $detail->id_batch }} - {{ $detail->jenis_kemasan }}">
                                                            <input type="hidden" name="id_detail_penerimaan[]"
                                                                class="detail-penerimaan-id"
                                                                value="{{ $detail->id_detail_penerimaan }}" required>

                                                            <!-- Modal -->





                                                        </td>

                                                        <!-- CSS tambahan -->
                                                        <style>
                                                            .inventory-display {
                                                                background-color: #fff;
                                                                cursor: pointer;
                                                            }
                                                        </style>
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


    <div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="inventoryModalLabel" class="modal-title">Pilih
                        Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search Box -->
                    <div class="form-group">
                        <input type="text" class="form-control" id="inventorySearch" placeholder="Cari inventory...">
                    </div>

                    <!-- Daftar Inventory -->
                    <div class="table-responsive">
                        <table class="datatable table table-hover">
                            <thead>
                                <tr>
                                    <th>Catatan</th>
                                    <th>No Inventory</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryList">
                                @foreach ($inventory as $item)
                                    <tr>
                                        <td>{{ $item->catatan }}</td>
                                        <td>{{ $item->no_inventory }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary select-inventory"
                                                data-id="{{ $item->id }}" data-catatan="{{ $item->catatan }}">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Detail Penerimaan -->
    <div class="modal fade" id="detailPenerimaanModal" tabindex="-1" role="dialog"
        aria-labelledby="detailPenerimaanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Detail Penerimaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Daftar Detail Penerimaan -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No Batch</th>
                                    <th>Jenis Kemasan</th>
                                    <th>Kadar Air</th>
                                    <th>Densitas</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="detailPenerimaanList">
                                <!-- Diisi dinamis lewat JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });

            // Pencarian Inventory
            $('#inventorySearch').keyup(function() {
                var value = $(this).val().toLowerCase();
                $('#inventoryList tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            let inventoryData =
                @json($inventory); // Semua data inventory (dengan relasi detail_penerimaan)

            let currentRow = null;

            $(document).on('click', '.inventory-display', function() {
                currentRow = $(this).closest('tr');
            })

            // Saat tombol "Pilih" inventory ditekan
            $(document).on('click', '.select-inventory', function() {
                let id = $(this).data('id');
                let catatan = $(this).data('catatan');

                // Isi hanya di baris yang aktif
                currentRow.find('.inventory-display').val(catatan);
                currentRow.find('.inventory-id').val(id);

                let detailList = inventoryData.filter(i => i.id == id);
                let html = '';

                detailList.forEach(item => {
                    html += `
            <tr>
                <td>${item.id_batch}</td>
                <td>${item.jenis_kemasan}</td>
                <td>${item.kadar_air}</td>
                <td>${item.jumlah}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-success select-detail"
                        data-detail-id="${item.id_detail_penerimaan}"
                        data-detail-label="${item.id_batch} - ${item.jenis_kemasan}">
                        Pilih
                    </button>
                </td>
            </tr>
        `;
                });

                $('#detailPenerimaanList').html(html);

                $('#inventoryModal').modal('hide');
                $('#detailPenerimaanModal').modal('show');
            });


            // Saat pilih detail_penerimaan
            $(document).on('click', '.select-detail', function() {
                let detailId = $(this).data('detail-id');
                let detailLabel = $(this).data('detail-label');

                currentRow.find('.detail-penerimaan-id').val(detailId);
                currentRow.find('.detail-penerimaan-display').val(detailLabel);

                $('#detailPenerimaanModal').modal('hide');
            });

        });
    </script>
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
                const existingRows = container.querySelectorAll('.detail-row');

                // Ambil row template atau clone dari row pertama
                let templateRow;
                if (existingRows.length > 0) {
                    templateRow = existingRows[0].cloneNode(true);
                } else {
                    // Jika tidak ada row, buat row baru
                    templateRow = createNewRow();
                }

                // Reset hidden input untuk detail baru (set ke empty string agar dianggap sebagai new record)
                const hiddenInput = templateRow.querySelector('input[name="detail_ids[]"]');
                if (hiddenInput) {
                    hiddenInput.value = '';
                } else {
                    // Jika tidak ada hidden input, buat yang baru
                    const newHiddenInput = document.createElement('input');
                    newHiddenInput.type = 'hidden';
                    newHiddenInput.name = 'detail_ids[]';
                    newHiddenInput.value = '';
                    templateRow.insertBefore(newHiddenInput, templateRow.firstChild);
                }

                // Reset semua input dan select
                templateRow.querySelectorAll('input:not([name="detail_ids[]"])').forEach(input => {
                    input.value = '';
                });
                templateRow.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });
                templateRow.querySelectorAll('textarea').forEach(textarea => {
                    textarea.value = '';
                });

                container.appendChild(templateRow);

                // Tambahkan event ke input qty_out baru
                const qtyOutInput = templateRow.querySelector('input[name="qty_out[]"]');
                if (qtyOutInput) {
                    qtyOutInput.addEventListener('input', hitungTotalQtyOut);
                }

                // Tambahkan event untuk inventory select baru jika ada fungsi auto-fill
                const inventorySelect = templateRow.querySelector('select[name="id_inventory[]"]');
                if (inventorySelect && typeof setupInventorySelectEvent === 'function') {
                    setupInventorySelectEvent(inventorySelect);
                }

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

            // Setup form submission dengan validasi
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const rows = document.querySelectorAll('.detail-row');
                    if (rows.length === 0) {
                        e.preventDefault();
                        alert('Minimal harus ada 1 detail batch production!');
                        return false;
                    }

                    // Validasi setiap row
                    let hasError = false;
                    rows.forEach((row, index) => {
                        const inventorySelect = row.querySelector('select[name="id_inventory[]"]');
                        const kadarAirInput = row.querySelector('input[name="kadar_air[]"]');
                        const bulkDensitasInput = row.querySelector(
                            'input[name="bulk_densitas[]"]');
                        const qtyOutInput = row.querySelector('input[name="qty_out[]"]');

                        if (!inventorySelect.value) {
                            hasError = true;
                            inventorySelect.focus();
                            alert(`Baris ${index + 1}: Inventory harus dipilih`);
                            return;
                        }

                        if (!kadarAirInput.value || parseFloat(kadarAirInput.value) < 0) {
                            hasError = true;
                            kadarAirInput.focus();
                            alert(
                                `Baris ${index + 1}: Kadar Air harus diisi dengan nilai yang valid`
                            );
                            return;
                        }

                        if (!bulkDensitasInput.value || parseFloat(bulkDensitasInput.value) < 0) {
                            hasError = true;
                            bulkDensitasInput.focus();
                            alert(
                                `Baris ${index + 1}: Bulk Densitas harus diisi dengan nilai yang valid`
                            );
                            return;
                        }

                        if (!qtyOutInput.value || parseFloat(qtyOutInput.value) <= 0) {
                            hasError = true;
                            qtyOutInput.focus();
                            alert(
                                `Baris ${index + 1}: Qty Out harus diisi dengan nilai yang lebih dari 0`
                            );
                            return;
                        }
                    });

                    if (hasError) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });

        // Fungsi untuk membuat row baru jika tidak ada template
        function createNewRow() {
            const tr = document.createElement('tr');
            tr.className = 'detail-row';

            tr.innerHTML = `
                <input type="hidden" name="detail_ids[]" value="">
               <td>

                                                            <!-- Hidden input untuk menyimpan nilai yang dipilih -->
                                                            <input type="hidden" name="id_inventory[]"
                                                                class="inventory-id"
                                                                required>

                                                            <!-- Input untuk menampilkan teks yang dipilih -->
                                                            <input type="text" class="form-control inventory-display"
                                                                placeholder="Pilih Inventory" readonly data-toggle="modal"
                                                                data-target="#inventoryModal"
                                                                >

                                                            <input type="text"
                                                                class="form-control detail-penerimaan-display"
                                                                placeholder="Detail Penerimaan terpilih" readonly
                                                               >
                                                            <input type="hidden" name="id_detail_penerimaan[]"
                                                                class="detail-penerimaan-id"
                                                               required>

                                                            <!-- Modal -->





                                                        </td>
                <td>
                    <input step="0.01" type="number" name="kadar_air[]" class="form-control kadar-air-input" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="bulk_densitas[]" class="form-control bulk-densitas-input" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="qty_out[]" class="form-control qty-out-input" required>
                </td>
                <td>
                    <textarea name="catatan_detail[]" class="form-control" rows="2"></textarea>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            return tr;
        }

        // Fungsi untuk setup event pada inventory select (opsional)
        function setupInventorySelectEvent(selectElement) {
            // Implementasi auto-fill jika diperlukan
            selectElement.addEventListener('change', function() {
                const selectedValue = this.value;
                const row = this.closest('tr');

                // Contoh: auto-fill kadar air dan bulk densitas berdasarkan inventory yang dipilih
                // Implementasi sesuai kebutuhan
                console.log('Inventory selected:', selectedValue);
            });
        }
    </script>

@endsection
