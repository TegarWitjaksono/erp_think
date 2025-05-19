@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Soal</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Edit Soal
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_soal.update', $soal->id_soal) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="id_materi">Materi</label>
                                <select name="id_materi" class="form-control" required>
                                    <option value="">Pilih Materi</option>
                                    @foreach ($materi as $m)
                                        <option value="{{ $m->id_materi }}"
                                            {{ $soal->id_materi == $m->id_materi ? 'selected' : '' }}>{{ $m->judul }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_kategori_soal">Kategori Soal</label>
                                <select name="id_kategori_soal" class="form-control" required
                                    onchange="updateSoalField(this.options[this.selectedIndex].text)">
                                    <option value="">Pilih Kategori Soal</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id_kategori }}"
                                            {{ $soal->id_kategori_soal == $kat->id_kategori ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="soalField">
                                <label for="soal">Soal</label>
                                @if ($soal->kategoriSoal->nama_kategori == 'text')
                                    <textarea name="soal" class="form-control" required>{{ $soal->soal }}</textarea>
                                @elseif ($soal->kategoriSoal->nama_kategori == 'foto')
                                    <input type="file" name="soal" class="form-control" accept="image/*">
                                    <input type="hidden" name="current_soal" value="{{ $soal->soal }}">
                                    @if (Str::startsWith($soal->soal, 'uploads/') && file_exists(public_path($soal->soal)))
                                        <div class="mt-2">
                                            <img src="{{ asset($soal->soal) }}" alt="Soal" style="max-width: 200px;">
                                            <p class="text-muted small">Pilih gambar baru untuk mengganti gambar ini, atau
                                                biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                        </div>
                                    @endif
                                @elseif ($soal->kategoriSoal->nama_kategori == 'document')
                                    <input type="file" name="soal" class="form-control"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                    <input type="hidden" name="current_soal" value="{{ $soal->soal }}">
                                    <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT,
                                        PPTX</small>
                                    @php
                                        $dokumen = $soal->soal;
                                        if (preg_match('/^\d+_\d+\.(pdf|doc|docx|xls|xlsx|ppt|pptx)$/i', $dokumen)) {
                                            $dokumen = 'uploads/dokumen/' . $dokumen;
                                        }
                                    @endphp
                                    @if (file_exists(public_path($dokumen)))
                                        <div class="mt-2">
                                            <a href="{{ asset($dokumen) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-file-alt"></i> Lihat Dokumen
                                            </a>
                                            <p class="text-muted small">Pilih dokumen baru untuk mengganti dokumen ini, atau
                                                biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                        </div>
                                    @endif
                                @elseif ($soal->kategoriSoal->nama_kategori == 'audio')
                                    <input type="file" name="soal" class="form-control"
                                        accept="audio/*,.mp3,.wav,.ogg">
                                    <input type="hidden" name="current_soal" value="{{ $soal->soal }}">
                                    <small class="text-muted">Format yang didukung: MP3, WAV, OGG</small>
                                    @php
                                        $audio = $soal->soal;
                                        if (preg_match('/^\d+_\d+\.(mp3|wav|ogg)$/i', $audio)) {
                                            $audio = 'uploads/audio/' . $audio;
                                        }
                                    @endphp
                                    @if (file_exists(public_path($audio)))
                                        <div class="mt-2">
                                            <audio controls style="max-width: 200px;">
                                                <source src="{{ asset($audio) }}"
                                                    type="audio/{{ pathinfo($audio, PATHINFO_EXTENSION) }}">
                                                Browser Anda tidak mendukung pemutaran audio.
                                            </audio>
                                            <p class="text-muted small">Pilih file audio baru untuk mengganti yang sudah
                                                ada, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                        </div>
                                    @endif
                                @elseif ($soal->kategoriSoal->nama_kategori == 'video')
                                    <input type="url" name="soal" class="form-control" placeholder="Link Video"
                                        value="{{ $soal->soal }}" required>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="id_kategori_jawaban">Kategori Jawaban</label>
                                <select name="id_kategori_jawaban" class="form-control" required
                                    onchange="updateJawabanFields(this.options[this.selectedIndex].text)">
                                    <option value="">Pilih Kategori Jawaban</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id_kategori }}"
                                            {{ $soal->id_kategori_jawaban == $kat->id_kategori ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @for ($i = 1; $i <= 4; $i++)
                                <div class="form-group" id="pilihan{{ $i }}Field">
                                    <label for="pilihan_{{ $i }}">Pilihan {{ $i }}</label>
                                    @if ($soal->kategoriJawaban->nama_kategori == 'text')
                                        <input type="text" name="pilihan_{{ $i }}" class="form-control"
                                            value="{{ $soal->{'pilihan_' . $i} }}" required>
                                    @elseif ($soal->kategoriJawaban->nama_kategori == 'foto')
                                        <input type="file" name="pilihan_{{ $i }}" class="form-control"
                                            accept="image/*">
                                        <input type="hidden" name="current_pilihan_{{ $i }}"
                                            value="{{ $soal->{'pilihan_' . $i} }}">
                                        @php
                                            $pilihan = $soal->{'pilihan_' . $i};
                                            if (preg_match('/^\d+_\d+\.(jpg|jpeg|png|gif)$/i', $pilihan)) {
                                                $pilihan = 'uploads/jawaban/' . $pilihan;
                                            }
                                        @endphp
                                        @if (file_exists(public_path($pilihan)))
                                            <div class="mt-2">
                                                <img src="{{ asset($pilihan) }}" alt="Pilihan {{ $i }}"
                                                    style="max-width: 200px;">
                                                <p class="text-muted small">Pilih gambar baru untuk mengganti gambar ini,
                                                    atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                            </div>
                                        @endif
                                    @elseif ($soal->kategoriJawaban->nama_kategori == 'document')
                                        <input type="file" name="pilihan_{{ $i }}" class="form-control"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                        <input type="hidden" name="current_pilihan_{{ $i }}"
                                            value="{{ $soal->{'pilihan_' . $i} }}">
                                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT,
                                            PPTX</small>
                                        @php
                                            $dokumen = $soal->{'pilihan_' . $i};
                                            if (
                                                preg_match('/^\d+_\d+\.(pdf|doc|docx|xls|xlsx|ppt|pptx)$/i', $dokumen)
                                            ) {
                                                $dokumen = 'uploads/dokumen/' . $dokumen;
                                            }
                                        @endphp
                                        @if (file_exists(public_path($dokumen)))
                                            <div class="mt-2">
                                                <a href="{{ asset($dokumen) }}" target="_blank"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-file-alt"></i> Lihat Dokumen
                                                </a>
                                                <p class="text-muted small">Pilih dokumen baru untuk mengganti dokumen ini,
                                                    atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                            </div>
                                        @endif
                                    @elseif ($soal->kategoriJawaban->nama_kategori == 'audio')
                                        <input type="file" name="pilihan_{{ $i }}" class="form-control"
                                            accept="audio/*,.mp3,.wav,.ogg">
                                        <input type="hidden" name="current_pilihan_{{ $i }}"
                                            value="{{ $soal->{'pilihan_' . $i} }}">
                                        <small class="text-muted">Format yang didukung: MP3, WAV, OGG</small>
                                        @php
                                            $audio = $soal->{'pilihan_' . $i};
                                            if (preg_match('/^\d+_\d+\.(mp3|wav|ogg)$/i', $audio)) {
                                                $audio = 'uploads/audio/' . $audio;
                                            }
                                        @endphp
                                        @if (file_exists(public_path($audio)))
                                            <div class="mt-2">
                                                <audio controls style="max-width: 200px;">
                                                    <source src="{{ asset($audio) }}"
                                                        type="audio/{{ pathinfo($audio, PATHINFO_EXTENSION) }}">
                                                    Browser Anda tidak mendukung pemutaran audio.
                                                </audio>
                                                <p class="text-muted small">Pilih file audio baru untuk mengganti yang
                                                    sudah ada, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                            </div>
                                        @endif
                                    @elseif ($soal->kategoriJawaban->nama_kategori == 'video')
                                        <input type="url" name="pilihan_{{ $i }}" class="form-control"
                                            placeholder="Link Video" value="{{ $soal->{'pilihan_' . $i} }}" required>
                                    @elseif ($soal->kategoriJawaban->nama_kategori == 'link')
                                        <input type="url" name="pilihan_{{ $i }}" class="form-control"
                                            placeholder="Masukkan URL" value="{{ $soal->{'pilihan_' . $i} }}" required>
                                        <small class="text-muted">Contoh: https://www.example.com</small>
                                    @endif
                                </div>
                            @endfor

                            <div class="form-group">
                                <label for="jawaban">Jawaban</label>
                                <select name="jawaban" class="form-control" required>
                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ $soal->jawaban == $i ? 'selected' : '' }}>
                                            Pilihan {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sts">Status</label>
                                <select name="sts" class="form-control" required>
                                    <option value="1" {{ $soal->sts == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $soal->sts == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control" required
                                    onchange="toggleBobot()">
                                    <option value="1" {{ $soal->type == 1 ? 'selected' : '' }}>Berbobot</option>
                                    <option value="0" {{ $soal->type == 0 ? 'selected' : '' }}>Tidak Berbobot
                                    </option>
                                </select>
                            </div>
                            <div class="form-group" id="bobot-group">
                                <label for="bobot">Bobot</label>
                                <input type="number" name="bobot" id="bobot" class="form-control"
                                    value="{{ $soal->bobot }}" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    onclick="window.location='{{ route('master_soal.index') }}'">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function updateSoalField(kategori) {
            const soalField = document.getElementById('soalField');
            const currentSoal = '{{ addslashes($soal->soal) }}';
            const kategoriLower = kategori.toLowerCase();

            switch (kategoriLower) {
                case 'text':
                    soalField.innerHTML = `
                    <label for="soal">Soal</label>
                    <textarea name="soal" class="form-control" required>${currentSoal}</textarea>
                `;
                    break;
                case 'foto':
                    soalField.innerHTML = `
                    <label for="soal">Soal</label>
                    <input type="file" name="soal" class="form-control" accept="image/*">
                    <input type="hidden" name="current_soal" value="${currentSoal}">
                    ${currentSoal ? `
                                                                        <div class="mt-2">
                                                                            <img src="{{ asset('') }}${currentSoal}" alt="Soal" style="max-width: 200px;">
                                                                            <p class="text-muted small">Pilih gambar baru untuk mengganti gambar ini, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                                                        </div>` : ''}
                `;
                    break;
                case 'document':
                    soalField.innerHTML = `
                    <label for="soal">Soal (Document)</label>
                    <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    <input type="hidden" name="current_soal" value="${currentSoal}">
                    <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                    ${currentSoal ? `
                                                                        <div class="mt-2">
                                                                            <a href="{{ asset('') }}${currentSoal}" target="_blank" class="btn btn-sm btn-info">
                                                                                <i class="fas fa-file-alt"></i> Lihat Dokumen
                                                                            </a>
                                                                            <p class="text-muted small">Pilih dokumen baru untuk mengganti dokumen ini, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                                                        </div>` : ''}
                `;
                    break;
                case 'audio':
                    soalField.innerHTML = `
                    <label for="soal">Soal (Audio)</label>
                    <input type="file" name="soal" class="form-control" accept="audio/*,.mp3,.wav,.ogg">
                    <input type="hidden" name="current_soal" value="${currentSoal}">
                    <small class="text-muted">Format yang didukung: MP3, WAV, OGG</small>
                    ${currentSoal ? `
                                                                        <div class="mt-2">
                                                                            <audio controls style="max-width: 200px;">
                                                                                <source src="{{ asset('') }}${currentSoal}" type="audio/${currentSoal.split('.').pop()}">
                                                                                Browser Anda tidak mendukung pemutaran audio.
                                                                            </audio>
                                                                            <p class="text-muted small">Pilih file audio baru untuk mengganti yang sudah ada, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                                                        </div>` : ''}
                `;
                    break;
                case 'video':
                    soalField.innerHTML = `
                    <label for="soal">Soal</label>
                    <input type="url" name="soal" class="form-control" placeholder="Link Video" value="${currentSoal}" required>
                `;
                    break;
            }
        }

        function updateJawabanFields(kategori) {
            @for ($i = 1; $i <= 4; $i++)
                const pilihan{{ $i }}Field = document.getElementById('pilihan{{ $i }}Field');
                const currentPilihan{{ $i }} = '{{ addslashes($soal->{'pilihan_' . $i}) }}';
            @endfor

            switch (kategori.toLowerCase()) {
                case 'text':
                    @for ($i = 1; $i <= 4; $i++)
                        pilihan{{ $i }}Field.innerHTML = `
                    <label for="pilihan_{{ $i }}">Pilihan {{ $i }}</label>
                    <input type="text" name="pilihan_{{ $i }}" class="form-control" value="${currentPilihan{{ $i }}}" required>
                `;
                    @endfor
                    break;

                case 'foto':
                    @for ($i = 1; $i <= 4; $i++)
                        let pilihan{{ $i }} = currentPilihan{{ $i }};
                        if (pilihan{{ $i }}.match(/^\d+_\d+\.(jpg|jpeg|png|gif)$/i) ||
                            pilihan{{ $i }}.match(/^\d+_[1-4]\.(jpg|jpeg|png|gif)$/i)) {
                            pilihan{{ $i }} = 'uploads/jawaban/' + pilihan{{ $i }};
                        }

                        pilihan{{ $i }}Field.innerHTML = `
                    <label for="pilihan_{{ $i }}">Pilihan {{ $i }}</label>
                    <input type="file" name="pilihan_{{ $i }}" class="form-control" accept="image/*">
                    <input type="hidden" name="current_pilihan_{{ $i }}" value="${currentPilihan{{ $i }}}">
                    ${pilihan{{ $i }} ? `
                                                                        <div class="mt-2">
                                                                            <img src="{{ asset('') }}${pilihan{{ $i }}}" alt="Pilihan {{ $i }}" style="max-width: 200px;">
                                                                            <p class="text-muted small">Pilih gambar baru untuk mengganti gambar ini, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                                                        </div>` : ''}
                `;
                    @endfor
                    break;

                case 'document':
                    @for ($i = 1; $i <= 4; $i++)
                        let doc{{ $i }} = currentPilihan{{ $i }};
                        if (doc{{ $i }}.match(/^\d+_\d+\.(pdf|doc|docx|xls|xlsx|ppt|pptx)$/i)) {
                            doc{{ $i }} = 'uploads/dokumen/' + doc{{ $i }};
                        }

                        pilihan{{ $i }}Field.innerHTML = `
                    <label for="pilihan_{{ $i }}">Pilihan {{ $i }} (Document)</label>
                    <input type="file" name="pilihan_{{ $i }}" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                    <input type="hidden" name="current_pilihan_{{ $i }}" value="${currentPilihan{{ $i }}}">
                    <small class="text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                    ${doc{{ $i }} ? `
                                                                        <div class="mt-2">
                                                                            <a href="{{ asset('') }}${doc{{ $i }}}" target="_blank" class="btn btn-sm btn-info">
                                                                                <i class="fas fa-file-alt"></i> Lihat Dokumen
                                                                            </a>
                                                                            <p class="text-muted small">Pilih dokumen baru untuk mengganti dokumen ini, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                                                        </div>` : ''}
                `;
                    @endfor
                    break;

                case 'audio':
                    @for ($i = 1; $i <= 4; $i++)
                        let audio{{ $i }} = currentPilihan{{ $i }};
                        if (audio{{ $i }}.match(/^\d+_\d+\.(mp3|wav|ogg)$/i)) {
                            audio{{ $i }} = 'uploads/audio/' + audio{{ $i }};
                        }

                        pilihan{{ $i }}Field.innerHTML = `
                    <label for="pilihan_{{ $i }}">Pilihan {{ $i }} (Audio)</label>
                    <input type="file" name="pilihan_{{ $i }}" class="form-control" accept="audio/*,.mp3,.wav,.ogg">
                    <input type="hidden" name="current_pilihan_{{ $i }}" value="${currentPilihan{{ $i }}}">
                    <small class="text-muted">Format yang didukung: MP3, WAV, OGG</small>
                    ${audio{{ $i }} ? `
                                                                        <div class="mt-2">
                                                                            <audio controls style="max-width: 200px;">
                                                                                <source src="{{ asset('') }}${audio{{ $i }}}" type="audio/${audio{{ $i }}.split('.').pop()}">
                                                                                Browser Anda tidak mendukung pemutaran audio.
                                                                            </audio>
                                                                            <p class="text-muted small">Pilih file audio baru untuk mengganti yang sudah ada, atau biarkan kosong untuk mempertahankan yang sudah ada.</p>
                                                                        </div>` : ''}
                `;
                    @endfor
                    break;

                case 'video':
                    @for ($i = 1; $i <= 4; $i++)
                        pilihan{{ $i }}Field.innerHTML = `
                    <label for="pilihan_{{ $i }}">Pilihan {{ $i }}</label>
                    <input type="url" name="pilihan_{{ $i }}" class="form-control" placeholder="Link Video" value="${currentPilihan{{ $i }}}" required>
                `;
                    @endfor
                    break;

                case 'link':
                    @for ($i = 1; $i <= 4; $i++)
                        pilihan{{ $i }}Field.innerHTML = `
                    <label for="pilihan_{{ $i }}">Pilihan {{ $i }} (Link)</label>
                    <input type="url" name="pilihan_{{ $i }}" class="form-control" placeholder="Masukkan URL" value="${currentPilihan{{ $i }}}" required>
                    <small class="text-muted">Contoh: https://www.example.com</small>
                `;
                    @endfor
                    break;
            }
        }

        function toggleBobot() {
            const type = document.getElementById('type').value;
            const bobotGroup = document.getElementById('bobot-group');
            const bobotInput = document.getElementById('bobot');

            if (type === '1') {
                bobotGroup.style.display = 'block';
                bobotInput.required = true;
                bobotInput.value = {{ $soal->bobot ?? 0 }};
            } else {
                bobotGroup.style.display = 'none';
                bobotInput.value = 0;
                bobotInput.required = false;
            }
        }

        // Inisialisasi saat load pertama
        window.onload = toggleBobot;
    </script>
@endsection
