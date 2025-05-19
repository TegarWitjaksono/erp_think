@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Materi</h1>
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
                <!-- Tombol Tambah Materi -->
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahMateriModal">
                    Tambah Materi
                </button>
                <!-- Tombol Ekspor -->
                <a href="{{ route('materi.export') }}" class="btn btn-success mb-3">Export Excel</a>
                <!-- Tombol Impor -->
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#importMateriModal">
                    Import Excel
                </button>
                <a href="{{ route('materi.template') }}" class="btn btn-danger mb-3">Template Excel</a>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Materi
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Kelas</th>
                                        <th>Kategori</th>
                                        <th>Logo</th>
                                        <th>File Materi</th>
                                        <th>Status</th>
                                        <th>Durasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materi as $mtr)
                                        <tr>
                                            <td>{{ Str::limit($mtr->judul, 25) }}</td>
                                            <td>{{ Str::limit($mtr->deskripsi, 25) }}</td>
                                            <td>{{ $mtr->kelas ? $mtr->kelas->nama_kelas : 'Tidak ada kelas' }}</td>
                                            <td>{{ $mtr->kategori ? $mtr->kategori->nama_kategori : 'Tidak ada kategori' }}
                                            </td>

                                            <!-- Tampilkan Logo -->
                                            <td>
                                                <img src="{{ asset('uploads/logo/' . $mtr->img) }}" width="50">
                                            </td>

                                            <!-- Tampilkan File Materi -->
                                            <td>
                                                @if ($mtr->kategori && $mtr->kategori->nama_kategori == 'foto')
                                                    @if (file_exists(public_path('uploads/materi/' . $mtr->file_materi)))
                                                        <a href="{{ asset('uploads/materi/' . $mtr->file_materi) }}"
                                                            target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> Lihat Foto
                                                        </a>
                                                    @else
                                                        <span class="text-danger">Foto tidak ditemukan</span>
                                                    @endif
                                                @elseif($mtr->kategori && $mtr->kategori->nama_kategori == 'text')
                                                    <p>{{ Str::limit($mtr->file_materi, 25) }}</p>
                                                @elseif($mtr->kategori && $mtr->kategori->nama_kategori == 'video')
                                                    <a href="{{ $mtr->file_materi }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-video"></i> Tonton Video
                                                    </a>
                                                @elseif($mtr->kategori && $mtr->kategori->nama_kategori == 'link')
                                                    <a href="{{ $mtr->file_materi }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-link"></i> Kunjungi Link
                                                    </a>
                                                @elseif($mtr->kategori && $mtr->kategori->nama_kategori == 'document')
                                                    <a href="{{ asset('uploads/dokumen/' . $mtr->file_materi) }}"
                                                        target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file-pdf"></i> Buka Dokumen
                                                    </a>
                                                @elseif($mtr->kategori && $mtr->kategori->nama_kategori == 'audio')
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2">{{ basename($mtr->file_materi) }}</span>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            data-toggle="modal"
                                                            data-target="#audioModal{{ $mtr->id_materi }}"
                                                            title="Putar Audio">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal fade" id="audioModal{{ $mtr->id_materi }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="audioModalLabel{{ $mtr->id_materi }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="audioModalLabel{{ $mtr->id_materi }}">
                                                                        Putar Audio Materi
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <div class="audio-container">
                                                                        <audio controls>
                                                                            <source
                                                                                src="{{ asset('uploads/audio/' . $mtr->file_materi) }}"
                                                                                type="audio/{{ pathinfo($mtr->file_materi, PATHINFO_EXTENSION) }}">
                                                                            Browser Anda tidak mendukung pemutaran audio.
                                                                        </audio>
                                                                    </div>
                                                                    <p class="mt-2">{{ basename($mtr->file_materi) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $mtr->sts == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                                            <td>{{ $mtr->durasi }}</td>
                                            <td>
                                                <a href="{{ route('master_materi.edit', $mtr->id_materi) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteModal{{ $mtr->id_materi }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="deleteModal{{ $mtr->id_materi }}"
                                                    tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Jika Anda menekan tombol hapus maka data akan terhapus.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Kembali</button>
                                                                <form
                                                                    action="{{ route('master_materi.destroy', $mtr->id_materi) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah Materi -->
    <div class="modal fade" id="tambahMateriModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Materi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_materi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" name="judul"
                                        class="form-control @error('judul') is-invalid @enderror"
                                        value="{{ old('judul') }}" required>
                                    @error('judul')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="id_kelas">Kelas</label>
                                    <select name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror"
                                        required>
                                        <option>Pilih</option>
                                        @foreach ($kelas as $kls)
                                            <option value="{{ $kls->id_kelas }}"
                                                {{ old('id_kelas') == $kls->id_kelas ? 'selected' : '' }}>
                                                {{ $kls->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_kelas')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_kategori">Kategori</label>
                                    <select name="id_kategori" id="id_kategori"
                                        class="form-control @error('id_kategori') is-invalid @enderror" required>
                                        <option>Pilih</option>
                                        @foreach ($kategori as $ktg)
                                            <option value="{{ $ktg->id_kategori }}"
                                                data-type="{{ $ktg->nama_kategori }}"
                                                {{ old('id_kategori') == $ktg->id_kategori ? 'selected' : '' }}>
                                                {{ $ktg->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sts">Status</label>
                                    <select name="sts" class="form-control @error('sts') is-invalid @enderror"
                                        required>
                                        <option value="1" {{ old('sts') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('sts') == '0' ? 'selected' : '' }}>Tidak Aktif
                                        </option>
                                    </select>
                                    @error('sts')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="durasi">Durasi</label>
                                    <input type="text" name="durasi"
                                        class="form-control @error('durasi') is-invalid @enderror"
                                        value="{{ old('durasi') }}" required>
                                    @error('durasi')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="file">Logo</label>
                                    <input type="file" name="img"
                                        class="form-control @error('img') is-invalid @enderror">
                                    @error('img')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <img id="fotoPreview" src="" class="mt-2" width="200"
                                        style="display: none; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                                </div>

                                <!-- Input tambahan berdasarkan kategori -->
                            </div>

                            <div class="col-md-12">
                                <div id="additionalInput"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Impor Materi -->
    <div class="modal fade" id="importMateriModal" tabindex="-1" role="dialog"
        aria-labelledby="importMateriModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importMateriModalLabel">Import Data Materi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('materi.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File Excel</label>
                            <input type="file" name="file"
                                class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Download template:
                                <a href="{{ route('materi.template') }}">
                                    <i class="fas fa-download"></i> Template Excel
                                </a>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.datatable').DataTable({
            responsive: true
        });
    </script>
    <script>
        $(document).ready(function() {
            // Preview gambar sebelum upload
            $('input[name="img"]').change(function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('fotoPreview');
                    output.src = reader.result;
                    output.style.display = 'block';
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        });
        $(document).ready(function() {
            setTimeout(function() {
                $('.floating-alert').alert('close');
            }, 2000);
        });
    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#tambahMateriModal').modal('show');
            });
        </script>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kategoriSelect = document.getElementById("id_kategori");
            const additionalInput = document.getElementById("additionalInput");

            kategoriSelect.addEventListener("change", function() {
                const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
                const kategori = selectedOption.getAttribute("data-type");

                additionalInput.innerHTML = ""; // Clear additional input
                let inputField = "";

                switch (kategori) {
                    case "foto":
                        inputField = `
                            <div class="form-group">
                                <label for="file_materi">Upload Foto atau Materi</label>
                                <input type="file" name="file_materi" class="form-control" id="file_materi" accept="image/*">
                                <span class="text-danger" id="error_file_materi"></span>
                            </div>
                        `;
                        break;

                    case "text":
                        inputField = `
                            <div class="form-group">
                                <label for="file_materi">Isi Materi</label>
                                <textarea name="file_materi" class="form-control" rows="4" id="file_materi"></textarea>
                                <span class="text-danger" id="error_file_materi"></span>
                            </div>
                        `;
                        break;

                    case "video":
                        inputField = `
                            <div class="form-group">
                                <label for="file_materi">Embed Video YouTube</label>
                                <input type="url" name="file_materi" class="form-control" id="file_materi" placeholder="Masukkan URL embed YouTube">
                                <span class="text-danger" id="error_file_materi"></span>
                            </div>
                        `;
                        break;

                    case "link":
                        inputField = `
                            <div class="form-group">
                                <label for="file_materi">Masukkan Link</label>
                                <input type="url" name="file_materi" class="form-control" id="file_materi" placeholder="Masukkan URL">
                                <span class="text-danger" id="error_file_materi"></span>
                            </div>
                        `;
                        break;

                    case "document":
                        inputField = `
                            <div class="form-group">
                                <label for="file_materi">Upload Dokumen</label>
                                <input type="file" name="file_materi" class="form-control" id="file_materi" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                <small class="form-text text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                                <span class="text-danger" id="error_file_materi"></span>
                            </div>
                        `;
                        break;

                    case "audio":
                        inputField = `
                            <div class="form-group">
                                <label for="file_materi">Upload Audio</label>
                                <input type="file" name="file_materi" class="form-control" id="file_materi" accept="audio/*,.mp3,.wav,.ogg">
                                <small class="form-text text-muted">Format yang didukung: MP3, WAV, OGG</small>
                                <span class="text-danger" id="error_file_materi"></span>
                                <div id="audioPreview" class="mt-2" style="display: none;">
                                    <audio controls style="width: 100%;">
                                        <source src="" type="audio/mpeg">
                                        Browser Anda tidak mendukung pemutaran audio.
                                    </audio>
                                </div>
                            </div>
                        `;
                        break;
                }

                additionalInput.innerHTML = inputField;

                // Add validation and preview for audio files
                if (kategori === "audio") {
                    const audioInput = document.getElementById("file_materi");
                    const audioPreview = document.getElementById("audioPreview");

                    audioInput.addEventListener("change", function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const audioURL = URL.createObjectURL(file);
                            const audio = audioPreview.querySelector("audio");
                            audio.src = audioURL;
                            audioPreview.style.display = "block";
                        }
                    });
                }

                // Add form validation
                document.querySelector("form").addEventListener("submit", function(event) {
                    const fileMateri = document.getElementById("file_materi");
                    const errorFileMateri = document.getElementById("error_file_materi");
                    let isValid = true;

                    switch (kategori) {
                        case "foto":
                            if (fileMateri.files.length === 0) {
                                errorFileMateri.textContent = "Harap unggah file foto.";
                                isValid = false;
                            }
                            break;

                        case "document":
                            if (fileMateri.files.length === 0) {
                                errorFileMateri.textContent = "Harap unggah file dokumen.";
                                isValid = false;
                            }
                            break;

                        case "audio":
                            if (fileMateri.files.length === 0) {
                                errorFileMateri.textContent = "Harap unggah file audio.";
                                isValid = false;
                            }
                            break;

                        case "text":
                            if (fileMateri.value.trim() === "") {
                                errorFileMateri.textContent = "Isi materi tidak boleh kosong.";
                                isValid = false;
                            }
                            break;

                        case "video":
                        case "link":
                            if (fileMateri.value.trim() === "") {
                                errorFileMateri.textContent = "Masukkan URL.";
                                isValid = false;
                            }
                            break;
                    }

                    if (!isValid) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk memuat durasi audio
            function loadAudioDurations() {
                document.querySelectorAll('.audio-duration').forEach(element => {
                    const audio = new Audio(element.dataset.src);
                    audio.addEventListener('loadedmetadata', function() {
                        const duration = formatTime(audio.duration);
                        element.textContent = duration;
                    });
                });
            }

            // Format waktu dari detik ke menit:detik
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
            }

            // Panggil fungsi saat halaman dimuat
            loadAudioDurations();

            // Custom audio player controls
            document.querySelectorAll('.custom-audio-player').forEach(player => {
                // Tambahkan event listener untuk custom controls jika diperlukan
                player.addEventListener('play', function() {
                    console.log('Audio started playing');
                });

                // Add loading state
                player.addEventListener('loadstart', function() {
                    this.closest('.audio-player').classList.add('loading');
                });

                player.addEventListener('canplay', function() {
                    this.closest('.audio-player').classList.remove('loading');
                });
            });
        });
    </script>

    <style>
        /* Audio player container */
        .audio-player {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            max-width: 300px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Audio element */
        .audio-player audio {
            width: 100%;
            height: 32px;
            margin-bottom: 4px;
        }

        /* Audio info area */
        .audio-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            color: #666;
        }

        .audio-filename {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .audio-duration {
            font-family: monospace;
            color: #191970;
        }

        /* Custom audio controls styling */
        audio::-webkit-media-controls-panel {
            background-color: #ffffff;
        }

        audio::-webkit-media-controls-play-button {
            background-color: #191970;
            border-radius: 50%;
        }

        audio::-webkit-media-controls-current-time-display,
        audio::-webkit-media-controls-time-remaining-display {
            color: #333333;
        }

        audio::-webkit-media-controls-timeline {
            background-color: #e9ecef;
            border-radius: 2px;
            height: 3px;
        }

        /* Loading state */
        .audio-player.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #191970;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        /* Existing styles... */

        .audio-preview {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }

        .audio-preview audio {
            width: 100%;
            height: 32px;
            margin-bottom: 8px;
        }

        .audio-preview .btn {
            display: block;
            width: 100%;
        }

        audio::-webkit-media-controls-panel {
            background-color: #fff;
        }

        audio::-webkit-media-controls-play-button {
            background-color: #007bff;
            border-radius: 50%;
        }

        audio::-webkit-media-controls-current-time-display,
        audio::-webkit-media-controls-time-remaining-display {
            color: #333;
        }

        .form-group small.text-muted {
            display: block;
            margin-top: 5px;
            font-size: 0.875rem;
        }
    </style>
@endsection
