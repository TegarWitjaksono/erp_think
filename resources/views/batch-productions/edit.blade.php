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
                <div class="container mt-4">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab"
                                aria-controls="tab1" aria-selected="true">Edit Batch Production</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab"
                                aria-controls="tab2" aria-selected="false">Batch Production Result</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                            <div class="row pt-3">
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
                                                Anda sedang mengedit batch production dengan No:
                                                <strong>{{ $batch->no_batch }}</strong>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="no_batch">NO Batch</label>
                                                            <input type="text" name="no_batch" id="no_batch"
                                                                class="form-control @error('no_batch') is-invalid @enderror"
                                                                value="{{ old('no_batch', $batch->no_batch) }}" readonly
                                                                required>
                                                            @error('no_batch')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Method</label>
                                                            <select disabled name="method_id" id="method_select"
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
                                                                        {{ old('roast_profile_id', $batch->id_roastprofile ?? '') == $batch->id_roastprofile ? 'selected' : '' }}>
                                                                        {{ $v }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <input type="hidden" id="roast_profile_to"
                                                                name="roast_profile_id"
                                                                value="{{ old('roast_profile_id', $batch->id_roastprofile ?? '') }}">
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
                                                            <input type="number" step="0.1" name="berat_diroasting"
                                                                readonly id="berat_diroasting"
                                                                class="form-control @error('berat_diroasting') is-invalid @enderror"
                                                                value="{{ old('berat_diroasting', $batch->berat_diroasting) }}">
                                                            @error('berat_diroasting')
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
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        id="add-detail">
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
                                                                <th width="15%">Qty (gr)</th>
                                                                <th width="25%">Catatan</th>
                                                                <th width="5%">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail-rows">
                                                            @foreach ($details as $index => $detail)
                                                                <tr class="detail-row">
                                                                    <td>
                                                                        <input type="hidden" name="detail_ids[]"
                                                                            value="{{ $detail->detail_id }}">


                                                                        <input type="text"
                                                                            class="form-control detail-penerimaan-display"
                                                                            data-toggle="modal" data-target="#detailModal"
                                                                            placeholder="Detail Penerimaan terpilih"
                                                                            readonly
                                                                            value="{{ $detail->id_batch }} - {{ $detail->jenis_kemasan }}">
                                                                        <input type="hidden"
                                                                            name="id_detail_penerimaan[]"
                                                                            class="detail-penerimaan-id"
                                                                            value="{{ $detail->id_detail_penerimaan }}"
                                                                            required>

                                                                        <!-- Hidden input untuk menyimpan nilai yang dipilih -->
                                                                        <input type="hidden" name="id_inventory[]"
                                                                            class="inventory-id"
                                                                            value="{{ $detail->inventory_id }}" required>

                                                                        <!-- Input untuk menampilkan teks yang dipilih -->
                                                                        <input type="text"
                                                                            class="form-control inventory-display"
                                                                            placeholder="Pilih Inventory" readonly
                                                                            value="{{ $detail->inventory_catatan }}">

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
                                                                        <input type="number" name="kadar_air[]"
                                                                            value="{{ old('kadar_air.' . $index) !== null
                                                                                ? (string) old('kadar_air.' . $index)
                                                                                : (is_array($detail->kadar_air)
                                                                                    ? ''
                                                                                    : (string) $detail->kadar_air) }}"
                                                                            step="0.01"
                                                                            class="form-control kadar-air-input" required>


                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="0.01"
                                                                            name="bulk_densitas[]"
                                                                            class="form-control bulk-densitas-input"
                                                                            value="{{ old('bulk_densitas.' . $index, $detail->bulk_densitas) }}"
                                                                            required>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="0.01"
                                                                            name="qty_out[]"
                                                                            class="form-control qty-out-input"
                                                                            value="{{ old('qty_out.' . $index, $detail->qty_out) }}"
                                                                            required>
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="catatan_detail[]" class="form-control" rows="2">{{ old('catatan_detail.' . $index, $detail->detail_catatan) }}</textarea>
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
                                                    <a href="{{ route('batch-productions.index') }}"
                                                        class="btn btn-secondary">
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
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Action Menu Batch Production</h3>
                                        </div>
                                        <form action="{{ route('batch-production.store-menu', $batch->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-info-circle"></i> ID Batch akan dibuat otomatis dengan
                                                format P0001, P0002, dst.
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    {{-- KOLOM KIRI --}}
                                                    <div class="col-md-6">
                                                        <!-- Data Readonly (dari batch sebelumnya) -->
                                                        <div class="card border-secondary mb-3">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0"><i class="fas fa-lock"></i> Data Batch
                                                                    (Readonly)</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label for="no_batch">NO Batch</label>
                                                                    <input type="text" name="no_batch" id="no_batch"
                                                                        class="form-control bg-light"
                                                                        value="{{ old('no_batch', $batch->no_batch) }}"
                                                                        readonly required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Method</label>
                                                                    <select name="method_id" class="form-control bg-light"
                                                                        readonly disabled>
                                                                        @foreach ($methods as $k => $v)
                                                                            <option value="{{ $k }}"
                                                                                {{ old('method_id', $batch->id_method ?? '') == $k ? 'selected' : '' }}>
                                                                                {{ $v }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="hidden" name="method_id"
                                                                        value="{{ old('method_id', $batch->id_method ?? '') }}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Roast Profile</label>
                                                                    <select name="roast_profile_id"
                                                                        class="form-control bg-light" readonly disabled>
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
                                                                    <select name="id_mesin" class="form-control bg-light"
                                                                        readonly disabled>
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
                                                                    <select name="attention" class="form-control bg-light"
                                                                        readonly disabled>
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
                                                            </div>
                                                        </div>

                                                        <!-- Data yang bisa diedit -->
                                                        <div class="card border-primary">
                                                            <div class="card-header bg-primary text-white">
                                                                <h6 class="mb-0"><i class="fas fa-edit"></i> Data
                                                                    Tambahan (Editable)</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label>Agtron</label>
                                                                    <input type="number" name="agtron"
                                                                        class="form-control"
                                                                        value="{{ old('agtron', $batch->agtron ?? '') }}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Cupping Score</label>
                                                                    <input type="number" step="0.01"
                                                                        name="cupping_score" class="form-control"
                                                                        value="{{ old('cupping_score', $batch->cupping_score ?? '') }}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Berat Akhir</label>
                                                                    <input type="number" step="0.01"
                                                                        name="berat_akhir" class="form-control"
                                                                        value="{{ old('berat_akhir', $batch->berat_akhir ?? '') }}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Kadar Air</label>
                                                                    <input type="number" step="0.01" name="kadar_air"
                                                                        class="form-control"
                                                                        value="{{ old('kadar_air', $batch->kadar_air ?? '') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- KOLOM KANAN --}}
                                                    <div class="col-md-6">
                                                        <!-- Data Readonly (dari batch sebelumnya) -->
                                                        <div class="card border-secondary mb-3">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0"><i class="fas fa-lock"></i> Info
                                                                    Product (Readonly)</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label>Product</label>
                                                                    <select name="id_product"
                                                                        class="form-control bg-light" readonly disabled>
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
                                                                    <select name="level_roast_id"
                                                                        class="form-control bg-light" readonly disabled>
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
                                                                    <input type="text" name="status"
                                                                        class="form-control bg-light" value="open"
                                                                        readonly>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Berat Diroasting (kg)</label>
                                                                    <input type="number" step="0.1"
                                                                        name="berat_diroasting" readonly
                                                                        id="berat_diroasting"
                                                                        class="form-control bg-light"
                                                                        value="{{ old('berat_diroasting', $batch->berat_diroasting ?? '') }}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Estimate Expire Date</label>
                                                                    <input type="date" name="estimate_expire_date"
                                                                        class="form-control bg-light"
                                                                        value="{{ old('estimate_expire_date', isset($batch->estimate_expire_date) ? Carbon\Carbon::parse($batch->estimate_expire_date)->format('Y-m-d') : '') }}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Catatan</label>
                                                                    <textarea name="catatan" class="form-control bg-light" readonly>{{ old('catatan', $batch->catatan ?? '') }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Data yang bisa diedit -->
                                                        <div class="card border-primary">
                                                            <div class="card-header bg-primary text-white">
                                                                <h6 class="mb-0"><i class="fas fa-edit"></i> Note
                                                                    Flavour (Editable)</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label>Note Flavour</label>
                                                                    <textarea name="note_flavour" class="form-control" rows="4" placeholder="Masukkan catatan flavour...">{{ old('note_flavour', $batch->note_flavour ?? '') }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary"
                                                        onclick="window.history.back();">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-coffee">
                                                        <i class="fas fa-save"></i> Simpan
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <th>Jenis</th>
                                    <th>No Batch</th>
                                    <th>Berat</th>
                                    <th>Jumlah</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="detailList">
                                @foreach ($detailPenerimaan as $item)
                                    <tr>
                                        <td>{{ $item->jenis_kemasan }}</td>
                                        <td>{{ $item->id_batch }}</td>
                                        <td>{{ $item->berat }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary select-detail"
                                                data-id="{{ $item->id_detail_penerimaan }}"
                                                data-catatan="{{ $item->id_batch }}">
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

    <!-- Modal Pilih Inventory -->
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
                                    <th>No Inventory</th>
                                    <th>Catatan</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
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
    <script>
        $(document).ready(function() {

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
                $('#detailInventory tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            let detailData =
                @json($detailPenerimaan); // Semua data inventory (dengan relasi detail_penerimaan)

            let currentRow = null;

            $(document).on('click', '.detail-penerimaan-display', function() {
                currentRow = $(this).closest('tr');
            })

            // Saat tombol "Pilih" inventory ditekan
            $(document).on('click', '.select-detail', function() {
                let id = $(this).data('id');
                let catatan = $(this).data('catatan');

                // Isi hanya di baris yang aktif
                currentRow.find('.detail-penerimaan-display').val(catatan);
                currentRow.find('.detail-penerimaan-id').val(id);

                let detailList = detailData.filter(i => i.id_detail_penerimaan == id);
                let html = '';

                detailList.forEach(item => {
                    html += `
            <tr>
                <td>${item.no_inventory}</td>
                <td>${item.catatan}</td>
                <td>${item.keluar}</td>
                <td>${item.masuk}</td>
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
                let LabelInventory = $(this).data('inventory-label');

                currentRow.find('.inventory-id').val(idInventory);
                currentRow.find('.inventory-display').val(LabelInventory);

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
                <td>
                    <input type="hidden" name="detail_ids[]" value="">
                    <input type="text"
                        class="form-control detail-penerimaan-display"
                        placeholder="Pilih Detail Penerimaan"
                        data-toggle="modal"
                        data-target="#detailModal"
                        readonly>
                    <input type="hidden" name="id_detail_penerimaan[]"
                        class="detail-penerimaan-id" required>

                    <!-- Hidden input untuk menyimpan nilai yang dipilih -->
                    <input type="hidden" name="id_inventory[]"
                        class="inventory-id" required>

                    <!-- Input untuk menampilkan teks yang dipilih -->
                    <input type="text" class="form-control inventory-display"
                        placeholder="Inventory Terpilih" readonly>
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

    <script>
        // Pastikan Bootstrap JavaScript sudah dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi tab jika diperlukan
            var triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        });
    </script>



@endsection
