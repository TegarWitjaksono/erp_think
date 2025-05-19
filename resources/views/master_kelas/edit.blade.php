@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Kelas</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Edit Kelas
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_kelas.update', $kelas->id_kelas) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama_kelas">Nama Kelas</label>
                                <input type="text" name="nama_kelas"
                                    class="form-control @error('nama_kelas') is-invalid @enderror"
                                    value="{{ $kelas->nama_kelas }}" required>
                                @error('nama_kelas')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="id_jurusan">Jurusan</label>
                                <select name="id_jurusan" class="form-control @error('id_jurusan') is-invalid @enderror"
                                    required>
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($jurusan as $jrs)
                                        <option value="{{ $jrs->id_jurusan }}"
                                            {{ $kelas->id_jurusan == $jrs->id_jurusan ? 'selected' : '' }}>
                                            {{ $jrs->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                @error('id_jurusan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sts">Status</label>
                                <select name="sts" class="form-control @error('sts') is-invalid @enderror" required>
                                    <option value="1" {{ $kelas->sts == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $kelas->sts == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('sts')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="foto">Foto</label>
                                <input type="file" name="foto"
                                    class="form-control @error('foto') is-invalid @enderror">
                                @if ($kelas->foto)
                                    <img src="{{ url('uploads/kelas/' . $kelas->foto) }}" alt="Foto Kelas"
                                        style="width: 100px; height: auto;">
                                @endif
                                @error('foto')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('master_kelas.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
