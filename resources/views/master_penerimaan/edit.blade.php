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
                                            <th>No Batch Supplier</th>
                                            <th>Aksi</th>
                                            <th>Jenis</th>
                                            <th>Origin</th>
                                            <th>Varietas</th>
                                            <th>Proses</th>
                                            <th>Grade</th>
                                            <th>Densitas(g/l)</th>
                                            <th>KA(%)</th>
                                            <th>Size</th>
                                            <th>Berat Diterima</th>
                                            <th>Jumlah Karung</th>


                                            <th>Package Size (gr)</th>

                                            <th>Harga</th>

                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-rows">
                                        @foreach ($details as $index => $detail)
                                            <tr class="detail-row align-middle">
                                                <input type="hidden" name="detail_ids[]"
                                                    value="{{ $detail->id_detail_penerimaan }}">
                                                <input type="hidden" name="id_rm[]" class="id_rm"
                                                    value={{ old('id_rm.' . $index, $detail->id_rm) }}>

                                                <td class="p-1" style="min-width: 150px;">
                                                    <input type="text" name="no_batch[]" class="form-control"
                                                        value="{{ old('no_batch.' . $index, $detail->no_batch) }}"
                                                        required>
                                                </td>
                                                <td class="p-1">
                                                    <button type="button" class="btn btn-secondary btn-cari"
                                                        data-toggle="modal" data-target="#cariModal">
                                                        Cari
                                                    </button>
                                                </td>


                                                <td class="p-1" style="min-width: 120px;">
                                                    <select name="id_jenis[]" class="form-control w-100 id_jenis"
                                                        required>
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
                                                    <select name="origin[]" class="form-control w-100 id_origin" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($origin as $item)
                                                            <option value="{{ $item->id_origin }}"
                                                                {{ old('origin.' . $index, $detail->id_origin) == $item->id_origin ? 'selected' : '' }}>
                                                                {{ $item->deskripsi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="p-1" style="min-width: 120px;">
                                                    <select name="id_varietas[]" class="form-control w-100 id_varietas"
                                                        required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($varietas as $item)
                                                            <option value="{{ $item->id_varietas }}"
                                                                {{ old('id_varietas.' . $index, $detail->id_varietas) == $item->id_varietas ? 'selected' : '' }}>
                                                                {{ $item->deskripsi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="p-1" style="min-width: 120px;">
                                                    <select name="proses[]" class="form-control w-100 proses" required>
                                                        <option value="">Pilih</option>
                                                        @foreach ($proses as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ old('proses.' . $index, $detail->id_proses) == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama_proses }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>




                                                <td class="p-1" style="min-width: 120px;">
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

                                                <td class="p-1" style="min-width: 100px;">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" name="bulk_value[]"
                                                            class="form-control" required
                                                            placeholder="Value dalam bentuk gram"
                                                            value="{{ old('bulk_value.' . $index, $detail->bulk) }}">
                                                    </div>
                                                </td>

                                                <td class="p-1" style="min-width: 90px ;">
                                                    <input type="number" step="0.01" name="kadar_air[]"
                                                        class="form-control"
                                                        value="{{ old('kadar_air.' . $index, $detail->kadar_air) }}"
                                                        required>
                                                </td>

                                                <td class="p-1" style="min-width: 90px">
                                                    <input type="number" name="size[]" class="form-control"
                                                        value="{{ old('size.' . $index, $detail->size) }}" required>
                                                </td>

                                                <td class="p-1" style="min-width: 90px">
                                                    <input type="number" step="0.01" name="berat_per_karung[]"
                                                        class="form-control berat" required
                                                        value="{{ old('berat_per_karung.' . $index, $detail->berat) }}">
                                                </td>

                                                <td class="p-1">
                                                    <input type="number" name="jumlah_karung[]" class="form-control"
                                                        value="{{ old('jumlah_karung.' . $index, $detail->jumlah) }}"
                                                        required>
                                                </td>





                                                <td class="p-1" style="min-width: 120px;">
                                                    <input type="number" name="p_size[]" class="form-control"
                                                        value="{{ old('jumlah_karung.' . $index, $detail->p_size) }}"
                                                        required>
                                                </td>



                                                <td class="p-1" style="min-width: 120px;">
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

    <div class="modal fade" id="cariModal" tabindex="-1" role="dialog" aria-labelledby="cariModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cariModalLabel">Pencarian Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="products-table" class="table datatable table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Origin</th>
                                    <th>Jenis</th>
                                    <th>Varietas</th>
                                    <th>Proses</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($data as $rm)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $rm->nama ?? 'Belum ada' }}</td>
                                        <td>{{ $rm->origin_desc ?? 'Belum ada' }}</td>
                                        <td>{{ $rm->jenis_desc ?? 'Belum ada' }}</td>
                                        <td>{{ $rm->varietas_desc }}</td>
                                        <td>{{ $rm->nama_proses }}</td>
                                        <!-- Modify the actions column to include a detail button -->
                                        <td>

                                            <button type="button" class="btn btn-sm btn-primary pilihRM"
                                                data-id="{{ $rm->id }}" data-jenis="{{ $rm->id_jenis }}"
                                                data-origin="{{ $rm->id_origin }}"
                                                data-varietas="{{ $rm->id_varietas }}"
                                                data-proses="{{ $rm->id_proses }}">
                                                Select
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

    <script>
        // Klik tombol Select di modal
        $(document).on("click", ".pilihRM", function() {
            let id = $(this).data("id"); // id RM dari modal
            let jenis = $(this).data("jenis");
            let origin = $(this).data("origin");
            let varietas = $(this).data("varietas");
            let proses = $(this).data("proses");

            // cari row aktif
            let row = $("#detail-rows .detail-row.active-row");

            // isi field di row itu
            row.find(".id_rm").val(id);
            row.find(".id_jenis").val(jenis);
            row.find(".id_origin").val(origin);
            row.find(".id_varietas").val(varietas);
            row.find(".proses").val(proses);

            // tutup modal
            $("#cariModal").modal("hide");
        });

        // saat tombol Cari ditekan, tandai row mana yang sedang aktif
        $(document).on("click", ".btn-cari", function() {
            $("#detail-rows .detail-row").removeClass("active-row");
            $(this).closest("tr").addClass("active-row");
        });
    </script>

@endsection
