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
                                                <!-- Tombol untuk buka modal -->
                                                <div class="pb-2">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal" data-target="#modalRoastProfile">
                                                        Pilih Roast Profile
                                                    </button>

                                                </div>

                                                <!-- Hidden select (tetap ada biar ikut form submit) -->
                                                <select id="roast_profile_id" class="form-control" readonly>
                                                    @foreach ($profiles as $k => $v)
                                                        <option value="{{ $k }}"
                                                            {{ old('roast_profile_id', $batch->roast_profile_id ?? '') == $k ? 'selected' : '' }}>
                                                            {{ $v }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" id="roast_profile_to" name="roast_profile_id"
                                                    value="{{ old('roast_profile_id', $batch->roast_profile_id ?? '') }}">
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






                                            {{-- <div class="form-group">
                                                <label>Estimate Expire Date</label>
                                                <input type="date" name="estimate_expire_date" class="form-control"
                                                    value="{{ old('estimate_expire_date', $batch->estimate_expire_date ?? '') }}">
                                            </div> --}}

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
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="detail-row">
                                                <td style="width: 500px ;">

                                                    <!-- Hidden input untuk menyimpan nilai yang dipilih -->
                                                    <input type="hidden" name="id_detail_penerimaan[]" class="detail-id"
                                                        required>

                                                    <!-- Input untuk menampilkan teks yang dipilih -->
                                                    <input type="text" class="form-control detail-display"
                                                        placeholder="Pilih Detail Penerimaan" readonly data-toggle="modal"
                                                        data-target="#detailModal">

                                                    <input type="text" class="form-control inventory-display"
                                                        placeholder="Inventory terpilih" readonly>
                                                    <input type="hidden" name="id_inventory[]" class="inventory-id">

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
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-row">Hapus</button>
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

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="detailModalLabel" class="modal-title">Pilih
                        Detail Penerimaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search Box -->
                    <div class="form-group">
                        <input type="text" class="form-control" id="detailSearch"
                            placeholder="Cari Detail Penerimaan...">
                    </div>

                    <!-- Daftar Inventory -->
                    <div class="table-responsive">
                        <table class="datatable table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Berat</th>
                                    <th>Harga</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="detailList">
                                @foreach ($detailPenerimaan as $item)
                                    <tr>
                                        <td style="width: 200px ;">
                                            {{ $item->nama_rm . ' - ' . $item->origin_desc . ' - ' . $item->varietas_desc . ' - ' . $item->jenis_desc . '-' . $item->nama_proses }}
                                        </td>

                                        <td>{{ $item->berat }}</td>
                                        <td>{{ $item->harga_per_kg }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary select-detail "
                                                data-id="{{ $item->id_detail_penerimaan }}"
                                                data-catatan="{{ $item->nama_rm . ' - ' . $item->origin_desc . ' - ' . $item->varietas_desc . ' - ' . $item->jenis_desc . '-' . $item->nama_proses }}">
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
    <div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Daftar Detail Penerimaan -->
                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>No Inventory</th>

                                    <th>Sisa</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="detailInventory">
                                <!-- Diisi dinamis lewat JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRoastProfile" tabindex="-1" role="dialog"
        aria-labelledby="modalRoastProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Roast Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" onclick="openAddModal()">
                            <i class="fas fa-plus"></i> Tambah Profile Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="profileTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Profile</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profiles as $k => $v)
                                    <tr data-id="{{ $k }}">
                                        <td>{{ $k }}</td>
                                        <td>{{ $v }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success pilih-profile"
                                                data-id="{{ $k }}" data-nama="{{ $v }}">
                                                <i class="fas fa-check"></i> Pilih
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning edit-profile"
                                                data-id="{{ $k }}" data-nama="{{ $v }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info save-as-profile"
                                                data-id="{{ $k }}" data-nama="{{ $v }}">
                                                <i class="fas fa-copy"></i> Save As
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form - Tambah/Edit Profile -->
    <div class="modal fade" id="modalFormProfile" tabindex="-1" role="dialog" aria-labelledby="modalFormProfileLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormTitle">Tambah Roast Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="profileForm">
                    <div class="modal-body">
                        <input type="hidden" id="profile_id" name="id" value="">
                        <input type="hidden" id="form_action" value="add"> <!-- add, edit, save_as -->

                        <div class="form-group">
                            <label for="profile_deskripsi">Deskripsi Profile</label>
                            <textarea id="profile_deskripsi" name="deskripsi" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Temperatur (°C)</th>
                                        <th>Waktu (detik)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Charge</td>
                                        <td><input type="number" step="0.01" name="charge_temp" id="charge_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="charge_time_sec" id="charge_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>TP</td>
                                        <td><input type="number" step="0.01" name="tp_temp" id="tp_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="tp_time_sec" id="tp_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>DE</td>
                                        <td><input type="number" step="0.01" name="de_temp" id="de_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="de_time_sec" id="de_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>FC</td>
                                        <td><input type="number" step="0.01" name="fc_temp" id="fc_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="fc_time_sec" id="fc_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>SC</td>
                                        <td><input type="number" step="0.01" name="sc_temp" id="sc_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="sc_time_sec" id="sc_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Drop</td>
                                        <td><input type="number" step="0.01" name="drop_temp" id="drop_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="drop_time_sec" id="drop_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Utama - Pilih Roast Profile -->
    <div class="modal fade" id="modalRoastProfile" tabindex="-1" role="dialog"
        aria-labelledby="modalRoastProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Roast Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" onclick="openAddModal()">
                            <i class="fas fa-plus"></i> Tambah Profile Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="profileTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Profile</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profiles as $k => $v)
                                    <tr data-id="{{ $k }}">
                                        <td>{{ $k }}</td>
                                        <td>{{ $v }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success pilih-profile"
                                                data-id="{{ $k }}" data-nama="{{ $v }}">
                                                <i class="fas fa-check"></i> Pilih
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning edit-profile"
                                                data-id="{{ $k }}" data-nama="{{ $v }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info save-as-profile"
                                                data-id="{{ $k }}" data-nama="{{ $v }}">
                                                <i class="fas fa-copy"></i> Save As
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-profile"
                                                data-id="{{ $k }}" data-nama="{{ $v }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form - Tambah/Edit Profile -->
    <div class="modal fade" id="modalFormProfile" tabindex="-1" role="dialog" aria-labelledby="modalFormProfileLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormTitle">Tambah Roast Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="profileForm">
                    <div class="modal-body">
                        <input type="hidden" id="profile_id" name="id" value="">
                        <input type="hidden" id="form_action" value="add"> <!-- add, edit, save_as -->

                        <div class="form-group">
                            <label for="profile_deskripsi">Deskripsi Profile</label>
                            <textarea id="profile_deskripsi" name="deskripsi" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Temperatur (°C)</th>
                                        <th>Waktu (detik)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Charge</td>
                                        <td><input type="number" step="0.01" name="charge_temp" id="charge_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="charge_time_sec" id="charge_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>TP</td>
                                        <td><input type="number" step="0.01" name="tp_temp" id="tp_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="tp_time_sec" id="tp_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>DE</td>
                                        <td><input type="number" step="0.01" name="de_temp" id="de_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="de_time_sec" id="de_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>FC</td>
                                        <td><input type="number" step="0.01" name="fc_temp" id="fc_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="fc_time_sec" id="fc_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>SC</td>
                                        <td><input type="number" step="0.01" name="sc_temp" id="sc_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="sc_time_sec" id="sc_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Drop</td>
                                        <td><input type="number" step="0.01" name="drop_temp" id="drop_temp"
                                                class="form-control" placeholder="0.00"></td>
                                        <td><input type="number" name="drop_time_sec" id="drop_time_sec"
                                                class="form-control" placeholder="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .btn-coffee {
            background-color: #79523B;
            border-color: #79523B;
            color: white;
        }

        .btn-coffee:hover {
            background-color: #5d3e2d;
            border-color: #5d3e2d;
            color: white;
        }

        .table td {
            vertical-align: middle;
        }

        .modal-xl {
            max-width: 90%;
        }
    </style>

    <script>
        $(document).ready(function() {
            const select = $('#roast_profile_id');
            const hidden = $('#roast_profile_to');

            // Set default value dari option pertama kalau hidden belum ada value
            if (!hidden.val()) {
                const firstVal = select.find('option:first').val();
                hidden.val(firstVal);
                select.val(firstVal);
            }
            // Event handler untuk pilih profile
            $(document).on('click', '.pilih-profile', function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');

                // Set nilai ke form utama (sesuaikan dengan field yang ada)
                if ($('#roast_profile_id').length) {
                    $('#roast_profile_id').val(id);
                    $('#roast_profile_to').val(id);
                }
                if ($('#roast_profile_name').length) {
                    $('#roast_profile_name').val(nama);
                }

                $('#modalRoastProfile').modal('hide');

                // Optional: tampilkan notifikasi
                showNotification('Profile berhasil dipilih: ' + nama, 'success');
            });

            // Event handler untuk edit profile
            $(document).on('click', '.edit-profile', function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');

                openEditModal(id, nama);
            });

            // Event handler untuk save as profile
            $(document).on('click', '.save-as-profile', function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');

                openSaveAsModal(id, nama);
            });

            // Event handler untuk delete profile
            $(document).on('click', '.delete-profile', function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');

                if (confirm('Apakah Anda yakin ingin menghapus profile "' + nama + '"?')) {
                    deleteProfile(id);
                }
            });

            // Submit form handler
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                let formAction = $('#form_action').val();
                let formData = new FormData(this);

                // Validasi
                if (!validateProfileForm()) {
                    return false;
                }

                // Tentukan URL dan method berdasarkan aksi
                let url, method;
                if (formAction === 'add') {
                    url = '{{ route('roast_profile.store') }}';
                    method = 'POST';
                } else if (formAction === 'edit') {
                    url = '{{ route('roast_profile.update', ':id') }}'.replace(':id', $('#profile_id')
                        .val());
                    method = 'POST';
                    formData.append('_method', 'PUT');
                } else if (formAction === 'save_as') {
                    url = '{{ route('roast_profile.store') }}';
                    method = 'POST';
                    formData.delete('id'); // Hapus ID untuk create new
                }

                // Add CSRF token
                formData.append('_token', '{{ csrf_token() }}');

                // Submit via AJAX
                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#modalFormProfile').modal('hide');
                        showNotification('Profile berhasil disimpan!', 'success');

                        // Refresh table atau redirect
                        location.reload();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            showValidationErrors(errors);
                        } else {
                            showNotification('Terjadi kesalahan saat menyimpan profile',
                                'error');
                        }
                    }
                });
            });
        });

        // Function untuk membuka modal tambah
        function openAddModal() {
            resetForm();
            $('#modalFormTitle').text('Tambah Roast Profile Baru');
            $('#form_action').val('add');
            $('#submitBtn').text('Simpan');
            $('#modalFormProfile').modal('show');
        }

        // Function untuk membuka modal edit
        function openEditModal(id, nama) {
            resetForm();
            $('#modalFormTitle').text('Edit Roast Profile');
            $('#form_action').val('edit');
            $('#profile_id').val(id);
            $('#submitBtn').text('Update');

            // Load data profile via AJAX
            loadProfileData(id);

            $('#modalFormProfile').modal('show');
        }

        // Function untuk membuka modal save as
        function openSaveAsModal(id, nama) {
            resetForm();
            $('#modalFormTitle').text('Save As New Profile');
            $('#form_action').val('save_as');
            $('#profile_deskripsi').val(nama + ' - Copy');
            $('#submitBtn').text('Save As New');

            // Load data profile via AJAX
            loadProfileData(id);

            $('#modalFormProfile').modal('show');
        }

        // Function untuk load data profile
        function loadProfileData(id) {
            $.ajax({
                url: '{{ route('roast_profile.show', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(data) {
                    // Populate form dengan data
                    $('#profile_deskripsi').val(data.deskripsi);
                    $('#charge_temp').val(data.charge_temp);
                    $('#charge_time_sec').val(data.charge_time_sec);
                    $('#tp_temp').val(data.tp_temp);
                    $('#tp_time_sec').val(data.tp_time_sec);
                    $('#de_temp').val(data.de_temp);
                    $('#de_time_sec').val(data.de_time_sec);
                    $('#fc_temp').val(data.fc_temp);
                    $('#fc_time_sec').val(data.fc_time_sec);
                    $('#sc_temp').val(data.sc_temp);
                    $('#sc_time_sec').val(data.sc_time_sec);
                    $('#drop_temp').val(data.drop_temp);
                    $('#drop_time_sec').val(data.drop_time_sec);
                },
                error: function() {
                    showNotification('Gagal memuat data profile', 'error');
                }
            });
        }

        // Function untuk delete profile
        function deleteProfile(id) {
            $.ajax({
                url: '{{ route('roast_profile.destroy', ':id') }}'.replace(':id', id),
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'DELETE'
                },
                success: function() {
                    $('tr[data-id="' + id + '"]').remove();
                    showNotification('Profile berhasil dihapus', 'success');
                },
                error: function() {
                    showNotification('Gagal menghapus profile', 'error');
                }
            });
        }

        // Function untuk reset form
        function resetForm() {
            $('#profileForm')[0].reset();
            $('#profile_id').val('');
            $('#form_action').val('add');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        // Function untuk validasi form
        function validateProfileForm() {
            let isValid = true;
            let deskripsi = $('#profile_deskripsi').val().trim();

            if (deskripsi === '') {
                showFieldError('#profile_deskripsi', 'Deskripsi wajib diisi');
                isValid = false;
            }

            return isValid;
        }

        // Function untuk menampilkan error di field
        function showFieldError(fieldSelector, message) {
            $(fieldSelector).addClass('is-invalid');
            if ($(fieldSelector).next('.invalid-feedback').length === 0) {
                $(fieldSelector).after('<div class="invalid-feedback">' + message + '</div>');
            }
        }

        // Function untuk menampilkan validation errors
        function showValidationErrors(errors) {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.each(errors, function(field, messages) {
                let fieldSelector = '#profile_' + field;
                if ($(fieldSelector).length === 0) {
                    fieldSelector = '#' + field;
                }

                if ($(fieldSelector).length > 0) {
                    showFieldError(fieldSelector, messages[0]);
                }
            });
        }

        // Function untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Implementasi sesuai dengan notification library yang digunakan
            // Contoh dengan alert (ganti dengan library yang lebih baik)
            if (type === 'success') {
                alert('✅ ' + message);
            } else if (type === 'error') {
                alert('❌ ' + message);
            } else {
                alert('ℹ️ ' + message);
            }
        }
    </script>


    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });

            // Pencarian Inventory
            $('#detailSearch').keyup(function() {
                var value = $(this).val().toLowerCase();
                $('#detailList tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            let detailPenerimaan =
                @json($detailPenerimaan); // Semua data inventory (dengan relasi detail_penerimaan)

            let currentRow = null;

            $(document).on('click', '.detail-display', function() {
                currentRow = $(this).closest('tr');
            })

            // Saat tombol "Pilih" inventory ditekan
            $(document).on('click', '.select-detail', function() {
                let id = $(this).data('id');
                let catatan = $(this).data('catatan');

                // Isi hanya di baris yang aktif
                currentRow.find('.detail-display').val(catatan);
                currentRow.find('.detail-id').val(id);

                let detailList = detailPenerimaan.filter(i => i.id_detail_penerimaan == id);
                let html = '';

                detailList.forEach(item => {
                    html += `
            <tr>
                  <td style='width : 200px ;'>${item.nama_rm} - ${item.origin_desc} - ${item.varietas_desc} - ${item.jenis_desc} - ${item.nama_proses}</td>
                <td>${item.no_inventory}</td>

                <td>${item.masuk - item.keluar}</td>

                <td>
                    <button type="button" class="btn btn-sm btn-success select-inventory"
                        data-inventory-id="${item.id}"
                        data-inventory-label="${item.no_inventory} - ${item.catatan}">
                        Pilih
                    </button>
                </td>
            </tr>
        `;
                });

                $('#detailInventory').html(html);

                $('#detailModal').modal('hide');
                $('#inventoryModal').modal('show');
            });


            // Saat pilih detail_penerimaan
            $(document).on('click', '.select-inventory', function() {
                let idInventory = $(this).data('inventory-id');
                let inventoryLabel = $(this).data('inventory-label');

                currentRow.find('.inventory-id').val(idInventory);
                currentRow.find('.inventory-display').val(inventoryLabel);

                $('#inventoryModal').modal('hide');
            });

        });
    </script>
    <script>
        function updateMethodField() {
            const methodSelect = document.getElementById('method_select');
            const detailRows = document.querySelectorAll('.detail-row');

            // pastikan select selalu disabled (readonly look)
            methodSelect.setAttribute('disabled', true);

            // pastikan hidden input ada
            let hidden = document.getElementById('method_id_hidden');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'method_id';
                hidden.id = 'method_id_hidden';
                methodSelect.parentElement.appendChild(hidden);
            }

            if (detailRows.length === 1) {
                // Kirim sesuai value select
                hidden.value = methodSelect.value;
            } else {
                // Kirim fix 2
                hidden.value = "2";

                // Optional: biar visual select juga keliatan "2"
                methodSelect.value = "2";
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
