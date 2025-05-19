@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Quiz</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('master_quiz.update', $quiz->id_quiz) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_quiz">Nama Quiz</label>
                                        <input type="text" class="form-control @error('nama_quiz') is-invalid @enderror"
                                            id="nama_quiz" name="nama_quiz" value="{{ old('nama_quiz', $quiz->nama_quiz) }}"
                                            required>
                                        @error('nama_quiz')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="desc">Deskripsi</label>
                                        <textarea class="form-control @error('desc') is-invalid @enderror" id="desc" name="desc" rows="4"
                                            required>{{ old('desc', $quiz->desc) }}</textarea>
                                        @error('desc')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="typ">Tipe Quiz</label>
                                        <select class="form-control @error('typ') is-invalid @enderror" id="typ"
                                            name="typ" required>
                                            <option value="1" {{ $quiz->typ == 'Quiz' ? 'selected' : '' }}>Quiz
                                            </option>
                                            <option value="2" {{ $quiz->typ == 'Ujian' ? 'selected' : '' }}>Ujian
                                            </option>
                                        </select>
                                        @error('typ')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sts">Status</label>
                                        <select class="form-control @error('sts') is-invalid @enderror" id="sts"
                                            name="sts" required>
                                            <option value="1" {{ $quiz->sts == 1 ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ $quiz->sts == 0 ? 'selected' : '' }}>Tidak Aktif
                                            </option>
                                        </select>
                                        @error('sts')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="durasi">Durasi</label>
                                        <input type="text" class="form-control @error('durasi') is-invalid @enderror"
                                            id="durasi" name="durasi" value="{{ old('durasi', $quiz->durasi) }}"
                                            required>
                                        @error('durasi')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="icon">Icon</label>
                                        <input type="file" class="form-control-file @error('icon') is-invalid @enderror"
                                            id="icon" name="icon">
                                        @error('icon')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @if ($quiz->icon)
                                            <br>
                                            <img id="iconPreview" src="{{ asset('uploads/quiz/' . $quiz->icon) }}"
                                                class="img-thumbnail mt-2" width="100">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('master_quiz.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            $('input[name="icon"]').change(function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('iconPreview');
                    output.src = reader.result;
                    output.style.display = 'block';
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        });
    </script>
@endsection
