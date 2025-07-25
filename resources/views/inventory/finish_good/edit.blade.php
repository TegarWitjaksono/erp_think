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
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Add Inventory GoodFinished</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Add Inventory GoodFinished</p>
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
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Inventory GoodFinished</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            @if ($errors->any())
                <div class="alert alert-danger mx-3 mt-3">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Terjadi Kesalahan:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Add Inventory GoodFinished</h3>
                            </div>

                            <form action="{{route('inventory_fg.store')}}" method="POST">
                                @csrf
                               

                                <div class="card-body">
                                  <div class="form-group">
                                        <label>Inventory</label>
                                        <select name="id_inventory" class="form-control">
                                            @foreach($inventory as $k)
                                                <option value="{{ $k->id }}" {{ (old('id_inventory', $result->id_inventory ?? '') == $k->id) ? 'selected' : '' }}>
                                                    {{ $k->keterangan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Batch Production</label>
                                        <select name="Id_batch_production" class="form-control">
                                            @foreach($batches as $k => $v)
                                                <option value="{{ $k }}" {{ (old('Id_batch_production', $result->batch_production_id ?? '') == $k) ? 'selected' : '' }}>
                                                    {{ $v }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Products</label>
                                        <select name="id_product" class="form-control">
                                            @foreach($barang as $k)
                                                <option value="{{ $k->id_barang }}" {{ (old('id_product', $result->id_product ?? '') == $k->id_barang) ? 'selected' : '' }}>
                                                    {{ $k->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis</label>
                                        <select name="jenis" class="form-control">
                                            <option value="PreRoastBlend">PreRoastBlend</option>
                                            <option value="PostRoast">PostRoast</option>
                                            <option value="Single">Single</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Masuk (kg)</label>
                                        <input
                                            type="number"
                                            step="0.001"
                                            name="jml_masuk"
                                            class="form-control"
                                            value="{{ old('jml_masuk', $result->jml_masuk ?? '') }}"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Keluar (Kg)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="jml_keluar"
                                            class="form-control"
                                            value="{{ old('jml_keluar', $result->jml_keluar ?? '') }}"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label>Catatan</label>
                                        <textarea
                                            name="catatan"
                                            class="form-control"
                                        >{{ old('catatan', $result->catatan ?? '') }}</textarea>
                                    </div>
                                     <div class="form-group">
                                        <label>Estimate Expire Date</label>
                                        <input
                                            type="date"
                                            name="expired_date"
                                            class="form-control"
                                            value="{{ old('expire_date', $result->expired_date ?? '') }}"
                                        >
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
