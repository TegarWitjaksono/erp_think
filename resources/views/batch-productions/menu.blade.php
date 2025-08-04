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
                            <form action="{{ route('batch-production.store-menu', $batch->id) }}" method="POST">
                                @csrf
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> ID Batch akan dibuat otomatis dengan format P0001,
                                    P0002, dst.
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        {{-- KIRI --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_batch">NO Batch</label>
                                                <input type="text" name="no_batch" id="no_batch" class="form-control"
                                                    value="{{ old('no_batch', $nextBatchId ?? '') }}" readonly required>
                                            </div>

                                            <div class="form-group">
                                                <label>Method</label>
                                                <select name="method_id" class="form-control" readonly disabled>
                                                    @foreach ($methods as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('method_id', $batch->method_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="method_id"
                                                    value="{{ old('method_id', $batch->method_id ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Roast Profile</label>
                                                <select name="roast_profile_id" class="form-control" readonly disabled>
                                                    @foreach ($profiles as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('roast_profile_id', $batch->roast_profile_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="roast_profile_id"
                                                    value="{{ old('roast_profile_id', $batch->roast_profile_id ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Mesin</label>
                                                <select name="id_mesin" class="form-control" readonly disabled>
                                                    @foreach ($machines as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('id_mesin', $batch->mesin_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="id_mesin"
                                                    value="{{ old('id_mesin', $batch->mesin_id ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Attention</label>
                                                <select name="attention" class="form-control" readonly disabled>
                                                    @foreach ($attentions as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('attention', $batch->attention ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="attention"
                                                    value="{{ old('attention', $batch->attention ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Agtron</label>
                                                <input type="number" name="agtron" class="form-control"
                                                    value="{{ old('agtron', $batch->agtron ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Cupping Score</label>
                                                <input type="number" step="0.01" name="cupping_score"
                                                    class="form-control"
                                                    value="{{ old('cupping_score', $batch->cupping_score ?? '') }}">
                                            </div>


                                            <div class="form-group">
                                                <label>Berat Akhir</label>
                                                <input type="number" step="0.01" name="berat_akhir" class="form-control"
                                                    value="{{ old('berat_akhir', $batch->berat_akhir ?? '') }}">
                                            </div>


                                            <div class="form-group">
                                                <label>Kadar Air</label>
                                                <input type="number" step="0.01" name="kadar_air"
                                                    class="form-control"
                                                    value="{{ old('kadar_air', $batch->kadar_air ?? '') }}">
                                            </div>


                                        </div>

                                        {{-- KANAN --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product</label>
                                                <select name="id_product" class="form-control" readonly disabled>
                                                    @foreach ($products as $k)
                                                        <option value="{{ $k->id_barang }}"
                                                            {{ old('id_product', $batch->id_product ?? '') == $k->id_barang ? 'selected' : '' }}>
                                                            {{ $k->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="id_product"
                                                    value="{{ old('id_product', $batch->id_product ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Level Roast</label>
                                                <select name="level_roast_id" class="form-control" readonly disabled>
                                                    @foreach ($levels as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('level_roast_id', $batch->level_roast_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="level_roast_id"
                                                    value="{{ old('level_roast_id', $batch->level_roast_id ?? '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Status</label>
                                                <input type="text" name="status" class="form-control" value="open"
                                                    readonly>
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
                                                    readonly
                                                    value="{{ old('estimate_expire_date', isset($batch->estimate_expire_date) ? Carbon\Carbon::parse($batch->estimate_expire_date)->format('Y-m-d') : '') }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Catatan</label>
                                                <textarea name="catatan" class="form-control" readonly>{{ old('catatan', $batch->catatan ?? '') }}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Note Flavour</label>
                                                <textarea name="note_flavour" class="form-control">{{ old('note_flavour', $batch->note_flavour ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.history.back();">Cancel</button>
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
        function updateMethodField() {
            const methodSelect = document.getElementById('method_select');
            const detailRows = document.querySelectorAll('.detail-row');

            if (detailRows.length === 1) {
                // Otomatis set ke "Single" dan disable (readonly)
                for (let i = 0; i < methodSelect.options.length; i++) {
                    if (methodSelect.options[i].text.toLowerCase().includes('single')) {
                        methodSelect.value = methodSelect.options[i].value;
                        break;
                    }
                }
                methodSelect.setAttribute('disabled', true);
            } else {
                // Jika lebih dari 1 detail, aktifkan pilihan method
                methodSelect.removeAttribute('disabled');
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
