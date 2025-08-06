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
                                        <a href="{{ route('batch-productions.index') }}" style="color: #79523B;">Batch
                                            Production</a>
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
                            <form action="{{ route('batch-productions.store') }}" method="POST">
                                @csrf
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> ID Batch akan dibuat otomatis dengan format P0001,
                                    P0002, dst.
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
                                                <label>Method</label>
                                                <select name="method_id" id="method_select" class="form-control">
                                                    @foreach ($methods as $k)
                                                        <option value="{{ $k->id }}"
                                                            {{ old('method_id', $batch->method_id ?? '') == $k->id ? 'selected' : '' }}>
                                                            {{ $k->deskripsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>



                                            <div class="form-group">
                                                <label>Roast Profile</label>
                                                <select name="roast_profile_id" class="form-control">
                                                    @foreach ($profiles as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('roast_profile_id', $batch->roast_profile_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Mesin</label>
                                                <select name="id_mesin" class="form-control">
                                                    @foreach ($machines as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('id_mesin', $batch->mesin_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Attention</label>
                                                <select name="attention" class="form-control">
                                                    @foreach ($attentions as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('attention', $batch->attention ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product</label>
                                                <select name="id_product" class="form-control">
                                                    @foreach ($products as $k)
                                                        <option value="{{ $k->id_barang }}"
                                                            {{ old('id_product', $batch->id_product ?? '') == $k->id_barang ? 'selected' : '' }}>
                                                            {{ $k->nama_barang }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Level Roast</label>
                                                <select name="level_roast_id" class="form-control">
                                                    @foreach ($levels as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('level_roast_id', $batch->level_roast_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="open" readonly>Open</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Berat Diroasting (kg)</label>
                                                <input type="number" step="0.1" name="berat_diroasting" readonly
                                                    id="berat_diroasting" class="form-control"
                                                    value="{{ old('berat_diroasting', $batch->berat_diroasting ?? '') }}">
                                            </div>






                                            <div class="form-group">
                                                <label>Estimate Expire Date</label>
                                                <input type="date" name="estimate_expire_date" class="form-control"
                                                    value="{{ old('estimate_expire_date', $batch->estimate_expire_date ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Catatan</label>
                                                <textarea name="catatan" class="form-control">{{ old('catatan', $batch->catatan ?? '') }}</textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <h4 class="mt-4">Detail Batch Production</h4>

                                    <table class="table table-bordered" id="detail-rows">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Inventory</th>
                                                <th>Kadar Air (%)</th>
                                                <th>Bulk Densitas</th>
                                                <th>Qty Out</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="detail-row">
                                                <td>

                                                    <!-- Hidden input untuk menyimpan nilai yang dipilih -->
                                                    <input type="hidden" name="id_inventory[]" class="inventory-id"
                                                        required>

                                                    <!-- Input untuk menampilkan teks yang dipilih -->
                                                    <input type="text" class="form-control inventory-display"
                                                        placeholder="Pilih Inventory" readonly data-toggle="modal"
                                                        data-target="#inventoryModal">

                                                    <input type="text" class="form-control detail-penerimaan-display"
                                                        placeholder="Detail Penerimaan terpilih" readonly>
                                                    <input type="hidden" name="id_detail_penerimaan[]"
                                                        class="detail-penerimaan-id">

                                                    <!-- Modal -->





                                                </td>

                                                <!-- CSS tambahan -->
                                                <style>
                                                    .inventory-display {
                                                        background-color: #fff;
                                                        cursor: pointer;
                                                    }
                                                </style>

                                                <!-- JavaScript untuk fungsi pencarian dan pemilihan -->

                                                <td>
                                                    <input step="0.01" type="number" name="kadar_air[]"
                                                        class="form-control kadar-air-input" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="bulk_densitas[]"
                                                        class="form-control bulk-densitas-input" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="qty_out[]"
                                                        class="form-control" required>
                                                </td>
                                                <td>
                                                    <textarea name="catatan_detail[]" class="form-control"></textarea>
                                                </td>
                                            </tr>
                                        </tbody>


                                    </table>
                                    <button type="button" class="btn btn-sm btn-success mt-2" id="add-detail">+ Tambah
                                        Baris</button>







                                </div>


                        </div>



                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-coffee">Simpan</button>
                    </div>
                    </form>
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
        // Saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateMethodField();

            // Saat klik tambah baris detail
            document.getElementById('add-detail').addEventListener('click', function() {
                setTimeout(updateMethodField,
                    100); // Delay sedikit untuk memastikan elemen baru sudah ditambahkan
            });
        });
    </script>

    <script>
        function hitungTotalQtyOut() {
            let total = 0;
            document.querySelectorAll('input[name="qty_out[]"]').forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) total += val;
            });
            document.getElementById('berat_diroasting').value = total.toFixed(3);
        }

        document.getElementById('add-detail').addEventListener('click', function() {
            const container = document.getElementById('detail-rows');
            const firstRow = container.querySelector('.detail-row');
            const newRow = firstRow.cloneNode(true);

            // Reset semua input dan select
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            container.appendChild(newRow);

            // Tambahkan event ke input qty_out baru
            newRow.querySelector('input[name="qty_out[]"]').addEventListener('input', hitungTotalQtyOut);
        });

        // Event pada qty_out saat halaman pertama kali dimuat
        document.querySelectorAll('input[name="qty_out[]"]').forEach(input => {
            input.addEventListener('input', hitungTotalQtyOut);
        });
    </script>

@endsection
