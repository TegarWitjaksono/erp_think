@extends('dashboard')

@section('konten')
    <style>
        .selected-soal-container {
            min-height: 100px;
            position: relative;
        }

        .selected-soal-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .selected-soal-list .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.75rem 1.25rem;
            transition: all 0.2s ease;
            border-left: 3px solid #007bff;
        }

        .selected-soal-list .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .selected-soal-list .soal-content {
            flex: 1;
            display: flex;
            align-items: flex-start;
        }

        .selected-soal-list .soal-content img {
            max-width: 80px;
            max-height: 60px;
            margin-right: 15px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            object-fit: contain;
        }

        .selected-soal-list .soal-text {
            flex: 1;
            font-size: 0.9rem;
        }

        .selected-soal-list .soal-type {
            background-color: #e9ecef;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.75rem;
            margin-left: 10px;
            white-space: nowrap;
            align-self: flex-start;
        }

        .selected-soal-list .remove-soal {
            margin-left: 10px;
            align-self: flex-start;
        }

        .empty-state {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
        }

        /* New styles for question options */
        .selected-soal-list .badge {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 3px;
        }

        .selected-soal-list .badge-success {
            background-color: #28a745;
            color: white;
        }

        /* New styles for options display */
        .options-container {
            margin-top: 8px;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }

        .option-item {
            margin-bottom: 4px;
            font-size: 0.85rem;
            color: #555;
        }

        .option-label {
            font-weight: bold;
            margin-right: 5px;
            display: inline-block;
            min-width: 20px;
        }

        .option-item.correct {
            color: #28a745;
            font-weight: 500;
        }

        /* Audio player styles */
        .soal-text audio {
            width: 100%;
            max-width: 250px;
            height: 40px;
            margin: 5px 0;
        }
    </style>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Map Soal</h1>
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
                <div class="text-center mt-3" id="loading" class="loading" style="display: none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <select id="tipe" class="form-control">
                            <option value="">Pilih Tipe</option>
                            <option value="Materi">Materi</option>
                            <option value="Quiz">Quiz/Kelas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="isi" class="form-control">
                            <option value="">Pilih Materi/Quiz</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="TipeSoal" class="form-control">
                            <option value="">Pilih Jenis Soal</option>
                            <option value="Bobot">Bobot</option>
                            <option value="Tidak">Tidak Bobot</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addSoal">
                            Tambah Map Soal
                        </button>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Tersedia
                    </div>
                    <div class="card-body">
                        <table id="tabel-data normal" class="table datatable normal table-hover text-nowrap ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tipe</th>
                                    <th>Soal</th>
                                    <th>Quiz/Materi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($maps as $map)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            @if ($map->id_quiz == 0)
                                                Materi
                                            @elseif($map->id_materi == 0)
                                                Quiz/Kelas
                                            @endif
                                        </td>
                                        <td>
                                            @if ($map->soal)
                                                @php
                                                    $soal = $map->soal->soal;
                                                    $extension = strtolower(pathinfo($soal, PATHINFO_EXTENSION));
                                                @endphp

                                                @if (filter_var($soal, FILTER_VALIDATE_URL))
                                                    <a href="{{ $soal }}" target="_blank">Lihat Soal</a>
                                                @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ asset($soal) }}" alt="Soal" style="max-width: 100px;">
                                                @elseif (in_array($extension, ['pdf']))
                                                    <a href="{{ asset($soal) }}" target="_blank">Lihat PDF</a>
                                                @elseif (in_array($extension, ['doc', 'docx']))
                                                    <a href="{{ asset($soal) }}" target="_blank">Download DOC/DOCX</a>
                                                @elseif (in_array($extension, ['mp3', 'wav', 'ogg']))
                                                    <audio controls>
                                                        <source src="{{ asset($soal) }}" type="audio/{{ $extension }}">
                                                        Browser anda tidak mendukung audio.
                                                    </audio>
                                                @else
                                                    {{ $soal }}
                                                @endif
                                            @else
                                                <span>Soal tidak tersedia</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $map->id_materi == 0 ? $map->quiz->nama_quiz : $map->materi->judul }}
                                        </td>

                                        <td>
                                            <a href="{{ route('master_map_soal.edit', $map->id_map) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal{{ $map->id_map }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $map->id_map }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        jika anda menekan tombol hapus maka data akan terhapus
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Kembali</button>
                                                        <form action="{{ route('master_map_soal.destroy', $map->id_map) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade" id="addSoal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Mapping Soal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('master_map_soal.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabel-data" class="table table-bordered datatable">
                                        <thead>
                                            <tr>
                                                <th>Soal</th>
                                                <th>Pilihan 1</th>
                                                <th>Pilihan 2</th>
                                                <th>Pilihan 3</th>
                                                <th>Pilihan 4</th>
                                                <th>Jawaban</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="soalTable">

                                        </tbody>
                                    </table>
                                </div>

                                <div class="card mt-4 mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-check-circle mr-2"></i> Soal yang dipilih</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="selected-soal-container">
                                            <ul id="selectedSoalList" class="list-group selected-soal-list"></ul>
                                            <div class="text-center py-3 empty-state" id="emptySelectedSoal">
                                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada soal yang dipilih</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <span class="badge badge-primary" id="selectedCount">0</span> soal dipilih
                                    </div>
                                </div>

                                <input type="hidden" name="selected_ids" id="selected_ids">
                                <div class="form-group">
                                    <label for="selected_tipe">Tipe yang di pilih</label>
                                    <input type="text" id="selected_tipe" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="selected_isi">Jenis yang di pilih</label>
                                    <input type="text" id="selected_isi" class="form-control" readonly>
                                    <input type="hidden" id="selected_id" name="selected_id">
                                </div>
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


    <!-- Include Select2 JS (jika menggunakan Select2) -->

    <script>
        $(document).ready(function() {
            let selectedSoals = []; // Array untuk menyimpan ID soal
            updateEmptyState(); // Initialize empty state

            // Event delegation agar tetap bisa menangkap event di semua pagination
            $(document).on("click", ".add-soal", function() {
                let row = $(this).closest("tr");
                let id = row.data("id");
                let soalText = row.find("td:first-child").html(); // Get the actual question content
                let pilihan1 = row.find("td:nth-child(2)").html(); // Get HTML content instead of text
                let pilihan2 = row.find("td:nth-child(3)").html();
                let pilihan3 = row.find("td:nth-child(4)").html();
                let pilihan4 = row.find("td:nth-child(5)").html();
                let jawaban = row.find("td:nth-child(6)").text();

                // Determine if it's an image, URL, audio, or text
                let isImage = soalText.includes('<img');
                let isUrl = soalText.includes('<a href');
                let isAudio = soalText.includes('<audio') || soalText.includes('.mp3') || soalText.includes(
                    '.wav');

                let type = isImage ? "Gambar" : (isAudio ? "Audio" : (isUrl ? "Link" : "Teks"));

                // Pastikan soal tidak duplikat
                if (!selectedSoals.includes(id)) {
                    selectedSoals.push(id);

                    let soalContent = '';

                    // Handle audio content
                    if (isAudio) {
                        let audioElement = '';

                        // Extract the audio element directly from HTML
                        if (soalText.includes('<audio')) {
                            // Clone the audio element to preserve all attributes and child nodes
                            let tempDiv = document.createElement('div');
                            tempDiv.innerHTML = soalText;
                            let audioNode = tempDiv.querySelector('audio');
                            if (audioNode) {
                                // Ensure controls attribute is set
                                audioNode.setAttribute('controls', '');
                                audioNode.style.maxWidth = '100%';
                                audioElement = audioNode.outerHTML;
                            }
                        } else {
                            // Create audio element for mp3/wav links
                            let audioUrl = '';
                            if (soalText.includes('.mp3') || soalText.includes('.wav')) {
                                // Extract URL from text or link
                                if (soalText.includes('<a href')) {
                                    let tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = soalText;
                                    let linkNode = tempDiv.querySelector('a');
                                    if (linkNode) {
                                        audioUrl = linkNode.getAttribute('href');
                                    }
                                } else {
                                    // Try to find URL pattern
                                    let urlMatch = soalText.match(/(https?:\/\/[^\s]+\.(mp3|wav))/g);
                                    if (urlMatch && urlMatch.length > 0) {
                                        audioUrl = urlMatch[0];
                                    }
                                }

                                if (audioUrl) {
                                    audioElement =
                                        `<audio controls style="max-width:100%"><source src="${audioUrl}" type="audio/${audioUrl.endsWith('mp3') ? 'mpeg' : 'wav'}"></audio>`;
                                }
                            }
                        }

                        // Format options, checking for images
                        let formattedOptions = formatOptions(pilihan1, pilihan2, pilihan3, pilihan4,
                            jawaban);

                        soalContent = `<div class="soal-content">
                            <div class="soal-text w-100">
                                <strong>Soal Audio:</strong>
                                <div class="my-2">${audioElement}</div>
                                <div class="options-container mt-2">
                                    ${formattedOptions}
                                </div>
                            </div>
                        </div>`;
                    }
                    // Handle image content
                    else if (isImage) {
                        // Extract the image and display it with the text
                        let imgElement = '';
                        let tempDiv = document.createElement('div');
                        tempDiv.innerHTML = soalText;
                        let imgNode = tempDiv.querySelector('img');
                        if (imgNode) {
                            imgNode.style.maxWidth = '80px';
                            imgNode.style.maxHeight = '60px';
                            imgNode.style.borderRadius = '4px';
                            imgNode.style.border = '1px solid #dee2e6';
                            imgElement = imgNode.outerHTML;
                        }

                        // Format options, checking for images
                        let formattedOptions = formatOptions(pilihan1, pilihan2, pilihan3, pilihan4,
                            jawaban);

                        soalContent = `<div class="soal-content">
                            ${imgElement}
                            <div class="soal-text">
                                <strong>Soal Gambar</strong>
                                <div class="options-container mt-2">
                                    ${formattedOptions}
                                </div>
                            </div>
                        </div>`;
                    }
                    // Handle URL/link content
                    else if (isUrl) {
                        // Extract the link and display it with text
                        let linkElement = '';
                        let tempDiv = document.createElement('div');
                        tempDiv.innerHTML = soalText;
                        let linkNode = tempDiv.querySelector('a');
                        if (linkNode) {
                            linkElement = linkNode.outerHTML;
                        }

                        // Format options, checking for images
                        let formattedOptions = formatOptions(pilihan1, pilihan2, pilihan3, pilihan4,
                            jawaban);

                        soalContent = `<div class="soal-content">
                            <div class="soal-text">
                                <strong>Soal Link:</strong> ${linkElement}
                                <div class="options-container mt-2">
                                    ${formattedOptions}
                                </div>
                            </div>
                        </div>`;
                    }
                    // Handle text content
                    else {
                        // Display text content
                        let textContent = $(soalText).text() || soalText;
                        // Truncate if too long
                        let truncatedText = textContent.length > 100 ?
                            textContent.substring(0, 100) + '...' :
                            textContent;

                        // Format options, checking for images
                        let formattedOptions = formatOptions(pilihan1, pilihan2, pilihan3, pilihan4,
                            jawaban);

                        soalContent = `<div class="soal-content">
                            <div class="soal-text">
                                <strong>Soal:</strong> ${truncatedText}
                                <div class="options-container mt-2">
                                    ${formattedOptions}
                                </div>
                            </div>
                        </div>`;
                    }

                    let listItem = `<li class="list-group-item" data-id="${id}">
                        ${soalContent}
                        <span class="soal-type">${type}</span>
                        <button type="button" class="btn btn-danger btn-sm remove-soal" data-id="${id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </li>`;

                    $("#selectedSoalList").append(listItem);
                    updateSelectedIds();
                    updateEmptyState();
                    updateSelectedCount();

                    // Sembunyikan baris setelah dipilih
                    row.hide();
                }
            });

            // Helper function to format options, handling both text and images
            function formatOptions(pilihan1, pilihan2, pilihan3, pilihan4, jawaban) {
                // Check if options contain images and format accordingly
                let options = [{
                        label: 'A',
                        content: pilihan1
                    },
                    {
                        label: 'B',
                        content: pilihan2
                    },
                    {
                        label: 'C',
                        content: pilihan3
                    },
                    {
                        label: 'D',
                        content: pilihan4
                    }
                ];

                let formattedOptions = '';

                options.forEach(option => {
                    let hasImage = option.content.includes('<img');
                    let optionContent = '';

                    if (hasImage) {
                        // Extract and format the image
                        let tempDiv = document.createElement('div');
                        tempDiv.innerHTML = option.content;
                        let imgNode = tempDiv.querySelector('img');
                        if (imgNode) {
                            imgNode.style.maxWidth = '60px';
                            imgNode.style.maxHeight = '40px';
                            imgNode.style.borderRadius = '3px';
                            imgNode.style.marginRight = '5px';
                            optionContent = imgNode.outerHTML;
                        } else {
                            optionContent = option.content;
                        }
                    } else {
                        optionContent = option.content;
                    }

                    let isCorrect = jawaban.trim() === option.label.trim() ||
                        jawaban.trim() === option.content.trim();

                    formattedOptions += `<div class="option-item ${isCorrect ? 'correct' : ''}">
                        <span class="option-label">${option.label}:</span> ${optionContent}
                    </div>`;
                });

                // Add the correct answer indicator
                formattedOptions += `<div class="option-item correct">
                    <span class="option-label">Jawaban:</span> ${jawaban}
                </div>`;

                return formattedOptions;
            }

            // Hapus soal dari daftar
            $(document).on("click", ".remove-soal", function() {
                let id = $(this).data("id");
                selectedSoals = selectedSoals.filter(soalId => soalId !== id);
                $(this).closest("li").remove();
                updateSelectedIds();
                updateEmptyState();
                updateSelectedCount();

                // Tampilkan kembali baris yang telah dihapus dari daftar pilihan
                $(`tr[data-id="${id}"]`).show();
            });

            // Fungsi untuk memperbarui input hidden
            function updateSelectedIds() {
                $("#selected_ids").val(JSON.stringify(selectedSoals)); // Simpan sebagai JSON
            }

            // Fungsi untuk memperbarui empty state
            function updateEmptyState() {
                if (selectedSoals.length > 0) {
                    $("#emptySelectedSoal").hide();
                } else {
                    $("#emptySelectedSoal").show();
                }
            }

            // Fungsi untuk memperbarui counter
            function updateSelectedCount() {
                $("#selectedCount").text(selectedSoals.length);
            }

            // Debugging sebelum form submit
            $("form").submit(function() {
                console.log("Data terkirim:", $("#selected_ids")
            .val()); // Pastikan data benar sebelum submit
            });

            // Inisialisasi DataTables dengan redraw pada perubahan halaman
            // let table = $('.datatable').DataTable({
            //     responsive: true
            // });

            // Ketika tabel dipaginasi ulang, pastikan baris yang sudah dipilih tetap tersembunyi
            table.on("draw", function() {
                selectedSoals.forEach(id => {
                    $(`tr[data-id="${id}"]`).hide();
                });
            });
        });
    </script>


    <script>
        setTimeout(function() {
            $('.floating-alert').alert('close');
        }, 2000);
    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#addSoal').modal('show');
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $("#tipe").change(function() {
                let tipe = $(this).val();
                let $isiDropdown = $("#isi");
                let $loading = $("#loading");

                if (tipe) {
                    $loading.show(); // Menampilkan loading spinner
                    $isiDropdown.prop("disabled", true);

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
                                    .id_materi
                                $isiDropdown.append('<option value="' + idDisplay +
                                    '">' +
                                    displayText + '</option>');
                            });
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
            $("#isi").change(function() {
                let selectedTipe = $("#tipe").val();
                let selectedIsi = $("#isi option:selected").text();
                let selectedId = $("#isi").val();

                $("#selected_tipe").val(selectedTipe);
                $("#selected_isi").val(selectedIsi);

                // Mengubah atribut name sesuai tipe yang dipilih
                let inputHidden = $("#selected_id");
                inputHidden.val(selectedId);

                if (selectedTipe === "Materi") {
                    inputHidden.attr("name", "id_materi");
                } else if (selectedTipe === "Quiz") {
                    inputHidden.attr("name", "id_quiz");
                }
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            // Inisialisasi awal DataTables
            let table = $('.datatable').DataTable({
                responsive: true
            });

            $('#TipeSoal').on('change', function() {
                let tipeSoal = $(this).val();
                let bobot = tipeSoal === 'Bobot' ? 1 : 0;

                $.ajax({
                    url: '/filter-soal',
                    type: 'GET',
                    data: {
                        bobot: bobot
                    },
                    success: function(response) {
                        // Hancurkan instance DataTable
                        table.destroy();

                        // Ganti isi tbody
                        $('#soalTable').html(response.html);

                        // Menghapus ID dan Class terkait DataTable
                        $('#normal').removeAttr('id');
                        $('.normal').removeClass('datatable');

                        // Re-inisialisasi DataTables
                        table = $('.datatable').DataTable({
                            responsive: true
                        });
                    },
                    error: function() {
                        alert('Gagal mengambil data soal.');
                    }
                });
            });
        });
    </script>
@endsection
