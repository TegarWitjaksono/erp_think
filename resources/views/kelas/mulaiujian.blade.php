@extends('depan.layout_after')

@section('konten')
    <style>
        :root {
            --primary-color: #191970;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --hover-color: #2a2a8a;
            --accent-color: #0059ff;
            --light-blue: #e3f2fd;
            --border-radius: 12px;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 56px;
            background-color: #f5f7fb;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link {
            color: var(--primary-color) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--hover-color) !important;
        }

        .navbar-nav .nav-link.active {
            color: white !important;
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
        }

        .quiz-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .quiz-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .timer-container {
            background-color: white;
            padding: 8px 15px;
            border-radius: 50px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .timer {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .timer-icon {
            margin-right: 8px;
            color: #ff5252;
        }

        .timer.warning {
            color: #ff5252;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        .question-section {
            padding: 30px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .question-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .question-text {
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            margin-bottom: 25px;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #333;
            padding: 5px;
        }

        .answer-list {
            margin-top: 25px;
        }

        .answer-list ul {
            list-style-type: none;
            padding: 0;
        }

        .answer-list ul li {
            margin: 12px 0;
            padding: 12px 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .answer-list ul li:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .answer-list ul li.selected {
            background-color: var(--light-blue);
            border-color: var(--accent-color);
            box-shadow: 0 2px 8px rgba(0, 59, 255, 0.15);
        }

        .btn-option {
            margin: 6px;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            border: 2px solid #ddd;
            background-color: white;
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        .btn-option:hover {
            border-color: var(--accent-color);
            transform: scale(1.05);
        }

        .btn-option.selected {
            background-color: var(--accent-color) !important;
            color: white !important;
            border-color: var(--accent-color) !important;
            box-shadow: 0 2px 8px rgba(0, 59, 255, 0.25);
        }

        .pagination-section {
            background-color: white;
            padding: 25px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            height: fit-content;
        }

        .pagination-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        .pagination {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
        }

        .pagination .page-item .page-link {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 500;
            color: var(--text-color);
            border: none;
            transition: all 0.2s ease;
        }

        .pagination .page-item .page-link:hover {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 6px rgba(25, 25, 112, 0.25);
        }

        .pagination .page-item.answered .page-link {
            background-color: var(--accent-color);
            color: white;
            box-shadow: 0 2px 6px rgba(0, 89, 255, 0.25);
        }

        .pagination .page-item.active.answered .page-link {
            background: linear-gradient(135deg, var(--primary-color) 50%, var(--accent-color) 50%);
        }

        .btn-nav {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-soal-midnight {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(25, 25, 112, 0.15);
        }

        .btn-soal-midnight:hover {
            background-color: var(--hover-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(25, 25, 112, 0.2);
        }

        .btn-soal-back {
            background-color: var(--accent-color);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 89, 255, 0.15);
        }

        .btn-soal-back:hover {
            background-color: #0046cc;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 89, 255, 0.2);
        }

        .btn-finish {
            margin-top: 25px;
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            background-color: #28a745;
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.15);
            transition: all 0.3s ease;
        }

        .btn-finish:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.2);
        }

        .image-container {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
        }

        .question-image {
            max-width: 100%;
            max-height: 350px;
            width: auto;
            height: auto;
            margin: 0 auto;
            border-radius: 8px;
            display: block;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .question-image:hover {
            transform: scale(1.02);
        }

        .answer-image {
            max-width: 160px;
            max-height: 130px;
            width: auto;
            height: auto;
            margin: 8px auto;
            border-radius: 6px;
            display: block;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            object-fit: contain;
            transition: transform 0.2s ease;
        }

        .answer-image:hover {
            transform: scale(1.05);
        }

        .answer-option {
            display: flex;
            align-items: flex-start;
        }

        .answer-option .option-letter {
            min-width: 30px;
            font-weight: bold;
            margin-top: 10px;
            color: var(--primary-color);
        }

        .answer-option .option-content {
            flex-grow: 1;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .progress-container {
            margin-top: 20px;
            text-align: center;
        }

        .progress-bar-container {
            height: 8px;
            background-color: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--accent-color);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .progress-text {
            font-size: 14px;
            margin-top: 8px;
            color: #666;
        }

        @media (max-width: 992px) {
            .quiz-container {
                padding: 20px;
                margin: 20px auto;
            }

            .question-section {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .quiz-container {
                padding: 15px;
                margin: 15px auto;
            }

            .question-section {
                padding: 15px;
                margin-bottom: 20px;
            }

            .question-image {
                max-height: 250px;
            }

            .btn-option {
                width: 46px;
                height: 46px;
                font-size: 1rem;
            }

            .pagination .page-item .page-link {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
            }

            .timer {
                font-size: 16px;
            }

            .btn-nav {
                padding: 10px 18px;
                font-size: 15px;
            }
        }

        @media (max-width: 576px) {
            .quiz-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .timer-container {
                margin-top: 10px;
                align-self: flex-end;
            }

            .question-image {
                max-height: 200px;
            }

            .answer-image {
                max-width: 120px;
                max-height: 100px;
            }

            .btn-option {
                width: 40px;
                height: 40px;
                font-size: 0.9rem;
                margin: 4px;
            }

            .answer-option .option-letter {
                min-width: 25px;
            }

            .btn-nav {
                padding: 8px 16px;
                font-size: 14px;
            }
        }

        .results-summary {
            margin: 20px 0;
            text-align: left;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 4px;
            background-color: #f8f9fa;
        }

        .result-label {
            font-weight: bold;
        }

        .result-value {
            font-weight: bold;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-primary {
            color: #007bff;
        }
        /* Add styles for audio and document content */
        .audio-container {
            text-align: center;
            margin: 15px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .question-audio {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 30px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .audio-player-title {
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .audio-player-title i {
            margin-right: 8px;
            color: var(--accent-color);
        }

        .document-container {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .document-preview-wrapper {
            position: relative;
            width: 100%;
            height: 600px;
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .document-preview {
            width: 100%;
            height: 100%;
            border: none;
            transition: all 0.3s ease;
        }

        .document-preview-controls {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .preview-control-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            color: #1e293b;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .preview-control-btn:hover {
            background: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .document-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .document-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(25, 25, 112, 0.15);
        }

        .document-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 25, 112, 0.25);
            color: white;
        }

        .document-button.secondary {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #475569;
        }

        .document-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .document-type-icon {
            font-size: 1.2em;
        }

        .document-type-icon.pdf { color: #dc2626; }
        .document-type-icon.word { color: #2563eb; }
        .document-type-icon.excel { color: #16a34a; }
        .document-type-icon.powerpoint { color: #ea580c; }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .document-preview {
                height: 300px;
            }
            
            .document-button {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .document-preview {
                height: 250px;
            }
        }

        .answer-audio {
            width: 100%;
            max-width: 200px;
            height: 40px;
            border-radius: 20px;
            background-color: #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="quiz-container">
        <div class="quiz-header">
            <h5 class="quiz-title">
                @if($id_quiz)
                    {{ $quiz->nama_quiz ?? 'Quiz' }}
                @else
                    {{ $materi->judul ?? 'Ujian' }}
                @endif
            </h5>
            <div class="timer-container">
                <i class="fas fa-clock timer-icon"></i>
                <div class="timer">
                    Waktu: <span id="timer">{{ $durasi }}:00</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Bagian Soal (col-lg-8) -->
            <div class="col-lg-8 mb-4">
                <div class="question-section">
                    <div class="question-header">
                        <div class="question-number">
                            Soal <span id="current-question">1</span> dari <span id="total-questions">{{ count($soal) }}</span>
                        </div>
                    </div>

                    <div class="question-text" id="question-text">
                        <!-- Soal akan dimuat di sini -->
                    </div>

                    <!-- Jawaban dalam format ul li -->
                    <div class="answer-list">
                        <ul id="answer-list">
                            <!-- Jawaban akan dimuat di sini -->
                        </ul>
                    </div>

                    <!-- Tombol A, B, C, D untuk menjawab -->
                    <div id="options" class="mt-4 d-flex justify-content-center">
                        <!-- Tombol jawaban (A, B, C, D) akan dimuat di sini -->
                    </div>

                    <div class="nav-buttons">
                        <button id="prev" class="btn-nav btn-soal-back">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <button id="next" class="btn-nav btn-soal-midnight">
                            Selanjutnya <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bagian Pagination (col-lg-4) -->
            <div class="col-lg-4">
                <div class="pagination-section">
                    <div class="pagination-title">Navigasi Soal</div>

                    <div class="progress-container">
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="progress-bar"></div>
                        </div>
                        <div class="progress-text" id="progress-text">0 dari 0 soal terjawab</div>
                    </div>

                    <nav class="mt-4">
                        <ul class="pagination" id="pagination">
                            <!-- Nomor soal akan dimuat di sini -->
                        </ul>
                    </nav>

                    <!-- Tombol Selesai -->
                    <button id="finish" class="btn-finish">
                        <i class="fas fa-check-circle"></i> Selesai Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        // Data soal dari backend
        let questions = @json($soal);
        let currentQuestion = 0;
        let answers = {};
        const id_materi = {{ $id_materi ?? 0 }};
        const id_quiz = {{ $id_quiz ?? 0 }};
        const id_siswa = {{ $id_siswa ?? 0 }};
        let csrf_token = "{{ csrf_token() }}";

        // Track answers from the current session only
        let currentSessionAnswers = {};

        // Load any existing temporary answers
        let tempAnswers = @json($temp_jawaban ?? []);

        // Initialize answers from temporary data
        // if (tempAnswers && Object.keys(tempAnswers).length > 0) {
        //     console.log("Loading saved answers:", tempAnswers);

        //     // Tentukan session key berdasarkan id_materi atau id_quiz
        //     let sessionKey = '';
        //     if (typeof id_materi !== 'undefined' && id_materi !== null) {
        //         sessionKey = `exam_session_materi_${id_materi}_${id_siswa}`;
        //     } else if (typeof id_quiz !== 'undefined' && id_quiz !== null) {
        //         sessionKey = `exam_session_quiz_${id_quiz}_${id_siswa}`;
        //     } else {
        //         console.warn("Tidak ada id_materi atau id_quiz untuk mengambil session.");
        //     }

        //     if (sessionKey) {
        //         const savedSessionAnswers = sessionStorage.getItem(sessionKey);
        //         if (savedSessionAnswers) {
        //             try {
        //                 currentSessionAnswers = JSON.parse(savedSessionAnswers);
        //                 console.log("Loaded current session answers:", currentSessionAnswers);

        //                 // Populate answers from current session storage
        //                 Object.keys(currentSessionAnswers).forEach(soalId => {
        //                     const questionIndex = questions.findIndex(q => q.id_soal == soalId);
        //                     if (questionIndex !== -1) {
        //                         answers[questionIndex] = currentSessionAnswers[soalId] - 1; // Convert to 0-based index
        //                         console.log(`Loaded session answer for question ${questionIndex+1}: ${answers[questionIndex] + 1}`);
        //                     }
        //                 });
        //             } catch (e) {
        //                 console.error("Error parsing session storage:", e);
        //                 sessionStorage.removeItem(sessionKey);
        //                 currentSessionAnswers = {};
        //             }
        //         } else {
        //             // First time in this session, load from temp_jawaban
        //             Object.keys(tempAnswers).forEach(soalId => {
        //                 const questionIndex = questions.findIndex(q => q.id_soal == soalId);
        //                 if (questionIndex !== -1) {
        //                     answers[questionIndex] = tempAnswers[soalId] - 1; // Convert to 0-based index
        //                     currentSessionAnswers[soalId] = tempAnswers[soalId];
        //                     console.log(`Loaded temp answer for question ${questionIndex+1}: ${answers[questionIndex] + 1}`);
        //                 }
        //             });
        //             sessionStorage.setItem(sessionKey, JSON.stringify(currentSessionAnswers));
        //         }
        //     }
        // }

        // Tentukan session key berdasarkan id_materi atau id_quiz
        let sessionKey = '';
        if (typeof id_materi !== 'undefined' && id_materi !== null) {
            sessionKey = `exam_session_materi_${id_materi}_${id_siswa}`;
        } else if (typeof id_quiz !== 'undefined' && id_quiz !== null) {
            sessionKey = `exam_session_quiz_${id_quiz}_${id_siswa}`;
        } else {
            console.warn("Tidak ada id_materi atau id_quiz untuk mengambil session.");
        }

        if (sessionKey) {
            const savedSessionAnswers = sessionStorage.getItem(sessionKey);
            if (savedSessionAnswers) {
                try {
                    currentSessionAnswers = JSON.parse(savedSessionAnswers);
                    console.log("Loaded current session answers:", currentSessionAnswers);

                    // Populate answers from current session storage
                    Object.keys(currentSessionAnswers).forEach(soalId => {
                        const questionIndex = questions.findIndex(q => q.id_soal == soalId);
                        if (questionIndex !== -1) {
                            answers[questionIndex] = currentSessionAnswers[soalId] - 1; // Convert to 0-based index
                            console.log(`Loaded session answer for question ${questionIndex + 1}: ${answers[questionIndex] + 1}`);
                        }
                    });
                } catch (e) {
                    console.error("Error parsing session storage:", e);
                    sessionStorage.removeItem(sessionKey);
                    currentSessionAnswers = {};
                }
            } else {
                console.log("Tidak ada data di sessionStorage untuk key:", sessionKey);
            }
        }



        console.log("Data soal:", questions);
        console.log("Jawaban sementara:", tempAnswers);


        // Fungsi untuk mendeteksi tipe konten (gambar, audio, dokumen, url, atau teks)
        function detectContentType(content) {
            if (!content) return {
                type: 'text',
                content: ''
            };

            // Deteksi gambar
            if (content.match(/\.(jpg|jpeg|png|gif)$/i)) {
                return {
                    type: 'image',
                    content: content
                };
            }

            // Deteksi audio
            if (content.match(/\.(mp3|wav|ogg)$/i)) {
                return {
                    type: 'audio',
                    content: content
                };
            }

            // Deteksi dokumen
            if (content.match(/\.(pdf|doc|docx|xls|xlsx|ppt|pptx)$/i)) {
                return {
                    type: 'document',
                    content: content
                };
            }

            // Deteksi URL
            if (content.startsWith('http://') || content.startsWith('https://')) {
                return {
                    type: 'url',
                    content: content
                };
            }

            // Default sebagai teks
            return {
                type: 'text',
                content: content
            };
        }

        // Fungsi untuk menyimpan jawaban sementara ke database
        function saveTemporaryAnswer(questionIndex) {
            if (answers[questionIndex] === undefined) return;

            const soal = questions[questionIndex];
            const pilihan = answers[questionIndex] + 1; // Convert to 1-based index

            console.log("Saving answer:", {
                _token: csrf_token,
                id_materi: id_materi,
                id_quiz: id_quiz,
                id_soal: soal.id_soal,
                id_siswa: id_siswa,
                pilihan: pilihan
            });

            $.ajax({
                url: "{{ route('simpan.jawaban.sementara') }}",
                type: "POST",
                data: {
                    _token: csrf_token,
                    id_materi: id_materi,
                    id_quiz: id_quiz,
                    id_soal: soal.id_soal,
                    id_siswa: id_siswa,
                    pilihan: pilihan
                },
                success: function(response) {
                    console.log("Jawaban berhasil disimpan:", response);
                    
                    // Update tempAnswers dengan jawaban terbaru
                    tempAnswers[soal.id_soal] = pilihan;

                    // Update session storage for current session answers
                    const soalId = soal.id_soal.toString();
                    currentSessionAnswers[soalId] = pilihan;

                    // Tentukan key berdasarkan id_materi atau id_quiz
                    let sessionKey = '';
                    if (typeof id_materi !== 'undefined' && id_materi !== null) {
                        sessionKey = `exam_session_materi_${id_materi}_${id_siswa}`;
                    } else if (typeof id_quiz !== 'undefined' && id_quiz !== null) {
                        sessionKey = `exam_session_quiz_${id_quiz}_${id_siswa}`;
                    } else {
                        console.warn("Tidak ada id_materi atau id_quiz untuk menyimpan session.");
                        return;
                    }

                    sessionStorage.setItem(sessionKey, JSON.stringify(currentSessionAnswers));

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Jawaban disimpan'
                    });
                },

                error: function(xhr, status, error) {
                    console.error("Error menyimpan jawaban:", error);
                    console.error("Response:", xhr.responseText);
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'error',
                        title: 'Gagal menyimpan jawaban'
                    });
                }
            });
        }

        // Fungsi untuk memperbarui progress bar
        function updateProgressBar() {
            const answeredCount = Object.keys(answers).length;
            const totalQuestions = questions.length;
            const progressPercentage = (answeredCount / totalQuestions) * 100;

            $('#progress-bar').css('width', `${progressPercentage}%`);
            $('#progress-text').text(`${answeredCount} dari ${totalQuestions} soal terjawab`);
        }

        // Simpan jawaban saat pengguna meninggalkan halaman
        window.addEventListener('beforeunload', function() {
            if (answers[currentQuestion] !== undefined) {
                saveTemporaryAnswer(currentQuestion);
            }
        });

        // Fungsi untuk memuat soal
        function loadQuestion() {
            // Filter questions by id_materi to ensure we're only showing questions for this exam
            const filteredQuestions = questions.filter(q => q.id_materi == id_materi);
            if (filteredQuestions.length > 0) {
                questions = filteredQuestions;
                displayQuestion(questions[currentQuestion]);
            } else {
                // If we don't have any questions after filtering, display all questions
                console.warn('No questions found for this materi. Displaying all available questions.');
                displayQuestion(questions[currentQuestion]);
            }
        }

        function displayQuestion(question) {
            const soal = question;
            $('#current-question').text(currentQuestion + 1);
            $('#total-questions').text(questions.length);

            const soalContent = detectContentType(soal.soal);

            switch(soalContent.type) {
            case 'image':
                $('#question-text').html(`
                    <div class="image-container">
                        <img src="{{ asset('') }}${soalContent.content}" class="question-image" alt="Soal">
                    </div>
                `);
                break;

            case 'audio':
                $('#question-text').html(`
                    <div class="audio-container">
                        <div class="audio-player-title">
                            <i class="fas fa-music"></i> Audio Soal
                        </div>
                        <audio controls class="question-audio">
                            <source src="{{ asset('') }}${soalContent.content}" type="audio/${soalContent.content.split('.').pop()}">
                            Browser Anda tidak mendukung pemutaran audio.
                        </audio>
                    </div>
                `);
                break;
            case 'document':
                const fileExtension = soalContent.content.split('.').pop().toLowerCase();
                const isPdf = fileExtension === 'pdf';
                const fileName = soalContent.content.split('/').pop();
                
                $('#question-text').html(`
                    <div class="document-container">
                        <div class="document-preview-wrapper">
                            ${isPdf ? `
                                <iframe src="{{ asset('') }}${soalContent.content}" class="document-preview"></iframe>
                                <div class="document-preview-controls">
                                    <button class="preview-control-btn" onclick="toggleFullScreen(this.parentElement.previousElementSibling)">
                                        <i class="fas fa-expand"></i> Layar Penuh
                                    </button>
                                    <a href="{{ asset('') }}${soalContent.content}" class="preview-control-btn" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                    </a>
                                </div>
                            ` : `
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center">
                                        <i class="fas fa-file-${getDocumentIcon(fileExtension)} fa-4x mb-3 document-type-icon ${fileExtension}"></i>
                                        <h4 class="mb-3">${fileName}</h4>
                                        <p class="text-muted">Dokumen ini tidak dapat ditampilkan secara langsung</p>
                                    </div>
                                </div>
                            `}
                        </div>

                        <div class="document-info">
                            <i class="fas fa-info-circle"></i>
                            <span>${fileName}</span>
                        </div>
                    </div>
                `);

                // Add fullscreen functionality
                if (!window.toggleFullScreen) {
                    window.toggleFullScreen = function(element) {
                        if (!document.fullscreenElement) {
                            element.requestFullscreen().catch(err => {
                                console.error(`Error attempting to enable full-screen mode: ${err.message}`);
                            });
                        } else {
                            document.exitFullscreen();
                        }
                    }
                }
                break;

            case 'url':
                $('#question-text').html(
                    `<a href="${soalContent.content}" target="_blank" class="btn btn-info">Lihat Soal</a>`
                );
                break;

            default:
                $('#question-text').text(soalContent.content);
            }

            const options = [
                soal.pilihan_1,
                soal.pilihan_2,
                soal.pilihan_3,
                soal.pilihan_4
            ];

            let answerListHtml = "";
            let optionsHtml = "";

            // Check if current question has been answered
            const currentQuestionAnswered = answers[currentQuestion] !== undefined;

            options.forEach((opt, index) => {
                const optContent = detectContentType(opt);
                const optionLetter = String.fromCharCode(65 + index);

                // Only mark as selected if the current question has been answered
                const isSelected = currentQuestionAnswered && answers[currentQuestion] === index;
                const selectedClass = isSelected ? "selected" : "";

                if (optContent.type === 'image') {
                    answerListHtml += `
                <li class="answer-option ${selectedClass}" data-index="${index}">
                    <div class="option-letter">${optionLetter}.</div>
                    <div class="option-content">
                        <div class="image-container">
                            <img src="{{ asset('') }}${optContent.content}" class="answer-image" alt="Pilihan ${index + 1}">
                        </div>
                    </div>
                </li>`;
                } else if (optContent.type === 'audio') {
                    answerListHtml += `
                <li class="answer-option ${selectedClass}" data-index="${index}">
                    <div class="option-letter">${optionLetter}.</div>
                    <div class="option-content">
                        <audio controls class="answer-audio">
                            <source src="{{ asset('') }}${optContent.content}" type="audio/${optContent.content.split('.').pop()}">
                            Browser Anda tidak mendukung pemutaran audio.
                        </audio>
                    </div>
                </li>`;
                } else if (optContent.type === 'document') {
                    const fileExtension = optContent.content.split('.').pop().toLowerCase();
                    
                    answerListHtml += `
                <li class="answer-option ${selectedClass}" data-index="${index}">
                    <div class="option-letter">${optionLetter}.</div>
                    <div class="option-content">
                        <a href="{{ asset('') }}${optContent.content}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-${getDocumentIcon(fileExtension)}"></i> Lihat Dokumen
                        </a>
                    </div>
                </li>`;
                } else if (optContent.type === 'url') {
                    answerListHtml += `
                <li class="answer-option ${selectedClass}" data-index="${index}">
                    <div class="option-letter">${optionLetter}.</div>
                    <div class="option-content">
                        <a href="${optContent.content}" target="_blank" class="btn btn-sm btn-outline-info">Lihat Pilihan ${index + 1}</a>
                    </div>
                </li>`;
                } else {
                    answerListHtml += `
                <li class="answer-option ${selectedClass}" data-index="${index}">
                    <div class="option-letter">${optionLetter}.</div>
                    <div class="option-content">${optContent.content}</div>
                </li>`;
                }

                optionsHtml += `<button class='btn btn-option ${selectedClass}' data-index='${index}'>
                    ${optionLetter}
                </button>`;
            });

            $('#answer-list').html(answerListHtml);
            $('#options').html(optionsHtml);
            updatePagination();
            updateButtons();
            updateProgressBar();
        }

        // Helper function to get the appropriate Font Awesome icon for document types
        function getDocumentIcon(extension) {
            switch(extension) {
                case 'pdf':
                    return 'pdf';
                case 'doc':
                case 'docx':
                    return 'word';
                case 'xls':
                case 'xlsx':
                    return 'excel';
                case 'ppt':
                case 'pptx':
                    return 'powerpoint';
                default:
                    return 'alt';
            }
        }

        // Fungsi untuk memperbarui pagination
        function updatePagination() {
            let paginationHtml = "";
            questions.forEach((_, index) => {
                let activeClass = currentQuestion === index ? "active" : "";
                let answeredClass = answers[index] !== undefined ? "answered" : "";
                paginationHtml += `<li class='page-item ${activeClass} ${answeredClass}'>
                    <a class='page-link' href='#' data-index='${index}'>${index + 1}</a>
                </li>`;
            });
            $('#pagination').html(paginationHtml);
        }

        // Fungsi untuk memperbarui tombol navigasi
        function updateButtons() {
            $('#prev').toggle(currentQuestion > 0);
            $('#next').toggle(currentQuestion < questions.length - 1);
            $('#finish').toggle(currentQuestion === questions.length - 1 || Object.keys(answers).length === questions.length);
        }

        // Event listener untuk tombol jawaban
        $(document).on('click', '.btn-option', function() {
            let selectedIndex = parseInt($(this).data('index'));

            // Cek apakah jawaban sudah dipilih sebelumnya
            if (answers[currentQuestion] === selectedIndex) {
                return;
            }

            answers[currentQuestion] = selectedIndex;

            $('.btn-option').removeClass('selected');
            $(this).addClass('selected');

            $('.answer-list li').removeClass('selected');
            $(`.answer-list li[data-index="${selectedIndex}"]`).addClass('selected');

            // Simpan jawaban sementara ke database
            saveTemporaryAnswer(currentQuestion);

            // Update session storage
            const soalId = questions[currentQuestion].id_soal.toString();
            currentSessionAnswers[soalId] = selectedIndex + 1; // Convert to 1-based index
            sessionStorage.setItem(`exam_session_${id_materi}_${id_siswa}`, JSON.stringify(currentSessionAnswers));

            updatePagination();
            updateButtons();
            updateProgressBar();
        });

        // Event listener untuk pilihan jawaban dalam daftar
        $(document).on('click', '.answer-list li', function() {
            const index = $(this).data('index');
            answers[currentQuestion] = index;

            $('.btn-option').removeClass('selected');
            $(`.btn-option[data-index="${index}"]`).addClass('selected');

            $('.answer-list li').removeClass('selected');
            $(this).addClass('selected');

            // Simpan jawaban sementara ke database
            saveTemporaryAnswer(currentQuestion);

            // Update session storage
            const soalId = questions[currentQuestion].id_soal.toString();
            currentSessionAnswers[soalId] = index + 1; // Convert to 1-based index
            sessionStorage.setItem(`exam_session_${id_materi}_${id_siswa}`, JSON.stringify(currentSessionAnswers));

            updatePagination();
            updateButtons();
            updateProgressBar();
        });

        // Event listener untuk tombol sebelumnya
        $('#prev').click(() => {
            if (currentQuestion > 0) {
                currentQuestion--;
                loadQuestion();
            }
        });

        $('#next').click(() => {
            if (currentQuestion < questions.length - 1) {
                currentQuestion++;
                loadQuestion();
            }
        });


        // Event listener untuk pagination
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            currentQuestion = parseInt($(this).data('index'));
            loadQuestion();
        });


        // Event listener untuk tombol selesai
        $('#finish').click(() => {
            // Confirm before finishing the exam
            Swal.fire({
                title: "Selesaikan Ujian?",
                text: `Anda telah menjawab ${Object.keys(answers).length} dari ${questions.length} soal.`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: "Ya, Selesaikan",
                cancelButtonText: "Tidak, Kembali ke Ujian"
            }).then((result) => {
                if (!result.isConfirmed) {
                    return; // Stop execution if user cancels
                }

                if (answers[currentQuestion] !== undefined) {
                    saveTemporaryAnswer(currentQuestion);
                }

                let correctCount = 0;
                let totalAnswered = Object.keys(answers).length;

                Object.keys(answers).forEach(index => {
                    let userAnswer = answers[index];
                    let correctAnswer = questions[index].jawaban -
                    1; // Convert to array index (0-3)

                    if (userAnswer === correctAnswer) {
                        correctCount++;
                    }
                });

                // Show loading
                Swal.fire({
                    title: 'Menyimpan jawaban...',
                    html: 'Mohon tunggu sebentar',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });

                // Send request to finish exam
                $.ajax({
                    url: "{{ route('selesai.ujian') }}",
                    type: "POST",
                    data: {
                        _token: csrf_token,
                        id_materi: id_materi ?? 0,
                        id_quiz : id_quiz ?? 0,
                        id_siswa: id_siswa
                    },
                    success: function(response) {
                        // Reset answers in the view
                        answers = {};
                        tempAnswers = {};
                        currentSessionAnswers = {};

                        // Clear session storage berdasarkan id_materi atau id_quiz
                        if (typeof id_materi !== 'undefined' && id_materi !== null) {
                            sessionStorage.removeItem(`exam_session_materi_${id_materi}_${id_siswa}`);
                        } else if (typeof id_quiz !== 'undefined' && id_quiz !== null) {
                            sessionStorage.removeItem(`exam_session_quiz_${id_quiz}_${id_siswa}`);
                        } else {
                            console.warn("Gagal menghapus session: id_materi atau id_quiz tidak ditemukan.");
                        }


                        // Show results popup
                        Swal.fire({
                            title: "Ujian Selesai!",
                            html: `
                        <div class="results-summary">
                            <div class="result-item">
                                <span class="result-label">Jawaban Benar:</span>
                                <span class="result-value text-success">${response.data.correct_answers}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Jawaban Salah:</span>
                                <span class="result-value text-danger">${response.data.answered_questions - response.data.correct_answers}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Nilai Akhir:</span>
                                <span class="result-value text-primary">${response.data.score}</span>
                            </div>
                        </div>
                    `,
                            icon: "success",
                            confirmButtonText: "Lihat Detail Hasil",
                            allowOutsideClick: false,
                            showCancelButton: true,
                            cancelButtonText: "Kembali ke Beranda"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to detailed results page
                                window.location.href =
                                    "{{ route('hasil.ujian') }}?id_materi=" +
                                    id_materi + "&id_siswa=" + id_siswa;
                            } else {
                                // Redirect to home page
                                window.location.href = "{{ route('siswaIn') }}";
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error details:", xhr.responseText);
                        let errorMessage =
                            "Terjadi kesalahan saat menyelesaikan ujian. Silakan coba lagi.";

                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                errorMessage = response.error;
                            } else if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error("Error parsing response:", e);
                        }

                        Swal.fire({
                            title: "Error!",
                            text: errorMessage,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            });
        });

        // Mulai ujian saat halaman dimuat
        $(document).ready(() => {
            loadQuestion();
        });
    </script>

    <script>
        const durasiMenit = {{ $durasi }}; // Ambil dari backend
        const totalWaktu = durasiMenit * 60 * 1000; // Konversi ke milidetik
        const timerDisplay = document.getElementById('timer');
        
        // Buat key unik untuk localStorage berdasarkan id_materi dan id_siswa
        const timerKey = `waktuMulai_${{{ $id_materi ?? $id_quiz }}}_${{{ $id_siswa }}}`;

        // Cek apakah ada waktu mulai tersimpan di localStorage
        let waktuMulai = localStorage.getItem(timerKey);
        let waktuSelesai = null;

        if (!waktuMulai) {
            waktuMulai = Date.now(); // Set waktu mulai sekarang jika belum ada
            waktuSelesai = waktuMulai + totalWaktu;
            localStorage.setItem(timerKey, waktuMulai);
            localStorage.setItem(`${timerKey}_selesai`, waktuSelesai);
        } else {
            waktuMulai = parseInt(waktuMulai); // Convert dari string ke angka
            waktuSelesai = parseInt(localStorage.getItem(`${timerKey}_selesai`));
            
            // Jika waktu selesai sudah lewat, reset timer
            if (Date.now() > waktuSelesai) {
                waktuMulai = Date.now();
                waktuSelesai = waktuMulai + totalWaktu;
                localStorage.setItem(timerKey, waktuMulai);
                localStorage.setItem(`${timerKey}_selesai`, waktuSelesai);
            }
        }

        function updateTimer() {
            const waktuSekarang = Date.now();
            const sisaWaktu = waktuSelesai - waktuSekarang;

            if (sisaWaktu <= 0) {
                clearInterval(timerInterval);
                timerDisplay.textContent = "00:00";
                Swal.fire({
                    title: 'Waktu Habis!',
                    text: 'Ujian akan diselesaikan secara otomatis.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Hapus penyimpanan waktu
                    localStorage.removeItem(timerKey);
                    localStorage.removeItem(`${timerKey}_selesai`);
                    document.getElementById('finish').click();
                });
                return;
            }

            const minutes = Math.floor(sisaWaktu / 60000);
            const seconds = Math.floor((sisaWaktu % 60000) / 1000);
            timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            // Beri peringatan jika kurang dari 5 menit
            if (sisaWaktu <= 300000) {
                timerDisplay.classList.add('warning');
            }
        }

        // Update timer segera dan set interval
        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);

        // Hapus timer saat selesai ujian
        document.getElementById('finish').addEventListener('click', function() {
            clearInterval(timerInterval);
            localStorage.removeItem(timerKey);
            localStorage.removeItem(`${timerKey}_selesai`);
        });
    </script>
    <script>
        // Get duration from backend based on quiz or materi
        const durasiMenit = {{ $durasi ?? 30 }}; // This will be set correctly from the controller
        let timeLeft = durasiMenit * 60; // Convert to seconds
        let timerInterval;

        // Create unique key for localStorage based on id_materi, id_quiz, and id_siswa
        const timerKey = `timer_${id_materi}_${id_quiz}_${id_siswa}`;
        
        // Check if there's a saved timer value in localStorage
        const savedTime = localStorage.getItem(timerKey);
        if (savedTime) {
            timeLeft = parseInt(savedTime);
        }

        // Initialize the timer
        function startTimer() {
            updateTimerDisplay();
            timerInterval = setInterval(function() {
                timeLeft--;
                
                // Save current time to localStorage
                localStorage.setItem(timerKey, timeLeft);
                
                updateTimerDisplay();
                
                // Warning when 5 minutes left
                if (timeLeft === 300) { // 5 minutes in seconds
                    $('.timer').addClass('warning');
                    Swal.fire({
                        title: 'Waktu Hampir Habis!',
                        text: 'Tersisa 5 menit lagi untuk menyelesaikan ujian.',
                        icon: 'warning',
                        confirmButtonText: 'Lanjutkan'
                    });
                }
                
                // Warning when 1 minute left
                if (timeLeft === 60) { // 1 minute in seconds
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Tersisa 1 menit lagi untuk menyelesaikan ujian.',
                        icon: 'warning',
                        confirmButtonText: 'Lanjutkan'
                    });
                }
                
                // Time's up
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    localStorage.removeItem(timerKey);
                    Swal.fire({
                        title: 'Waktu Habis!',
                        text: 'Waktu ujian telah habis. Jawaban Anda akan dikirimkan secara otomatis.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        finishExam();
                    });
                }
            }, 1000);
        }
        
        // Update timer display
        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            $('#timer').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
        }

        // When the exam is finished, clear the timer
        $('#finish').on('click', function() {
            clearInterval(timerInterval);
            localStorage.removeItem(timerKey);
        });

        // Start the timer when the page loads
        $(document).ready(function() {
            startTimer();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find the Beranda nav link and add the active class
            const berandaLink = document.querySelector('.navbar-nav .nav-item:first-child .nav-link');
            if (berandaLink) {
                berandaLink.classList.add('active');
            }
        });
    </script>
@endsection