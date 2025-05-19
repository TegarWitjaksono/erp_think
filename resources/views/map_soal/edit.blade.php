@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Master Map Soal</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="text-center mt-3" id="loading" class="loading" style="display: none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('master_map_soal.update', $map->id_map) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Dropdown Pilih Soal -->
                                    <div class="form-group">
                                        <label for="id_soal">Pilih Soal</label>
                                        <select name="id_soal" id="id_soal"
                                            class="form-control @error('id_soal') is-invalid @enderror">
                                            @foreach ($soals as $soal)
                                                <option value="{{ $soal->id_soal }}"
                                                    {{ $map->id_soal == $soal->id_soal ? 'selected' : '' }}
                                                    data-type="{{ $soal->id_kategori_soal === 2
                                                        ? 'Gambar'
                                                        : ($soal->id_kategori_soal === 3
                                                            ? 'Video'
                                                            : ($soal->id_kategori_soal === 4
                                                                ? 'Link'
                                                                : ($soal->id_kategori_soal === 5
                                                                    ? 'Document'
                                                                    : ($soal->id_kategori_soal === 6
                                                                        ? 'Audio'
                                                                        : $soal->soal)))) }}"
                                                    data-isi="{{ $soal->soal }}"
                                                    data-image="{{ $soal->id_kategori_soal === 2 ? asset($soal->soal) : '' }}"
                                                    data-audio="{{ $soal->id_kategori_soal === 6 ? asset($soal->soal) : '' }}"
                                                    data-pdf="{{ $soal->id_kategori_soal === 4 ? asset($soal->soal) : '' }}"
                                                    data-docx="{{ $soal->id_kategori_soal === 5 ? asset($soal->soal) : '' }}"
                                                    data-video="{{ $soal->id_kategori_soal === 3 ? asset($soal->soal) : '' }}">
                                                    {{ $soal->id_kategori_soal == 2
                                                        ? 'Gambar'
                                                        : ($soal->id_kategori_soal == 3
                                                            ? 'Video'
                                                            : ($soal->id_kategori_soal == 4
                                                                ? 'Link'
                                                                : ($soal->id_kategori_soal == 5
                                                                    ? 'Document'
                                                                    : ($soal->id_kategori_soal == 6
                                                                        ? 'Audio'
                                                                        : $soal->soal)))) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_soal')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Preview Gambar -->
                                    <div class="form-group" id="previewImageContainer" style="display: none;">
                                        <label for="previewImageLabel">Preview Gambar</label>
                                        <div>
                                            <img id="previewImage" src="" alt="Preview Gambar"
                                                style="max-width: 100%;">
                                        </div>
                                    </div>

                                    <!-- Preview Audio -->
                                    <div class="form-group" id="previewAudioContainer" style="display: none;">
                                        <label for="previewAudioLabel">Preview Audio</label>
                                        <audio id="previewAudio" controls>
                                            <source id="audioSource" src="" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>

                                    <!-- Preview PDF -->
                                    <div class="form-group" id="previewPdfContainer" style="display: none;">
                                        <label for="previewPdfLabel">Preview PDF</label>
                                        <div>
                                            <embed id="previewPdf" src="" width="100%" height="400px"
                                                type="application/pdf">
                                        </div>
                                    </div>

                                    <!-- Preview PDF / DOCX -->
                                    <div class="form-group" id="previewDocContainer" style="display: none;">
                                        <label for="previewDocLabel">Preview PDF / DOCX</label>
                                        <div>
                                            <iframe id="previewDoc" src="" width="100%" height="400px"></iframe>
                                        </div>
                                    </div>



                                    <!-- Preview Gambar -->
                                    <div class="form-group" id="previewImageContainer" style="display: none;">
                                        <label for="previewImageLabel">Preview Gambar</label>
                                        <div>
                                            <img id="previewImage" src="" alt="Preview Gambar"
                                                style="max-width: 100%;">
                                        </div>
                                    </div>

                                    <!-- Dropdown Tipe (Materi/Quiz) -->
                                    <div class="form-group">
                                        <label for="tipe">Tipe</label>
                                        <select id="tipe" name="tipe" class="form-control">
                                            <option value="">Pilih Tipe</option>
                                            <option value="Materi" {{ $map->id_materi ? 'selected' : '' }}>Materi</option>
                                            <option value="Quiz" {{ $map->id_quiz ? 'selected' : '' }}>Quiz/Kelas
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Dropdown Isi (Materi/Quiz) -->
                                    <div class="form-group">
                                        <label for="isi">Quiz/Materi</label>
                                        <select id="isi" class="form-control"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('master_map_soal.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        /* Form Container Styles */
        .content-wrapper {
            background: #f8f9fa;
            padding: 20px;
        }

        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }

        .card-body {
            padding: 2rem;
        }

        /* Form Group Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* Select Styles */
        .form-control,
        .select2-container--default .select2-selection--single {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            height: auto;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
        }

        /* Preview Image Container */
        #previewImageContainer {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-top: 1rem;
        }

        #previewImage {
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #4a90e2;
            border: none;
            box-shadow: 0 2px 4px rgba(74, 144, 226, 0.2);
        }

        .btn-primary:hover {
            background: #357abd;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(74, 144, 226, 0.2);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            box-shadow: 0 2px 4px rgba(108, 117, 125, 0.2);
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* Loading Spinner */
        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #4a90e2;
        }

        /* Error Message Styling */
        .text-danger {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 38px;
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>

    <!-- Include Select2 JS (jika menggunakan Select2) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Tangani perubahan pada dropdown soal
            $('#id_soal').change(function() {
                var selectedOption = $(this).find('option:selected');
                var type = selectedOption.data('type');
                var isi = selectedOption.data('isi');
                var imageUrl = selectedOption.data('image');
                var audioUrl = selectedOption.data('audio');
                var pdfUrl = selectedOption.data('pdf');
                var docxUrl = selectedOption.data('docx');
                var videoUrl = selectedOption.data('video');

                // Reset semua preview
                $('#previewImageContainer').hide();
                $('#previewAudioContainer').hide();
                $('#previewPDFContainer').hide();
                $('#previewDocContainer').hide();
                $('#previewVideoContainer').hide();

                // Menampilkan preview sesuai dengan tipe
                if (type === 'Gambar') {
                    $('#previewImage').attr('src', imageUrl);
                    $('#previewImageContainer').show();
                } else if (type === 'Audio') {
                    $('#previewAudio').attr('src', audioUrl);
                    $('#previewAudioContainer').show();
                } else if (type === 'PDF' || type === 'Document') {
                    // Tampilkan preview PDF atau DOCX
                    var fileUrl = pdfUrl || docxUrl;
                    $('#previewDoc').attr('src', fileUrl);
                    $('#previewDocContainer').show();
                } else if (type === 'Video') {
                    $('#previewVideo').attr('src', videoUrl);
                    $('#previewVideoContainer').show();
                } else {
                    // Untuk tipe Teks, tidak ada preview
                    $('#previewText').text(isi);
                }
            });

            // Trigger change event saat halaman dimuat (untuk menampilkan preview jika sudah ada yang terpilih)
            $('#id_soal').trigger('change');

            // Tangani perubahan pada dropdown tipe
            $('#tipe').change(function() {
                let tipe = $(this).val();
                let $isiDropdown = $("#isi");
                let $loading = $("#loading");

                if (tipe) {
                    $loading.show(); // Menampilkan loading spinner
                    $.ajax({
                        url: '/master_map_soal/data', // Laravel backend route
                        type: 'GET',
                        data: {
                            tipe: tipe
                        },
                        dataType: 'json',
                        success: function(response) {
                            $isiDropdown.empty().append('<option value="">Pilih Isi</option>');

                            $.each(response, function(index, item) {
                                let displayText = tipe === 'Quiz' ? item.nama_quiz :
                                    item.judul;
                                let idDisplay = tipe === 'Quiz' ? item.id_quiz : item
                                    .id_materi;
                                $isiDropdown.append('<option value="' + idDisplay +
                                    '">' + displayText + '</option>');
                            });

                            // Set nilai lama jika ada
                            let oldValue = tipe === 'Materi' ? '{{ $map->id_materi }}' :
                                '{{ $map->id_quiz }}';
                            if (oldValue) {
                                $isiDropdown.val(oldValue);
                            }
                            // Ubah atribut name dropdown isi berdasarkan tipe
                            if (tipe === 'Materi') {
                                $isiDropdown.attr('name', 'id_materi'); // Jika tipe Materi
                            } else if (tipe === 'Quiz') {
                                $isiDropdown.attr('name', 'id_quiz'); // Jika tipe Quiz
                            }
                        },
                        error: function() {
                            alert('Gagal mengambil data.');
                        },
                        complete: function() {
                            $loading.hide(); // Menyembunyikan loading spinner
                            $isiDropdown.prop("disabled", false);
                        }
                    });
                } else {
                    $isiDropdown.empty().append('<option value="">Pilih Isi</option>');
                }
            });

            // Trigger change event saat halaman dimuat
            $('#tipe').trigger('change');
        });
    </script>
@endsection
