@extends('dashboard')

@section('konten')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Kelas</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('master_kelas.store') }}" method="POST" enctype="multipart/form-data"> <!-- Tambahkan enctype -->
                @csrf
                <div class="form-group">
                    <label for="nama_kelas">Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas') }}" required>
                    @error('nama_kelas')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="sts">Status</label>
                    <select name="sts" class="form-control @error('sts') is-invalid @enderror" required>
                        <option value="1" {{ old('sts') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('sts') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('sts')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                    @error('foto')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection