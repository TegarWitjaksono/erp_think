@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        {{-- Header & Breadcrumbs (sama seperti sebelumnya) --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <!-- ... -->
        </div>

        <section class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
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
                <div class="card shadow-sm">
                    <div class="card-header text-white">
                        <i class="fas fa-table me-1"></i>
                        TAMBAH PENERIMAAN BAHAN BAKU
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_penerimaan.store.new') }}" method="POST">
                            @csrf

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No Penerimaan akan dibuat otomatis dengan format P0001,
                                P0002, dst.
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
                                <label for="cdate">TANGGAL</label>
                                <input type="date" name="cdate" id="cdate"
                                    class="form-control @error('cdate') is-invalid @enderror"
                                    value="{{ old('cdate', date('Y-m-d')) }}" required>
                                @error('cdate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="id_suplier">SUPPLIER</label>
                                <select name="id_suplier" class="form-control" required>
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($suppliers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="no_po">NO PO</label>
                                <input type="text" name="no_po" id="no_po"
                                    class="form-control @error('no_po') is-invalid @enderror" value="{{ old('no_po') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="no_do">NO DO</label>
                                <input type="text" name="no_do" id="no_do"
                                    class="form-control @error('no_do') is-invalid @enderror" value="{{ old('no_do') }}"
                                    required>
                                @error('no_do')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="no_invoice">NO INVOICE</label>
                                <input type="text" name="no_invoice" id="no_invoice"
                                    class="form-control @error('no_invoice') is-invalid @enderror"
                                    value="{{ old('no_invoice') }}" required>
                                @error('no_invoice')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jumlah_tagihan">JUMLAH TAGIHAN</label>
                                <input type="text" name="jumlah_tagihan" id="jumlah_tagihan"
                                    class="form-control @error('jumlah_tagihan') is-invalid @enderror"
                                    value="{{ old('jumlah_tagihan') }}" required readonly>
                                @error('jumlah_tagihan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-group">
                                <label for="biaya-lain">BIAYA LAIN - LAIN</label>
                                <input type="text" name="biaya-lain" id="biaya-lain"
                                    class="form-control @error('biaya-lain') is-invalid @enderror"
                                    value="{{ old('biaya-lain') }}" required>
                                @error('biaya-lain')
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


                            <h4 class="mt-4">Detail Penerimaan</h4>


                            <div class="table-responsive">
                                <table id="penerimaan-table" class="table datatable table-hover text-nowrap"
                                    id="detail-table">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>NO BATCH</th>
                                            <th>Jenis</th>
                                            <th>Varietas</th>
                                            <th>Grade</th>
                                            <th>Densitas</th>
                                            <th>Kadar Air (kg)</th>
                                            <th>Size</th>
                                            <th>Jumlah Karung</th>
                                            <th>Proses</th>
                                            <th>Berat Diterima</th>
                                            <th>Package Size</th>
                                            <th>Origin</th>
                                            <th>Harga</th>

                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-rows">
                                        <tr class="detail-row align-middle">
                                            <td class="p-1" style="min-width: 150px;">
                                                <input type="text" name="no_batch[]" class="form-control" required>
                                            </td>

                                            <td class="p-1" style="min-width: 150px;">
                                                <select name="id_jenis[]" class="form-control  w-100" required>
                                                    <option value="">Pilih</option>
                                                    @foreach ($jenis as $item)
                                                        <option value="{{ $item->id_jenis }}">{{ $item->deskripsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="p-1" style="min-width: 150px;">
                                                <select name="id_varietas[]" class="form-control w-100" required>
                                                    <option value="">Pilih</option>
                                                    @foreach ($varietas as $item)
                                                        <option value="{{ $item->id_varietas }}">{{ $item->deskripsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="p-1" style="min-width: 150px;">
                                                <select name="id_grade[]" class="form-control w-100" required>
                                                    <option value="">Pilih</option>
                                                    @foreach ($grade as $item)
                                                        <option value="{{ $item->id_grade }}">{{ $item->deskripsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="p-1" style="min-width: 180px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" name="bulk_value[]"
                                                        class="form-control" required
                                                        placeholder="Value dalam bentuk gram">
                                                </div>
                                            </td>

                                            <td class="p-1">
                                                <input type="number" step="0.01" name="kadar_air[]"
                                                    class="form-control" required>
                                            </td>

                                            <td class="p-1">
                                                <input type="number" name="size[]" class="form-control" required>
                                            </td>

                                            <td class="p-1">
                                                <input type="number" name="jumlah[]" class="form-control" required>
                                            </td>

                                            <td class="p-1" style="min-width: 150px;">
                                                <select name="proses[]" class="form-control w-100" required>
                                                    <option value="">Pilih</option>
                                                    @foreach ($proses as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_proses }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="p-1">
                                                <input type="number" step="0.01" name="berat_per_karung[]"
                                                    class="form-control berat" required>
                                            </td>

                                            <td class="p-1" style="min-width: 150px;">
                                                <select name="p_size[]" class="form-control w-100" required>
                                                    <option value="">Pilih</option>
                                                    @foreach ($package as $item)
                                                        <option value="{{ $item->id }}">{{ $item->deskripsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="p-1" style="min-width: 150px;">
                                                <select name="id_origin[]" class="form-control w-100" required>
                                                    <option value="">Pilih</option>
                                                    @foreach ($origin as $item)
                                                        <option value="{{ $item->id_origin }}">{{ $item->deskripsi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="p-1">
                                                <input type="number" name="harga_per_kg[]" class="form-control harga"
                                                    required>
                                            </td>

                                            <td class="p-1 text-center">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-row">Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>


                                </table>
                            </div>

                            <button type="button" id="add-detail" class="btn btn-primary mt-3">Tambah Baris</button>


                    </div>
                </div>




                <div class="card-footer">
                    <div>
                        <a href="{{ route('master_penerimaan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-coffee">Simpan</button>
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
            $('.datatable').DataTable({
                responsive: true
            });

            // Handle delete button click
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                $('#deleteForm').attr('action', `{{ url('master_penerimaan') }}/${id}`);
            });
        });
    </script>
    <script>
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

            // Tampilkan ke field jumlah_tagihan
            document.getElementById('jumlah_tagihan').value = total.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });
        }

        // Event listener untuk semua input harga/berat
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('harga') || e.target.classList.contains('berat')) {
                hitungJumlahTagihan();
            }
        });
    </script>

    <script>
        document.getElementById('add-detail').addEventListener('click', function() {
            const container = document.getElementById('detail-rows');
            const firstRow = container.querySelector('.detail-row');
            const newRow = firstRow.cloneNode(true);

            // Kosongkan semua input dan select
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            container.appendChild(newRow);
        });

        // Event delegation untuk tombol hapus
        document.getElementById('detail-rows').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                const rows = document.querySelectorAll('.detail-row');
                if (rows.length > 1) {
                    row.remove();
                }
            }
        });
    </script>







@endsection
