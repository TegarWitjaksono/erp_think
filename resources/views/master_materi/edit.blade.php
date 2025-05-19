@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Materi</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('master_materi.update', $materi->id_materi) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="judul">Judul</label>
                                        <input type="text" name="judul" class="form-control"
                                            value="{{ old('judul', $materi->judul) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="img">Logo</label>
                                        <input type="file" name="img" class="form-control"
                                            id="imgInput"value="{{ old('img', $materi->img) }}">
                                        <br>
                                        <img id="fotoPreview" src="{{ asset('uploads/logo/' . $materi->img) }}"
                                            width="200"
                                            style="border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_kelas">Kelas</label>
                                        <select name="id_kelas" class="form-control" required>
                                            @foreach ($kelas as $kls)
                                                <option value="{{ $kls->id_kelas }}"
                                                    {{ $materi->id_kelas == $kls->id_kelas ? 'selected' : '' }}>
                                                    {{ $kls->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="id_kategori">Kategori</label>
                                        <select id="kategoriSelect" name="id_kategori" class="form-control" required>
                                            @foreach ($kategori as $kat)
                                                <option value="{{ $kat->id_kategori }}"
                                                    {{ $materi->id_kategori == $kat->id_kategori ? 'selected' : '' }}>
                                                    {{ $kat->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="sts">Status</label>
                                        <select name="sts" class="form-control" required>
                                            <option value="1" {{ $materi->sts == '1' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="0" {{ $materi->sts == '0' ? 'selected' : '' }}>Tidak Aktif
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group" id="fileMateri">
                                        <label for="file_materi">File Materi</label>

                                        @if ($materi->kategori->nama_kategori == 'text')
                                            <textarea name="file_materi" class="form-control" rows="6">{{ old('file_materi', $materi->file_materi) }}</textarea>
                                        @elseif ($materi->kategori->nama_kategori == 'foto')
                                            <input type="file" name="file_materi" class="form-control">
                                            @if($materi->file_materi)
                                                <img src="{{ asset('uploads/materi/' . $materi->file_materi) }}" class="mt-2" width="200">
                                            @endif
                                        @elseif ($materi->kategori->nama_kategori == 'document')
                                            <input type="file" name="file_materi" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                            <small class="form-text text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                                            @if($materi->file_materi)
                                                <div class="mt-2">
                                                    <p>Dokumen saat ini: {{ $materi->file_materi }}</p>
                                                    <a href="{{ asset('uploads/dokumen/' . $materi->file_materi) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file-alt"></i> Lihat Dokumen
                                                    </a>
                                                </div>
                                            @endif
                                        @elseif ($materi->kategori->nama_kategori == 'audio')
                                            <input type="file" name="file_materi" class="form-control" accept="audio/*,.mp3,.wav,.ogg">
                                            <small class="form-text text-muted">Format yang didukung: MP3, WAV, OGG</small>
                                            @if($materi->file_materi)
                                                <div class="mt-2">
                                                    <audio controls style="max-width: 200px;">
                                                        <source src="{{ asset('uploads/audio/' . $materi->file_materi) }}" type="audio/{{ pathinfo($materi->file_materi, PATHINFO_EXTENSION) }}">
                                                        Browser Anda tidak mendukung pemutaran audio.
                                                    </audio>
                                                    <p class="text-muted small mt-2">Audio saat ini: {{ $materi->file_materi }}</p>
                                                </div>
                                            @endif
                                        @elseif ($materi->kategori->nama_kategori == 'link')
                                            <input type="url" name="file_materi" class="form-control" placeholder="Masukkan URL Link" 
                                                value="{{ old('file_materi', $materi->file_materi) }}" required>
                                        @else
                                            <input type="url" name="file_materi" class="form-control" placeholder="Masukkan URL Video"
                                                value="{{ old('file_materi', $materi->file_materi) }}" required>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="durasi">durasi</label>
                                        <input type="text" name="durasi" class="form-control"
                                            value="{{ old('durasi', $materi->durasi) }}" required>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('master_materi.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('fotoPreview').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }

            function toggleFileInput() {
                let kategoriSelect = document.getElementById("kategoriSelect");
                let fileMateriDiv = document.getElementById("fileMateri");
                let selectedKategori = kategoriSelect.options[kategoriSelect.selectedIndex].text.toLowerCase();

                // Get initial values from PHP
                let initialKategori = "{{ $materi->kategori->nama_kategori }}".toLowerCase();
                let initialValue = "{{ old('file_materi', $materi->file_materi) }}";

                // Get old value before element is removed
                let oldValue = document.querySelector("[name='file_materi']") ? document.querySelector("[name='file_materi']").value : '';

                // Check if selected category is initial category
                let isInitialKategori = selectedKategori === initialKategori;
                let valueToUse = isInitialKategori ? initialValue : '';

                fileMateriDiv.innerHTML = ''; // Clear previous input
                let inputField = "";

                if (selectedKategori === 'text') {
                    inputField = `
                        <label for="file_materi">File Materi</label>
                        <textarea name="file_materi" class="form-control" required>${valueToUse}</textarea>
                    `;
                } else if (selectedKategori === 'foto') {
                    inputField = `
                        <label for="file_materi">Upload File</label>
                        <input type="file" name="file_materi" class="form-control">
                        ${valueToUse ? `<img src="{{ asset('uploads/materi/') }}/${valueToUse}" class="mt-2" width="200">` : ''}
                    `;
                } else if (selectedKategori === 'video') {
                    inputField = `
                        <label for="file_materi">Link Video</label>
                        <input type="url" name="file_materi" class="form-control" placeholder="Masukkan URL Video" value="${valueToUse}" required>
                    `;
                } else if (selectedKategori === 'link') {
                    inputField = `
                        <label for="file_materi">Link</label>
                        <input type="url" name="file_materi" class="form-control" placeholder="Masukkan URL Link" value="${valueToUse}" required>
                    `;
                } else if (selectedKategori === 'document') {
                    inputField = `
                        <label for="file_materi">Upload Dokumen</label>
                        <input type="file" name="file_materi" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <small class="form-text text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                        ${valueToUse ? `
                            <div class="mt-2">
                                <p>Dokumen saat ini: ${valueToUse}</p>
                                <a href="{{ asset('uploads/dokumen/') }}/${valueToUse}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-alt"></i> Lihat Dokumen
                                </a>
                            </div>` : ''
                        }
                    `;
                } else if (selectedKategori === 'audio') {
                    inputField = `
                        <label for="file_materi">Upload Audio</label>
                        <input type="file" name="file_materi" class="form-control" accept="audio/*,.mp3,.wav,.ogg">
                        <small class="form-text text-muted">Format yang didukung: MP3, WAV, OGG</small>
                        ${valueToUse ? `
                            <div class="mt-2">
                                <audio controls style="max-width: 200px;">
                                    <source src="{{ asset('uploads/audio/') }}/${valueToUse}" type="audio/${valueToUse.split('.').pop()}">
                                    Browser Anda tidak mendukung pemutaran audio.
                                </audio>
                                <p class="text-muted small mt-2">Audio saat ini: ${valueToUse}</p>
                            </div>` : ''
                        }
                    `;
                }

                fileMateriDiv.innerHTML = `
                    <div class="form-group">
                        ${inputField}
                        <span class="text-danger" id="error_file_materi"></span>
                    </div>
                `;
            }

            // Event listener untuk perubahan kategori
            document.getElementById('kategoriSelect').addEventListener('change', toggleFileInput);

            // Jalankan saat halaman dimuat
            toggleFileInput();

        });
    </script>
@endsection
