@extends('dashboard')

@section('konten')
<style>
    /* Existing styles... */
    
    /* Responsive table styles */
    .table-responsive {
        margin-bottom: 1rem;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Custom scrollbar for better UX */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Ensure buttons in action column stay in one line */
    .btn-group-sm > .btn, .btn-sm {
        padding: .25rem .5rem;
        font-size: .875rem;
        line-height: 1.5;
        border-radius: .2rem;
    }
    
    /* Responsive text handling */
    .text-nowrap {
        white-space: nowrap !important;
    }
    
    @media screen and (max-width: 768px) {
        .table td, .table th {
            padding: .5rem;
        }
        
        .btn-sm {
            padding: .2rem .4rem;
            font-size: .775rem;
        }
    }

    /* Lebar kolom */
    th.soal             { width: 25%; min-width: 150px; }
    th.materi           { width: 10%; min-width: 80px; }
    th.kategori-soal    { width: 8%;  min-width: 80px; }
    th.kategori-jawaban { width: 8%;  min-width: 80px; }
    th.pilihan          { width: 5%;  min-width: 50px; }
    th.jawaban          { width: 7%;  min-width: 60px; }
    th.bobot            { width: 5%;  min-width: 50px; }
    th.status           { width: 7%;  min-width: 60px; }
    th.aksi             { width: 10%; min-width: 80px; }
</style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Soal</h1>
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
                <!-- Tombol Tambah Soal -->
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahSoalModal">
                    Tambah Soal
                </button>
                <!-- Tombol Ekspor -->
                <a href="{{ route('soal.export') }}" class="btn btn-success mb-3">Export Excel</a>
                <!-- Tombol Impor -->
                <button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#importSoalModal">
                    Import Excel
                </button>
                <a href="{{ route('soal.exportTemplate') }}" class="btn btn-warning mb-3 text-white">Download Template</a>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Soal
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0">
                            <table id="tabel-data" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="soal">Soal</th>
                                        <th class="materi">Materi</th>
                                        <th class="kategori-soal">Kategori Soal</th>
                                        <th class="kategori-jawaban">Kategori Jawaban</th>
                                        <th class="pilihan">Pilihan 1</th>
                                        <th class="pilihan">Pilihan 2</th>
                                        <th class="pilihan">Pilihan 3</th>
                                        <th class="pilihan">Pilihan 4</th>
                                        <th class="jawaban">Jawaban</th>
                                        <th class="bobot">Bobot</th>
                                        <th class="status">Status</th>
                                        <th class="aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($soal as $s)
                                        <tr>
                                            <td>
                                                @if (Str::startsWith($s->soal, 'uploads/') && Str::endsWith($s->soal, ['.jpg', '.jpeg', '.png', '.gif']))
                                                    <img src="{{ asset($s->soal) }}" alt="Soal"
                                                        style="max-width: 100px;">
                                                @elseif (Str::endsWith($s->soal, ['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx']))
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2">{{ basename($s->soal) }}</span>
                                                        <a href="{{ asset($s->soal) }}" target="_blank"
                                                            class="btn btn-sm btn-info" title="Lihat Dokumen">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                    </div>
                                                @elseif (Str::endsWith($s->soal, ['.mp3', '.wav', '.ogg']))
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2">{{ basename($s->soal) }}</span>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            data-toggle="modal" data-target="#audioModal{{ $s->id_soal }}"
                                                            title="Putar Audio">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Audio Modal -->
                                                    <div class="modal fade" id="audioModal{{ $s->id_soal }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="audioModalLabel{{ $s->id_soal }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="audioModalLabel{{ $s->id_soal }}">
                                                                        Putar Audio Soal
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <div class="audio-container">
                                                                        <audio controls>
                                                                            <source src="{{ asset($s->soal) }}"
                                                                                type="audio/{{ pathinfo($s->soal, PATHINFO_EXTENSION) }}">
                                                                            Browser Anda tidak mendukung pemutaran audio.
                                                                        </audio>
                                                                    </div>
                                                                    <p class="mt-2">{{ basename($s->soal) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif (filter_var($s->soal, FILTER_VALIDATE_URL))
                                                    <a href="{{ $s->soal }}" target="_blank">Lihat Soal</a>
                                                @else
                                                    {{ $s->soal }}
                                                @endif
                                            </td>
                                            <td>{{ $s->materi->judul ?? 'N/A' }}</td>
                                            <td>{{ $s->kategoriSoal->nama_kategori ?? 'N/A' }}</td>
                                            <td>{{ $s->kategoriJawaban->nama_kategori ?? 'N/A' }}</td>
                                            <!-- Update the display of pilihan_1, pilihan_2, pilihan_3, and pilihan_4 in the table -->
                                            @for ($i = 1; $i <= 4; $i++)
                                                <td>
                                                    @php
                                                        $pilihan = $s->{'pilihan_' . $i};
                                                        // Check if it's a file path
if (
    preg_match(
        '/^\d+_\d+\.(jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|ppt|pptx|mp3|wav|ogg)$/i',
        $pilihan,
    ) ||
    preg_match(
        '/^\d+_[1-4]\.(jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|ppt|pptx|mp3|wav|ogg)$/i',
        $pilihan,
    )
) {
    // Determine folder based on file extension
    $extension = strtolower(
        pathinfo($pilihan, PATHINFO_EXTENSION),
    );
    if (
        in_array($extension, [
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'ppt',
            'pptx',
        ])
    ) {
        $pilihan = 'uploads/dokumen/' . $pilihan;
    } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
        $pilihan = 'uploads/audio/' . $pilihan;
    } else {
        $pilihan = 'uploads/jawaban/' . $pilihan;
                                                            }
                                                        }
                                                    @endphp

                                                    @if (Str::endsWith($pilihan, ['.jpg', '.jpeg', '.png', '.gif']))
                                                        <img src="{{ asset($pilihan) }}" alt="Pilihan {{ $i }}"
                                                            style="max-width: 100px;">
                                                    @elseif (Str::endsWith($pilihan, ['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx']))
                                                        <a href="{{ asset($pilihan) }}" target="_blank"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-file-alt"></i>
                                                            {{ Str::afterLast($pilihan, '/') }}
                                                        </a>
                                                    @elseif (Str::endsWith($pilihan, ['.mp3', '.wav', '.ogg']))
                                                        <div class="audio-container">
                                                            <audio controls style="max-width: 200px; height: 30px;">
                                                                <source src="{{ asset($pilihan) }}"
                                                                    type="audio/{{ Str::afterLast($pilihan, '.') }}">
                                                                Browser Anda tidak mendukung pemutaran audio.
                                                            </audio>
                                                            <small class="d-block mt-1 text-muted">
                                                                {{ Str::afterLast($pilihan, '/') }}
                                                            </small>
                                                        </div>
                                                    @elseif (filter_var($pilihan, FILTER_VALIDATE_URL))
                                                        <a href="{{ $pilihan }}" target="_blank"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-link"></i> Lihat Link
                                                        </a>
                                                    @else
                                                        {{ $pilihan }}
                                                    @endif
                                                </td>
                                            @endfor
                                            <td>{{ $s->jawaban }}</td>
                                            <td>{{ $s->bobot == 0 ? 'Soal Tidak Berbobot' : $s->bobot }}</td>
                                            <td>{{ $s->sts == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                                            <td>
                                                <a href="{{ route('master_soal.edit', $s->id_soal) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" 
                                                    data-target="#deleteModal{{ $s->id_soal }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $s->id_soal }}" tabindex="-1" 
                                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Jika Anda menekan tombol hapus maka data akan terhapus.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                                                <form action="{{ route('master_soal.destroy', $s->id_soal) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
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
        </section>
    </div>

    <!-- Modal Tambah Soal -->
    <div class="modal fade" id="tambahSoalModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Soal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_soal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- <div class="form-group">
                            <label for="id_materi">Materi</label>
                            <select name="id_materi" class="form-control" required>
                                <option value="">Pilih Materi</option>
                                @foreach ($materi as $m)
                                    <option value="{{ $m->id_materi }}">{{ $m->judul }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="form-group">
                            <label for="id_kategori_soal">Kategori Soal</label>
                            <select name="id_kategori_soal" class="form-control" required
                                onchange="updateSoalField(this.options[this.selectedIndex].text)">
                                <option value="">Pilih Kategori Soal</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="soalField">
                            <label for="soal">Soal</label>
                            <textarea name="soal" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="id_kategori_jawaban">Kategori Jawaban</label>
                            <select name="id_kategori_jawaban" class="form-control" required
                                onchange="updateJawabanFields(this.options[this.selectedIndex].text)">
                                <option value="">Pilih Kategori Jawaban</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="pilihan1Field">
                            <label for="pilihan_1">Pilihan 1</label>
                            <input type="text" name="pilihan_1" class="form-control" required>
                        </div>
                        <div class="form-group" id="pilihan2Field">
                            <label for="pilihan_2">Pilihan 2</label>
                            <input type="text" name="pilihan_2" class="form-control" required>
                        </div>
                        <div class="form-group" id="pilihan3Field">
                            <label for="pilihan_3">Pilihan 3</label>
                            <input type="text" name="pilihan_3" class="form-control" required>
                        </div>
                        <div class="form-group" id="pilihan4Field">
                            <label for="pilihan_4">Pilihan 4</label>
                            <input type="text" name="pilihan_4" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="jawaban">Jawaban</label>
                            <select name="jawaban" class="form-control" required>
                                <option value="1">Pilihan 1</option>
                                <option value="2">Pilihan 2</option>
                                <option value="3">Pilihan 3</option>
                                <option value="4">Pilihan 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sts">Status</label>
                            <select name="sts" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control" required
                                onchange="toggleBobot()">
                                <option value="1">Bobot</option>
                                <option value="0">Tidak Berbobot</option>
                            </select>
                        </div>

                        <div class="form-group" id="bobot-group">
                            <label for="bobot">Bobot</label>
                            <input type="number" name="bobot" id="bobot" class="form-control" required>
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

    <!-- Import Modal -->
    <div class="modal fade" id="importSoalModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Soal</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('soal.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>File Excel</label>
                            <input type="file" name="file" class="form-control" required>
                            <small class="text-muted">Download template:
                                <a href="{{ route('soal.exportTemplate') }}">
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
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            responsive: true,
            scrollX: true,
            autoWidth: false,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { responsivePriority: 1, targets: [0, 1, 8] }, // Columns that will be always visible
                { responsivePriority: 2, targets: [2, 3] },    // Second priority
                { responsivePriority: 3, targets: '_all' }     // Rest of the columns
            ]
        });
    });
</script>
    <script>
        function updateSoalField(kategori) {
            const soalField = document.getElementById('soalField');

            switch (kategori.toLowerCase()) {
                case 'text':
                    soalField.innerHTML = `
                        <label for="soal">Soal</label>
                        <textarea name="soal" class="form-control" required></textarea>
                    `;
                    break;
                case 'foto':
                    soalField.innerHTML = `
                        <label for="soal">Soal</label>
                        <input type="file" name="soal" class="form-control" accept="image/*" required>
                    `;
                    break;
                case 'video':
                    soalField.innerHTML = `
                        <label for="soal">Soal</label>
                        <input type="url" name="soal" class="form-control" placeholder="Link Video" required>
                    `;
                    break;
                case 'link':
                    soalField.innerHTML = `
                        <label for="soal">Soal (Link)</label>
                        <input type="url" name="soal" class="form-control" placeholder="Masukkan URL" required>
                        <small class="text-muted">Contoh: https://www.example.com</small>
                    `;
                    break;
                case 'document':
                    soalField.innerHTML = `
                        <label for="soal">Soal (Document)</label>
                        <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                    `;
                    break;
                case 'audio':
                    soalField.innerHTML = `
                        <label for="soal">Soal (Audio)</label>
                        <input type="file" name="soal" class="form-control" accept="audio/*,.mp3,.wav,.ogg" required>
                        <small class="text-muted">Format yang didukung: MP3, WAV, OGG</small>
                    `;
                    break;
            }
        }

        function updateJawabanFields(kategori) {
            const fields = ['pilihan1Field', 'pilihan2Field', 'pilihan3Field', 'pilihan4Field'];

            fields.forEach((fieldId, index) => {
                const field = document.getElementById(fieldId);
                const number = index + 1;

                switch (kategori.toLowerCase()) {
                    case 'text':
                        field.innerHTML = `
                            <label for="pilihan_${number}">Pilihan ${number}</label>
                            <input type="text" name="pilihan_${number}" class="form-control" required>
                        `;
                        break;
                    case 'foto':
                        field.innerHTML = `
                            <label for="pilihan_${number}">Pilihan ${number}</label>
                            <input type="file" name="pilihan_${number}" class="form-control" accept="image/*" required>
                        `;
                        break;
                    case 'video':
                        field.innerHTML = `
                            <label for="pilihan_${number}">Pilihan ${number}</label>
                            <input type="url" name="pilihan_${number}" class="form-control" placeholder="Link Video" required>
                        `;
                        break;
                    case 'link':
                        field.innerHTML = `
                            <label for="pilihan_${number}">Pilihan ${number}</label>
                            <input type="url" name="pilihan_${number}" class="form-control" placeholder="Masukkan URL" required>
                            <small class="text-muted">Contoh: https://www.example.com</small>
                        `;
                        break;
                    case 'document':
                        field.innerHTML = `
                            <label for="pilihan_${number}">Pilihan ${number}</label>
                            <input type="file" name="pilihan_${number}" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                            <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                        `;
                        break;
                    case 'audio':
                        field.innerHTML = `
                            <label for="pilihan_${number}">Pilihan ${number}</label>
                            <input type="file" name="pilihan_${number}" class="form-control" accept="audio/*,.mp3,.wav,.ogg" required>
                            <small class="text-muted">Format yang didukung: MP3, WAV, OGG</small>
                        `;
                        break;
                }
            });
        }

        function toggleBobot() {
            const type = document.getElementById('type').value;
            const bobotGroup = document.getElementById('bobot-group');
            const bobotInput = document.getElementById('bobot');

            if (type === '1') {
                bobotGroup.style.display = 'block';
                bobotInput.required = true;
            } else {
                bobotGroup.style.display = 'none';
                bobotInput.value = 0;
                bobotInput.required = false;
            }
        }

        // Inisialisasi saat load pertama
        window.onload = toggleBobot;
    </script>

    <style>
        .audio-container {
            max-width: 250px;
        }

        .audio-container audio {
            width: 100%;
            height: 30px;
        }

        .btn-sm {
            margin: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .btn-sm i {
            margin-right: 4px;
        }

        td {
            vertical-align: middle !important;
        }

        .d-flex {
            display: flex !important;
        }

        .align-items-center {
            align-items: center !important;
        }

        .mr-2 {
            margin-right: 0.5rem !important;
        }

        /* Audio modal styling */
        .audio-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .audio-container audio {
            width: 100%;
            height: 40px;
        }

        /* Document and audio buttons */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .btn-sm i {
            font-size: 0.875rem;
        }

        /* Truncate long filenames */
        td span {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
    </style>
@endsection
