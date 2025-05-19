@extends('dashboard')
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3bc9db;
            --success: #0ca678;
            --warning: #f59f00;
            --danger: #e03131;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --border-radius: 8px;
            --box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .content-wrapper {
            background-color: #f8f9fc;
            background-image: linear-gradient(135deg, rgba(245, 247, 250, 0.8) 0%, rgba(195, 207, 226, 0.3) 100%);
            padding-bottom: 60px;
        }

        .page-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
            color: var(--gray-800);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 4px;
            width: 60px;
            background: var(--primary);
            border-radius: 2px;
        }

        .chat-form-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .chat-form-container:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .chat-form-container:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }

        .form-group {
            margin-bottom: 28px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 14px;
            transition: var(--transition);
        }

        .form-group:hover label {
            color: var(--primary);
        }

        .form-control {
            height: auto;
            padding: 14px 18px;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-size: 15px;
            background-color: var(--gray-100);
            color: var(--gray-800);
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            background-color: white;
        }

        .form-control::placeholder {
            color: var(--gray-500);
            opacity: 0.7;
        }

        .form-section {
            position: relative;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--gray-200);
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 20px;
            color: var(--gray-800);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .form-section-title i {
            margin-right: 12px;
            color: var(--primary);
            font-size: 22px;
            background: rgba(67, 97, 238, 0.1);
            padding: 10px;
            border-radius: 50%;
            height: 42px;
            width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            min-height: 50px;
            padding: 8px 12px;
            background-color: var(--gray-100);
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            background-color: white;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            font-size: 13px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }

        .select2-dropdown {
            border-color: var(--gray-300);
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary);
        }

        /* Custom File Input */
        .custom-file-container {
            position: relative;
            border: 2px dashed var(--gray-300);
            border-radius: var(--border-radius);
            padding: 30px 20px;
            text-align: center;
            transition: var(--transition);
            background-color: var(--gray-100);
            cursor: pointer;
        }

        .custom-file-container:hover {
            border-color: var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .custom-file-container i {
            font-size: 36px;
            color: var(--primary);
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .custom-file-container p {
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--gray-700);
        }

        .custom-file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .audio-preview {
            margin-top: 20px;
            padding: 15px;
            border-radius: var(--border-radius);
            background-color: var(--gray-100);
            display: none;
        }

        .audio-preview audio {
            width: 100%;
        }

        /* Checkbox Styling */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        .custom-checkbox {
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            font-weight: 500;
            display: inline-block;
            color: var(--gray-700);
        }

        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 22px;
            width: 22px;
            background-color: var(--gray-200);
            border-radius: 4px;
            transition: var(--transition);
        }

        .custom-checkbox:hover input ~ .checkmark {
            background-color: var(--gray-300);
        }

        .custom-checkbox input:checked ~ .checkmark {
            background-color: var(--primary);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .custom-checkbox input:checked ~ .checkmark:after {
            display: block;
        }

        .custom-checkbox .checkmark:after {
            left: 8px;
            top: 4px;
            width: 6px;
            height: 12px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .required-label:after {
            content: " *";
            color: var(--danger);
            font-weight: bold;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(45deg, var(--primary), #5a73f4);
            border: none;
            padding: 14px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: var(--transition);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            position: relative;
            overflow: hidden;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
            background: linear-gradient(45deg, #3b54d6, var(--primary));
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0) 100%);
            transform: translateX(-100%);
        }

        .btn-primary:hover::after {
            animation: shine 1.5s infinite;
        }

        @keyframes shine {
            100% {
                transform: translateX(100%);
            }
        }

        .submit-btn-wrapper {
            text-align: right;
            margin-top: 30px;
        }

        /* Helper Text */
        .text-muted {
            color: var(--gray-500) !important;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .chat-form-container {
                padding: 25px;
            }
            
            .form-section-title {
                font-size: 18px;
            }
            
            .form-section-title i {
                font-size: 18px;
                height: 36px;
                width: 36px;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status Indicator */
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-required {
            background-color: var(--danger);
        }

        .status-optional {
            background-color: var(--gray-400);
        }

        /* Form Legend */
        .form-legend {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-left: 15px;
            color: var(--gray-600);
        }
    </style>
@endsection

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold page-title fade-in">
                            <i class="fas fa-headphones-alt mr-2"></i>Chat Audio
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-legend">
                            <div class="legend-item">
                                <span class="status-indicator status-required"></span> Wajib diisi
                            </div>
                            <div class="legend-item">
                                <span class="status-indicator status-optional"></span> Opsional
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <form action="{{ url('chatsimpan') }}" method="POST" enctype="multipart/form-data" id="chatForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-10 mx-auto">
                            <div class="chat-form-container fade-in">
                                <!-- Detail Pesan Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">
                                        <i class="fas fa-info-circle"></i>Detail Pesan
                                    </h3>
                                    
                                    <div class="form-group">
                                        <label for="title" class="required-label">Judul</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul pesan...">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="message">Pesan Teks</label>
                                        <textarea name="message" id="message" class="form-control" rows="5" placeholder="Masukkan pesan teks (opsional)..."></textarea>
                                    </div>
                                </div>
                                
                                <!-- Audio Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">
                                        <i class="fas fa-microphone"></i>File Audio
                                    </h3>
                                    
                                    <div class="form-group">
                                        <label for="audio" class="required-label">Unggah Audio</label>
                                        <div class="custom-file-container" id="dropArea">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p>Drag & drop file audio atau klik untuk memilih</p>
                                            <small class="text-muted">Format yang didukung: MP3, WAV, AAC</small>
                                            <input type="file" class="custom-file-input" id="audio" name="audio" accept="audio/mp3,audio/wav,audio/aac">
                                        </div>
                                        <div class="audio-preview mt-3" id="audioPreview">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-file-audio text-primary mr-2"></i>
                                                <span id="fileName" class="font-weight-medium"></span>
                                                <button type="button" class="btn btn-sm btn-link text-danger ml-auto" id="removeAudio">
                                                    <i class="fas fa-times"></i> Hapus
                                                </button>
                                            </div>
                                            <audio controls class="w-100" id="audioPlayer"></audio>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Penerima Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">
                                        <i class="fas fa-users"></i>Penerima
                                    </h3>
                                    
                                    <div class="form-group">
                                        <label for="id_kelas" class="required-label">Kelas</label>
                                        <select name="id_kelas" id="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($kelas as $kls)
                                                <option value="{{ $kls->id_kelas }}">{{ $kls->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_kelas')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">Pilih kelas terlebih dahulu untuk menampilkan daftar murid</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="userid" class="required-label">Nama Murid</label>
                                        <select id="userid" name="userid[]" class="form-control" style="width: 100%;" multiple="multiple">
                                        </select>
                                        <small class="text-muted">Pilih satu atau lebih murid sebagai penerima</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="custom-checkbox">
                                            <input type="checkbox" id="select_all_siswa" checked>
                                            <span class="checkmark"></span>
                                            Pilih Semua Siswa
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="submit-btn-wrapper">
                                    <button type="button" class="btn btn-primary" id="send">
                                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            let allOptions = [];

            // Initialize Select2 for dropdowns
            $('#id_kelas').select2({
                placeholder: 'Pilih Kelas',
                allowClear: true,
                width: '100%',
                theme: 'classic'
            });

            $('#userid').select2({
                placeholder: 'Cari Pengguna...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route('chat.getuser') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            id_kelas: $('#id_kelas').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Drag and drop functionality
            const dropArea = document.getElementById('dropArea');
            const fileInput = document.getElementById('audio');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropArea.style.borderColor = '#4361ee';
                dropArea.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
            }

            function unhighlight() {
                dropArea.style.borderColor = '#dee2e6';
                dropArea.style.backgroundColor = '#f8f9fa';
            }

            dropArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                handleFiles(files);
            }

            function handleFiles(files) {
                if (files.length) {
                    updateFileInfo(files[0]);
                }
            }

            // File input change handler
            fileInput.addEventListener('change', function() {
                if (this.files.length) {
                    updateFileInfo(this.files[0]);
                }
            });

            // Update file info and preview
            function updateFileInfo(file) {
                const fileName = file.name;
                const fileUrl = URL.createObjectURL(file);
                
                $('#fileName').text(fileName);
                $('#audioPlayer').attr('src', fileUrl);
                $('#audioPreview').fadeIn();
                
                // Change drop area appearance
                dropArea.style.borderStyle = 'solid';
                dropArea.style.borderColor = '#4361ee';
                dropArea.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
            }

            // Remove audio button
            $('#removeAudio').on('click', function() {
                fileInput.value = '';
                $('#audioPreview').fadeOut();
                $('#audioPlayer').attr('src', '');
                
                // Reset drop area
                dropArea.style.borderStyle = 'dashed';
                dropArea.style.borderColor = '#dee2e6';
                dropArea.style.backgroundColor = '#f8f9fa';
            });

            function fetchUsers(id_kelas, callback) {
                $.ajax({
                    url: '{{ route('chat.getuser') }}',
                    data: {
                        id_kelas: id_kelas
                    },
                    dataType: 'json',
                    success: function(data) {
                        allOptions = data;
                        callback(data);
                    }
                });
            }

            // Saat kelas berubah
            $('#id_kelas').on('change', function() {
                let id_kelas = $(this).val();
                $('#userid').val(null).trigger('change');
                $('#userid').empty().trigger('change');

                if (id_kelas) {
                    $('#select_all_siswa').prop('checked', true);
                    fetchUsers(id_kelas, function() {
                        applyAllSelection();
                    });
                } else {
                    $('#select_all_siswa').prop('checked', false);
                    $('#userid').val(null).trigger('change');
                    $('#userid').empty().trigger('change');
                }
            });

            // Checkbox manual "Pilih Semua"
            $('#select_all_siswa').on('change', function() {
                let id_kelas = $('#id_kelas').val();

                if ($(this).is(':checked') && id_kelas) {
                    fetchUsers(id_kelas, function() {
                        applyAllSelection();
                    });
                } else {
                    $('#userid').val(null).trigger('change');
                    $('#userid').empty().trigger('change');
                }
            });

            function applyAllSelection() {
                let options = allOptions.map(user => new Option(user.text, user.id, true, true));
                $('#userid').append(options).trigger('change');
            }
        });
    </script>

    @if (session('message'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('message') }}',
                showConfirmButton: false,
                timer: 2000,
                customClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            })
        </script>
    @endif
    
    <script>
        document.getElementById('send').addEventListener('click', function(e) {
            e.preventDefault(); // Biar tidak langsung submit

            const title = document.querySelector('input[name="title"]').value.trim();
            const audio = document.querySelector('input[name="audio"]');
            const message = document.querySelector('textarea[name="message"]').value.trim();
            const userSelect = document.getElementById('userid');
            const selectedUsers = [...userSelect.selectedOptions].map(option => option.value);

            const allowedExtensions = ['mp3', 'wav', 'aac'];
            const file = audio.files[0];

            // Cek title
            if (!title) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Title wajib diisi!',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }

            // Cek user
            if (selectedUsers.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Minimal satu murid harus dipilih!',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }

            // Cek file audio
            if (!file) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'File audio wajib diunggah!',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }

            const fileExt = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExt)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format tidak didukung!',
                    text: 'File harus berformat .mp3, .wav, atau .aac!',
                    confirmButtonColor: '#4361ee'
                });
                // Kosongkan input file
                audio.value = '';
                return;
            }

            // Show loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            this.disabled = true;

            // Jika semua valid, submit form
            setTimeout(() => {
                document.getElementById('chatForm').submit();
            }, 800);
        });
    </script>
@endsection
