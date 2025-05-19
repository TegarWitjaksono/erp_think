@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit User</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('master_user.update', $user->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password (Biarkan kosong jika tidak ingin diubah)</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan password baru">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role" id="role"
                                            class="form-control @error('role') is-invalid @enderror">
                                            <option value="0" {{ old('role', $user->role) == '0' ? 'selected' : '' }}>
                                                Siswa</option>
                                            <option value="1" {{ old('role', $user->role) == '1' ? 'selected' : '' }}>
                                                Guru</option>
                                            <option value="2" {{ old('role', $user->role) == '2' ? 'selected' : '' }}>
                                                Admin</option>
                                        </select>
                                        @error('role')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="nip-group">
                                        <label for="nip">NIP</label>
                                        <input type="text" name="nip" id="nip"
                                            class="form-control @error('nip') is-invalid @enderror"
                                            value="{{ old('nip', 
                                                $user->role == 0 ? optional($user->masterSiswa)->nip : 
                                                ($user->role == 1 ? optional($user->masterGuru)->nip : 
                                                $user->nip)) }}"
                                            required>
                                        @error('nip')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="jurusan-group">
                                        <label for="id_jurusan">Jurusan</label>
                                        <select name="id_jurusan" id="jurusan"
                                            class="form-control @error('id_jurusan') is-invalid @enderror">
                                            <option value="">Pilih Jurusan</option>
                                            @foreach ($jurusan as $jrs)
                                                <option value="{{ $jrs->id_jurusan }}"
                                                    {{ $user->id_jurusan == $jrs->id_jurusan ? 'selected' : '' }}>
                                                    {{ $jrs->nama_jurusan }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_jurusan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="kelas-group">
                                        <label for="id_kelas">Kelas</label>
                                            <select name="id_kelas" id="kelas"
                                                class="form-control @error('id_kelas') is-invalid @enderror">
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($kelas as $kls)
                                                    <option value="{{ $kls->id_kelas }}"
                                                        {{ (old('id_kelas', optional($user->masterSiswa)->id_kelas) == $kls->id_kelas) ? 'selected' : '' }}>
                                                        {{ $kls->nama_kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @error('id_kelas')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('master_user.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            function toggleFunc() {
                var role = $('#role').val();
                $('#jurusan-group').hide();
                $('#kelas-group').hide();
                
                // Show NIP field for all roles
                $('#nip-group').show();
                
                // Update NIP label based on role
                if (role == '0') {
                    $('#jurusan-group').show();
                    $('#kelas-group').show();
                    $('label[for="nip"]').text('NIP'); // Changed to NIK for students
                    $('#nip').attr('placeholder', 'Masukkan NIP Siswa');
                } else if (role == '1') {
                    $('label[for="nip"]').text('NIP');
                    $('#nip').attr('placeholder', 'Masukkan NIP Guru');
                } else {
                    $('label[for="nip"]').text('NIP');
                    $('#nip').attr('placeholder', 'Masukkan NIP Admin');
                }
            }
            
            toggleFunc();

            // Call when user changes Role
            $('#role').on('change', function() {
                toggleFunc();
            });
        });
    </script>
@endsection
