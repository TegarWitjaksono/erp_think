@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-cubes fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Nickname Raw Materials</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage your roasted coffee Nickname Raw Materials</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">
                                        Master Raw</li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">
                                        Edit Nickname Raw</li>
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
                                <h3 class="card-title">Edit data Nickname Raw Material</h3>
                            </div>
                            <form action="{{ route('master_raw.update', $data->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="nama">Nama Raw Material</label>
                                        <input type="text" name="nama" class="form-control"
                                            value="{{ old('nama', $data->nama) }}">
                                        @error('nama')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="id_origin">Pilih Origin</label>
                                        <select name="id_origin" id="id_origin"
                                            class="form-control @error('id_origin') is-invalid @enderror" required>
                                            <option value="">Select Origin</option>
                                            @foreach ($origin as $item)
                                                <option value="{{ $item->id_origin }}"
                                                    {{ old('id_origin', $data->id_origin) == $item->id_origin ? 'selected' : '' }}>
                                                    {{ $item->deskripsi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_origin')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="id_jenis">Pilih Jenis</label>
                                        <select name="id_jenis" id="id_jenis"
                                            class="form-control @error('id_jenis') is-invalid @enderror" required>
                                            <option value="">Select Jenis</option>
                                            @foreach ($jenis as $item)
                                                <option value="{{ $item->id_jenis }}"
                                                    {{ old('id_jenis', $data->id_jenis) == $item->id_jenis ? 'selected' : '' }}>
                                                    {{ $item->deskripsi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_jenis')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="id_varietas">Pilih Varietas</label>
                                        <select name="id_varietas" id="id_varietas"
                                            class="form-control @error('id_varietas') is-invalid @enderror" required>
                                            <option value="">Select Varietas</option>
                                            @foreach ($varietas as $var)
                                                <option value="{{ $var->id_varietas }}"
                                                    {{ old('id_varietas', $data->id_varietas) == $var->id_varietas ? 'selected' : '' }}>
                                                    {{ $var->deskripsi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_varietas')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="id_proses">Pilih Proses</label>
                                        <select name="id_proses" id="id_proses"
                                            class="form-control @error('id_proses') is-invalid @enderror" required>
                                            <option value="">Select Proses</option>
                                            @foreach ($proses as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('id_proses', $data->id_proses) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama_proses }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_proses')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.history.back()">Cancel</button>
                                    <button type="submit" class="btn btn-coffee">Update</button>
                                </div>
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
