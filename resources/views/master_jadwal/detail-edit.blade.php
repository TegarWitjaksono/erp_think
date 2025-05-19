@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Detail Jadwal</h1>
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
                        Edit Detail Jadwal
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_jadwal.detail-update', base64_encode($data->id_detail_jadwal)) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="type">Pilih Tipe</label>
                                <select name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="0">Pilih</option>
                                    <option value="1" {{ $data->id_materi ? 'selected' : '' }}>Materi</option>
                                    <option value="2" {{ $data->id_quiz ? 'selected' : '' }}>Quiz</option>
                                </select>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" id="materiField" style="{{ $data->id_materi ? '' : 'display: none;' }}">
                                <label for="id_materi">Materi</label>
                                <select name="id_materi" class="form-control @error('id_materi') is-invalid @enderror">
                                    <option value="">Pilih Materi</option>
                                    @foreach ($materi as $mtr)
                                        <option value="{{ $mtr->id_materi }}"
                                            {{ $data->id_materi == $mtr->id_materi ? 'selected' : '' }}>
                                            {{ $mtr->judul }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_materi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" id="quizField" style="{{ $data->id_quiz ? '' : 'display: none;' }}">
                                <label for="id_quiz">Quiz</label>
                                <select name="id_quiz" class="form-control @error('id_quiz') is-invalid @enderror">
                                    <option value="">Pilih Quiz</option>
                                    @foreach ($quiz as $qz)
                                        <option value="{{ $qz->id_quiz }}"
                                            {{ $data->id_quiz == $qz->id_quiz ? 'selected' : '' }}>
                                            {{ $qz->nama_quiz }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_quiz')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jam_in">Jam Masuk</label>
                                <input type="time" name="jam_in"
                                    class="form-control @error('jam_in') is-invalid @enderror" value="{{ $data->jam_in }}"
                                    required>
                                @error('jam_in')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jam_out">Jam Keluar</label>
                                <input type="time" name="jam_out"
                                    class="form-control @error('jam_out') is-invalid @enderror"
                                    value="{{ $data->jam_out }}" required>
                                @error('jam_out')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="sts">Status</label>
                                <select name="sts" class="form-control @error('sts') is-invalid @enderror" required>
                                    <option value="1" {{ $data->sts == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $data->sts == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('sts')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('master_jadwal.detail', base64_encode($data->id_jadwal)) }}"
                                    class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="text/javascript">
        $('#type').change(function() {
            var type = $(this).val();
            if (type == '1') {
                $('#materiField').show();
                $('#quizField').hide();
            } else if (type == '2') {
                $('#materiField').hide();
                $('#quizField').show();
            } else {
                $('#materiField').hide();
                $('#quizField').hide();
            }
        });

        $(document).ready(function() {
            var type = $('#type').val();
            $('#materiField').hide();
            $('#quizField').hide();
            if (type == '1') {
                $('#materiField').show();
            } else if (type == '2') {
                $('#quizField').show();
            }
        });

        setTimeout(function() {
            $('.floating-alert').alert('close');
        }, 2000);
    </script>
@endsection
