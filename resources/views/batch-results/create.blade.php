@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-tags fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Hasil Sangrai</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Update detail hasil batch roasting</p>
                            </div>
                        </div>
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
                                        <a href="{{ route('sku.index') }}" style="color: #79523B;">SKU</a>
                                    </li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Edit Hasil</li>
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
                                <h3 class="card-title">Edit Hasil Batch</h3>
                            </div>

                            <form action="" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Batch Production</label>
                                        <select name="batch_production_id" class="form-control">
                                            @foreach($batches as $k => $v)
                                                <option value="{{ $k }}" {{ (old('batch_production_id', $result->batch_production_id ?? '') == $k) ? 'selected' : '' }}>
                                                    {{ $v }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Level Roast</label>
                                        <select name="level_roast_id" class="form-control">
                                            @foreach($levels as $k => $v)
                                                <option value="{{ $k }}" {{ (old('level_roast_id', $result->level_roast_id ?? '') == $k) ? 'selected' : '' }}>
                                                    {{ $v }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Berat Akhir (kg)</label>
                                        <input
                                            type="number"
                                            step="0.001"
                                            name="berat_akhir"
                                            class="form-control"
                                            value="{{ old('berat_akhir', $result->berat_akhir ?? '') }}"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Kadar Air (%)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="kadar_air"
                                            class="form-control"
                                            value="{{ old('kadar_air', $result->kadar_air ?? '') }}"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Agtron</label>
                                        <input
                                            type="number"
                                            name="agtron"
                                            class="form-control"
                                            value="{{ old('agtron', $result->agtron ?? '') }}"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Cupping Score</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="cupping_score"
                                            class="form-control"
                                            value="{{ old('cupping_score', $result->cupping_score ?? '') }}"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Note Flavour</label>
                                        <textarea
                                            name="note_flavour"
                                            class="form-control"
                                        >{{ old('note_flavour', $result->note_flavour ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-coffee">SIMPAN</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
