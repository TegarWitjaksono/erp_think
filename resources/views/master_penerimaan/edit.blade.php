@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        {{-- Header & Breadcrumbs --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Penerimaan Bahan Baku</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('master_penerimaan.index') }}">Master
                                    Penerimaan</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Validation Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header text-white">
                        <i class="fas fa-edit me-1"></i>
                        EDIT PENERIMAAN BAHAN BAKU
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_penerimaan.update.new', $masterPenerimaan->id_penerimaan) }}"
                            method="POST">
                            @method('PUT')
                            @csrf

                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Anda sedang mengedit data penerimaan dengan No:
                                <strong>{{ $masterPenerimaan->id_batch_mp }}</strong>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_batch_mp">NO PENERIMAAN</label>
                                        <input type="text" name="id_batch_mp" id="id_batch_mp"
                                            class="form-control @error('id_batch_mp') is-invalid @enderror"
                                            value="{{ old('id_batch_mp', $masterPenerimaan->id_batch_mp ?? '') }}" readonly
                                            required>
                                        @error('id_batch_mp')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cdate">TANGGAL</label>
                                        <input type="date" name="cdate" id="cdate"
                                            class="form-control @error('cdate') is-invalid @enderror"
                                            value="{{ old('cdate', isset($masterPenerimaan->cdate) ? \Carbon\Carbon::parse($masterPenerimaan->cdate)->format('Y-m-d') : '') }}"
                                            required>
                                        @error('cdate')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_suplier">SUPPLIER</label>
                                        <select name="id_suplier"
                                            class="form-control @error('id_suplier') is-invalid @enderror" required>
                                            <option value="">Pilih Supplier</option>
                                            @foreach ($suppliers as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('id_suplier', $masterPenerimaan->id_supplier) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_suplier')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_po">NO PO</label>
                                        <input type="text" name="no_po" id="no_po"
                                            class="form-control @error('no_po') is-invalid @enderror"
                                            value="{{ old('no_po', $masterPenerimaan->no_po) }}" required>
                                        @error('no_po')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_do">NO DO</label>
                                        <input type="text" name="no_do" id="no_do"
                                            class="form-control @error('no_do') is-invalid @enderror"
                                            value="{{ old('no_do', $masterPenerimaan->no_do) }}" required>
                                        @error('no_do')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_invoice">NO INVOICE</label>
                                        <input type="text" name="no_invoice" id="no_invoice"
                                            class="form-control @error('no_invoice') is-invalid @enderror"
                                            value="{{ old('no_invoice', $masterPenerimaan->no_invoice) }}" required>
                                        @error('no_invoice')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jumlah_tagihan">JUMLAH TAGIHAN</label>
                                        <input type="text" name="jumlah_tagihan" id="jumlah_tagihan"
                                            class="form-control @error('jumlah_tagihan') is-invalid @enderror"
                                            value="{{ old('jumlah_tagihan', $masterPenerimaan->jum_tagihan) }}" required
                                            readonly>
                                        @error('jumlah_tagihan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biaya-lain">BIAYA LAIN - LAIN</label>
                                        <input type="text" name="biaya-lain" id="biaya-lain"
                                            class="form-control @error('biaya-lain') is-invalid @enderror"
                                            value="{{ old('biaya-lain', $masterPenerimaan->biaya_lain) }}" required>
                                        @error('biaya-lain')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                    rows="3" required>{{ old('keterangan', $masterPenerimaan->keterangan) }}</textarea>
                                @error('keterangan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h4 class="mt-4">
                                <i class="fas fa-list"></i> Detail Penerimaan
                                <button type="button" id="add-detail" class="btn btn-success btn-sm float-end">
                                    <i class="fas fa-plus"></i> Tambah Detail
                                </button>
                            </h4>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="detail-table">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>NO BATCH</th>
                                            <th>Jenis</th>
                                            <th>Varietas</th>
                                            <th>Grade</th>
                                            <th>Densitas</th>
                                            <th>Kadar Air (%)</th>
                                            <th>Size</th>
                                            <th>Jumlah Karung</th>
                                            <th>Proses</th>
                                            <th>Berat Diterima</th>
                                            <th>Package Size</th>
                                            <th>Origin</th>
                                            <th>Harga/Kg</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-rows">
                                        @foreach ($details as $index => $detail)
                                            <tr class="detail-row align-middle">
                                                <input type="hidden" name="detail_ids[]"
                                                    value="{{ $detail->id_detail_penerimaan }}">

                                                <td class="p-1" style="min-width: 150px;">
                                                    <input type="text" name="no_batch[]" class="form-control"
                                                        value="{{ old('no_batch.' . $index, $detail->no_batch) }}"
                                                        required>
                                                </td>

                                                <td class="p-1" style="min-width: 150px;">
                                                    <select name="id_jenis[]" class="form-control w-100" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($jenis as $item)
                                                            <option value="{{ $item->id_jenis }}"
                                                                {{ old('id_jenis.' . $index, $detail->id_jenis) == $item->id_jenis ? 'selected' : '' }}>
                                                                {{ $item->deskripsi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1" style="min-width: 150px;">
                                                    <select name="id_varietas[]" class="form-control w-100" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($varietas as $item)
                                                            <option value="{{ $item->id_varietas }}"
                                                                {{ old('id_varietas.' . $index, $detail->id_varietas) == $item->id_varietas ? 'selected' : '' }}>
                                                                {{ $item->deskripsi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1" style="min-width: 150px;">
                                                    <select name="id_grade[]" class="form-control w-100" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($grade as $item)
                                                            <option value="{{ $item->id_grade }}"
                                                                {{ old('id_grade.' . $index, $detail->id_grade) == $item->id_grade ? 'selected' : '' }}>
                                                                {{ $item->deskripsi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1" style="min-width: 180px;">
                                                    @php
                                                        $bulkParts = explode(' ', $detail->bulk);
                                                        $bulkValue = $bulkParts[0] ?? '';
                                                        $bulkUnit = $bulkParts[1] ?? 'kg';
                                                    @endphp
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" name="bulk_value[]"
                                                            class="form-control" required
                                                            value="{{ old('bulk_value.' . $index, $bulkValue) }}"
                                                            placeholder="Value">
                                                        <select name="bulk_unit[]" class="form-control" required>
                                                            <option value="kg"
                                                                {{ old('bulk_unit.' . $index, $bulkUnit) == 'kg' ? 'selected' : '' }}>
                                                                kg</option>
                                                            <option value="liter"
                                                                {{ old('bulk_unit.' . $index, $bulkUnit) == 'liter' ? 'selected' : '' }}>
                                                                liter</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td class="p-1">
                                                    <input type="number" step="0.01" name="kadar_air[]"
                                                        class="form-control"
                                                        value="{{ old('kadar_air.' . $index, $detail->kadar_air) }}"
                                                        required>
                                                </td>

                                                <td class="p-1">
                                                    <input type="number" name="size[]" class="form-control"
                                                        value="{{ old('size.' . $index, $detail->size) }}" required>
                                                </td>

                                                <td class="p-1">
                                                    <input type="number" name="jumlah_karung[]" class="form-control"
                                                        value="{{ old('jumlah_karung.' . $index, $detail->jumlah) }}"
                                                        required>
                                                </td>

                                                <td class="p-1" style="min-width: 150px;">
                                                    <select name="proses[]" class="form-control w-100" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($proses as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ old('proses.' . $index, $detail->id_proses) == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama_proses }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1">
                                                    <input type="number" step="0.01" name="berat_per_karung[]"
                                                        class="form-control berat" required
                                                        value="{{ old('berat_per_karung.' . $index, $detail->berat) }}">
                                                </td>

                                                <td class="p-1" style="min-width: 150px;">
                                                    <select name="p_size[]" class="form-control w-100" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($package as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ old('p_size.' . $index, $detail->id_p_size) == $item->id ? 'selected' : '' }}>
                                                                {{ $item->deskripsi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1" style="min-width: 150px;">
                                                    <select name="origin[]" class="form-control w-100" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($origin as $item)
                                                            <option value="{{ $item->id_origin }}"
                                                                {{ old('origin.' . $index, $detail->id_origin) == $item->id_origin ? 'selected' : '' }}>
                                                                {{ $item->deskripsi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1">
                                                    <input type="number" step="0.01" name="harga_per_kg[]"
                                                        class="form-control harga" required
                                                        value="{{ old('harga_per_kg.' . $index, $detail->harga_per_kg) }}">
                                                </td>

                                                <td class="p-1 text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row">
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
                                                <strong>Total Items:</strong> <span
                                                    id="total-items">{{ count($details) }}</span>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <strong>Total Tagihan:</strong> <span id="display-total"
                                                    class="text-success h5">{{ $masterPenerimaan->jum_tagihan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer mt-4">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('master_penerimaan.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-warning me-2"
                                            onclick="hitungJumlahTagihan()">
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
        </section>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initial calculation
            hitungJumlahTagihan();
            updateTotalItems();
        });

        function hitungJumlahTagihan() {
            let total = 0;

            // Ambil semua pasangan harga dan berat
            const hargaInputs = document.querySelectorAll('.harga');
            const beratInputs = document.querySelectorAll('.berat');

            hargaInputs.forEach((hargaInput, index) => {
                const harga = parseFloat(hargaInput.value) || 0;
                const berat = parseFloat(beratInputs[index].value) || 0;
                total += harga * berat;
            });

            // Tampilkan ke field jumlah_tagihan dan display
            const formattedTotal = total.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });

            document.getElementById('jumlah_tagihan').value = formattedTotal;
            document.getElementById('display-total').textContent = formattedTotal;
        }

        function updateTotalItems() {
            const totalItems = document.querySelectorAll('.detail-row').length;
            document.getElementById('total-items').textContent = totalItems;
        }

        // Event listener untuk semua input harga/berat
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('harga') || e.target.classList.contains('berat')) {
                hitungJumlahTagihan();
            }
        });

        // Add detail row
        document.getElementById('add-detail').addEventListener('click', function() {
            const container = document.getElementById('detail-rows');
            const firstRow = container.querySelector('.detail-row');
            const newRow = firstRow.cloneNode(true);

            // Reset hidden input untuk detail baru
            newRow.querySelector('input[name="detail_ids[]"]').value = '';

            // Kosongkan semua input dan select
            newRow.querySelectorAll('input:not([name="detail_ids[]"])').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            container.appendChild(newRow);
            updateTotalItems();
        });

        // Event delegation untuk tombol hapus
        document.getElementById('detail-rows').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                const row = e.target.closest('tr');
                const rows = document.querySelectorAll('.detail-row');
                if (rows.length > 1) {
                    row.remove();
                    hitungJumlahTagihan();
                    updateTotalItems();
                } else {
                    alert('Minimal harus ada 1 detail penerimaan!');
                }
            }
        });
    </script>

@endsection
