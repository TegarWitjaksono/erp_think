<!-- View: master_jadwal.edit -->
@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Jadwal</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Edit Jadwal
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_jadwal.update', $jadwal->id_jadwal) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="hari">Hari</label>
                                <select name="hari" class="form-control @error('hari') is-invalid @enderror" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ $jadwal->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="Minggu" {{ $jadwal->hari == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                </select>
                                @error('hari')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="id_kelas">Kelas</label>
                                <select name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror"
                                    required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}"
                                            {{ $jadwal->id_kelas == $kls->id_kelas ? 'selected' : '' }}>
                                            {{ $kls->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kelas')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama_jadwal">Nama Jadwal</label>
                                <input type="text" name="nama_jadwal"
                                    class="form-control @error('nama_jadwal') is-invalid @enderror"
                                    value="{{ $jadwal->nama_jadwal }}" required>
                                @error('nama_jadwal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sts">Status</label>
                                <select name="sts" class="form-control @error('sts') is-invalid @enderror" required>
                                    <option value="1" {{ $jadwal->sts == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $jadwal->sts == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('sts')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="id_guru">Guru</label>
                                <select name="id_guru" class="form-control @error('id_guru') is-invalid @enderror">
                                    <option value="">Pilih Guru</option>
                                    @foreach ($gurus as $guru)
                                        <option value="{{ $guru->id_guru }}"
                                            {{ $jadwal->id_guru == $guru->id_guru ? 'selected' : '' }}>
                                            {{ $guru->nama_guru }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_guru')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('master_jadwal.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
