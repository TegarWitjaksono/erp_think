@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Varietas</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Edit Varietas
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_varietas.update', $data->id_varietas) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="Deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="Deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-coffee">Simpan</button>
                                <a href="{{ route('master_varietas.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
