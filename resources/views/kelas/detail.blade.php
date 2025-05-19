@extends('depan.layout_after')

@section('konten')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .simple-link-wrapper {
        margin: 15px 0;
    }

    .simple-btn {
        display: inline-flex;
        align-items: center;
        background-color: #f8f9fa;
        color: #2d3748;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: all 0.25s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .simple-btn:hover {
        background-color: #ffffff;
        border-color: #cbd5e0;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Document button styles */
    .document-wrapper {
        margin: 15px 0;
    }

    .document-btn {
        background-color: #f8f9fa;
        border-color: #e53e3e;
        color: #e53e3e;
    }

    .document-btn:hover {
        background-color: #fff5f5;
        border-color: #c53030;
        color: #c53030;
    }

    /* Document preview styles */
    .document-preview-container {
        margin: 15px 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .document-preview-frame {
        width: 100%;
        height: 400px;
        border: none;
    }

    .document-actions {
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }

    .document-icon {
        font-size: 48px;
        margin-bottom: 15px;
        color: #e53e3e;
    }

    .document-info {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
    }

    .document-filename {
        font-weight: 500;
        margin-bottom: 5px;
        color: #4a5568;
        word-break: break-all;
    }

    .document-type {
        font-size: 14px;
        color: #718096;
    }

    /* Audio player styles */
    .audio-wrapper {
        margin: 15px 0;
    }

    .audio-player-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .audio-player-container:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .audio-player-title {
        font-weight: 500;
        color: #4a5568;
    }

    .custom-audio-player {
        width: 100%;
        height: 40px;
        border-radius: 20px;
    }

    .custom-audio-player:focus {
        outline: none;
    }

    .link-text {
        margin-right: 8px;
    }

    .link-icon {
        font-size: 18px;
        transition: transform 0.2s ease;
    }

    .simple-btn:hover .link-icon {
        transform: translateX(4px);
    }

    /* New styles for tabs */
    .nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #718096;
        font-weight: 500;
        padding: 10px 20px;
        margin-right: 10px;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #4a5568;
        background-color: #f7fafc;
    }

    .nav-tabs .nav-link.active {
        color: #191970;
        border-bottom: 3px solid #191970;
        font-weight: 600;
    }

    .section-title {
        color: #191970;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-materi,
    .card-quiz {
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .card-materi:hover,
    .card-quiz:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .card-quiz {
        border-left: 4px solid #191970;
    }

    .card-materi {
        border-left: 4px solid #0059ff;
    }

    .btn-midnight {
        background-color: #191970;
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        text-decoration: none;
    }

    .btn-midnight:hover {
        background-color: #2a2a8a;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(25, 25, 112, 0.2);
        color: white;
        text-decoration: none;
    }

    #chatbot {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 350px;
        max-height: 600px;
        display: none;
        z-index: 1055;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid rgba(0, 0, 0, 0.08);
        background-color: #fff;
    }
    
    .chatbot-header {
        background: linear-gradient(135deg, #191970, #0059ff);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .chatbot-header button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.2s ease;
        padding: 0;
        font-size: 14px;
    }
    
    .chatbot-header button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }
    
    .chatbot-body {
        background-color: #f8f9fa;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23191970' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        height: 350px;
        overflow-y: auto;
        padding: 16px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f8f9fa;
    }
    
    .chatbot-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .chatbot-body::-webkit-scrollbar-track {
        background: #f8f9fa;
    }
    
    .chatbot-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 6px;
    }
    
    .chatbot-footer {
        background-color: #fff;
        padding: 12px 16px;
        border-top: 1px solid #e2e8f0;
    }
    
    .chat-msg {
        margin-bottom: 16px;
        max-width: 80%;
        clear: both;
        position: relative;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .chat-msg.bot {
        background-color: #ffffff;
        color: #4a5568;
        padding: 12px 16px;
        border-radius: 16px 16px 16px 4px;
        display: inline-block;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border-left: 3px solid #191970;
        float: left;
        width: 85%;
    }
    
    .chat-msg.user {
        background: linear-gradient(135deg, #191970, #0059ff);
        color: white;
        padding: 12px 16px;
        border-radius: 16px 16px 4px 16px;
        float: right;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .chat-msg.bot audio {
        margin-top: 10px;
        width: 100%;
        height: 40px;
        border-radius: 8px;
        background-color: #f0f4ff;
        outline: none;
    }
    
    .chat-time {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 5px;
        text-align: right;
    }
    
    .chat-msg.bot .chat-time {
        color: #a0aec0;
    }
    
    #chat-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1060;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #191970, #0059ff);
        color: white;
        box-shadow: 0 4px 15px rgba(25, 25, 112, 0.3);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    #chat-toggle:hover {
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 6px 20px rgba(25, 25, 112, 0.4);
    }
    
    #chat-toggle i {
        font-size: 24px;
    }
    
    .chat-input-container {
        display: flex;
        align-items: center;
    }
    
    .chat-input {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 10px 16px;
        font-size: 14px;
        transition: all 0.2s ease;
        background-color: #f8f9fa;
    }
    
    .chat-input:focus {
        outline: none;
        border-color: #191970;
        box-shadow: 0 0 0 3px rgba(25, 25, 112, 0.1);
        background-color: #fff;
    }
    
    .chat-send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #191970, #0059ff);
        color: white;
        border: none;
        margin-left: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .chat-send-btn:hover {
        transform: scale(1.1);
        background: linear-gradient(135deg, #0059ff, #191970);
    }
    
    .chat-send-btn i {
        font-size: 18px;
    }
    
    /* Audio wrapper styling */
    .audio-wrapper {
        margin-top: 10px;
        width: 100%;
    }
    
    .audio-wrapper.rendered audio {
        display: block;
        width: 100%;
        height: 40px;
        border-radius: 8px;
        background-color: #f0f4ff;
        margin-top: 8px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        #chatbot {
            width: 300px;
            bottom: 70px;
            right: 10px;
        }
        
        .chat-msg {
            max-width: 90%;
        }
    }
</style>
<div class="container card-container mt-5">
    <div class="card">
        <div class="card-body">
            <!-- Navigation tabs -->
            <ul class="nav nav-tabs" id="contentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="materi-tab" data-toggle="tab" href="#materi" role="tab">
                        <i class="fas fa-book-open mr-2"></i>Materi Pelajaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="quiz-tab" data-toggle="tab" href="#quiz" role="tab">
                        <i class="fas fa-question-circle mr-2"></i>Quiz
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Materi Tab -->
                <div class="tab-pane fade show active" id="materi" role="tabpanel">
                    <h5 class="section-title">Materi Pelajaran</h5>
                    <div class="row">
                        @php $materiCount = 0; @endphp
                        @foreach ($data as $item)
                        @if (!$item->quiz)
                        @php $materiCount++; @endphp
                        <div class="col-md-12 col-12">
                            <div class="card card-materi">
                                <div class="card-header">
                                    <h5>{{ $item->materi->judul }}</h5>
                                    <p class="card-header-description">
                                        {{ $item->materi->deskripsi }}
                                    </p>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <!-- Jika ini adalah materi -->
                                            @if ($item->materi->kategori->nama_kategori == 'foto')
                                            <div>
                                                <img src="{{ asset('uploads/materi/' . $item->materi->file_materi) }}"
                                                    alt="{{ $item->materi->judul }} content"
                                                    class="img-fluid">
                                            </div>
                                            @elseif ($item->materi->kategori->nama_kategori == 'video')
                                            <div>
                                                @php
                                                // Convert YouTube watch URL to embed URL if needed
                                                $videoUrl = $item->materi->file_materi;
                                                if (
                                                strpos($videoUrl, 'youtube.com/watch?v=') !==
                                                false
                                                ) {
                                                $videoId = substr(
                                                $videoUrl,
                                                strpos($videoUrl, 'v=') + 2,
                                                );
                                                if (strpos($videoId, '&') !== false) {
                                                $videoId = substr(
                                                $videoId,
                                                0,
                                                strpos($videoId, '&'),
                                                );
                                                }
                                                $embedUrl =
                                                'https://www.youtube.com/embed/' . $videoId;
                                                } else {
                                                $embedUrl = $videoUrl;
                                                }
                                                @endphp
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item"
                                                        src="{{ $embedUrl }}" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                            @elseif ($item->materi->kategori->nama_kategori == 'link')
                                            <div class="simple-link-wrapper">
                                                <a href="{{ $item->materi->file_materi }}"
                                                    class="simple-btn" target="_blank">
                                                    <span class="link-text">Buka Link</span>
                                                    <span class="link-icon">→</span>
                                                </a>
                                            </div>
                                            @elseif ($item->materi->kategori->nama_kategori == 'document')
                                            <div class="document-wrapper">
                                                @php
                                                $fileExtension = pathinfo($item->materi->file_materi, PATHINFO_EXTENSION);
                                                $fileName = basename($item->materi->file_materi);
                                                $filePath = asset('uploads/dokumen/' . $item->materi->file_materi);
                                                $isPdf = strtolower($fileExtension) === 'pdf';

                                                // Get document icon based on extension
                                                $iconClass = 'fa-file-alt';
                                                if ($isPdf) {
                                                $iconClass = 'fa-file-pdf';
                                                } elseif (in_array(strtolower($fileExtension), ['doc', 'docx'])) {
                                                $iconClass = 'fa-file-word';
                                                } elseif (in_array(strtolower($fileExtension), ['xls', 'xlsx'])) {
                                                $iconClass = 'fa-file-excel';
                                                } elseif (in_array(strtolower($fileExtension), ['ppt', 'pptx'])) {
                                                $iconClass = 'fa-file-powerpoint';
                                                }
                                                @endphp

                                                <div class="document-info">
                                                    <div class="document-filename">
                                                        <i class="fas {{ $iconClass }} mr-2"></i>
                                                        {{ $fileName }}
                                                    </div>
                                                    <div class="document-type">
                                                        Tipe: {{ strtoupper($fileExtension) }}
                                                    </div>
                                                </div>

                                                @if($isPdf)
                                                <div class="document-preview-container">
                                                    <iframe src="{{ $filePath }}" class="document-preview-frame"></iframe>
                                                </div>
                                                @else
                                                <div class="text-center">
                                                    <div class="document-icon">
                                                        <i class="fas {{ $iconClass }}"></i>
                                                    </div>
                                                    <p class="text-muted">Preview tidak tersedia untuk dokumen ini</p>
                                                </div>
                                                @endif

                                                <div class="document-actions">
                                                    <a href="{{ $filePath }}" class="btn btn-primary" target="_blank">
                                                        <i class="fas fa-external-link-alt mr-2"></i>
                                                        Buka Dokumen
                                                    </a>
                                                    <a href="{{ $filePath }}" class="btn btn-outline-secondary ml-2" download>
                                                        <i class="fas fa-download mr-2"></i>
                                                        Unduh
                                                    </a>
                                                </div>
                                            </div>
                                            @elseif ($item->materi->kategori->nama_kategori == 'audio')
                                            <div class="audio-wrapper">
                                                <div class="audio-player-container">
                                                    <div class="audio-player-title mb-2">
                                                        <i class="fas fa-music mr-2"></i>{{ basename($item->materi->file_materi) }}
                                                    </div>
                                                    <audio controls class="custom-audio-player w-100">
                                                        <source src="{{ asset('uploads/audio/' . $item->materi->file_materi) }}"
                                                            type="audio/{{ pathinfo($item->materi->file_materi, PATHINFO_EXTENSION) }}">
                                                        Browser Anda tidak mendukung pemutaran audio.
                                                    </audio>
                                                </div>
                                                <p class="mt-2 text-muted">
                                                    <small><i class="fas fa-info-circle mr-1"></i> Gunakan kontrol di atas untuk memutar audio</small>
                                                </p>
                                            </div>
                                            @else
                                            <p class="card-text">
                                                {{ $item->materi->file_materi }}
                                            </p>
                                            @endif
                                        </div>
                                        <!-- Replace the existing "Lihat Soal" button with this code -->
                                        <div class="col-md-5 text-md-right">
                                            @php
                                            $email = Auth::user()->email;
                                            $student = DB::table('master_siswa')->where('email', $email)->first();
                                            $id_siswa = $student ? $student->id_siswa : null;

                                            // Check if student has already taken this exam
                                            $sudahMengerjakan = DB::table('trans_nilai')
                                            ->where('id_siswa', $id_siswa)
                                            ->where('id_materi', $item->id_materi)
                                            ->exists();
                                            @endphp

                                            @if ($sudahMengerjakan)
                                            <button type="button" class="btn-midnight disabled" style="opacity: 0.7; cursor: not-allowed;"
                                                data-toggle="tooltip" data-placement="top" title="Anda sudah mengerjakan ujian ini">
                                                <i class="fas fa-check-circle mr-2"></i>Sudah Dikerjakan
                                            </button>
                                            @else
                                            <a href="{{ route('mulai.ujian', ['id' => base64_encode($item->id_materi)]) }}"
                                                class="btn-midnight">
                                                <i class="fas fa-pencil-alt mr-2"></i>Lihat Soal
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        @if ($materiCount == 0)
                        <div class="col-12">
                            <div class="alert alert-info">
                                Belum ada materi pelajaran untuk kelas ini.
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quiz Tab -->
                <div class="tab-pane fade" id="quiz" role="tabpanel">
                    <h5 class="section-title">Quiz dan Ujian</h5>
                    <div class="row">
                        @php $quizCount = 0; @endphp
                        @foreach ($data as $item)
                        @if ($item->quiz)
                        @php $quizCount++; @endphp
                        <div class="col-md-12 col-12">
                            <div class="card card-quiz">
                                <div class="card-header">
                                    <h5>{{ $item->quiz->nama_quiz }}</h5>
                                    <p class="card-header-description">
                                        {{ $item->quiz->desc }}
                                    </p>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <div class="quiz-info">
                                                <p><i class="fas fa-clock mr-2"></i>Durasi:
                                                    <strong>{{ $item->quiz->durasi }} Menit</strong>
                                                </p>
                                                <p><i class="fas fa-info-circle mr-2"></i>Tipe:
                                                    <strong>{{ $item->quiz->typ }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-5 text-md-right">
                                            @php
                                            $email = Auth::user()->email;
                                            $student = DB::table('master_siswa')->where('email', $email)->first();
                                            $id_siswa = $student ? $student->id_siswa : null;

                                            // Check if student has already taken this quiz
                                            $sudahMengerjakan = DB::table('trans_nilai')
                                            ->where('id_siswa', $id_siswa)
                                            ->where('id_quiz', $item->quiz->id_quiz)
                                            ->exists();
                                            @endphp

                                            @if ($sudahMengerjakan)
                                            <button type="button" class="btn-midnight disabled" style="opacity: 0.7; cursor: not-allowed;"
                                                data-toggle="tooltip" data-placement="top" title="Anda sudah mengerjakan quiz ini">
                                                <i class="fas fa-check-circle mr-2"></i>Sudah Dikerjakan
                                            </button>
                                            @else
                                            <a href="{{ route('mulai.ujian', ['id' => base64_encode($item->quiz->id_quiz)]) }}"
                                                class="btn-midnight btn-start-test"
                                                data-id="{{ base64_encode($item->quiz->id_quiz) }}">
                                                <i class="fas fa-pencil-alt mr-2"></i>Mulai Quiz
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        @if ($quizCount == 0)
                        <div class="col-12">
                            <div class="alert alert-info">
                                Belum ada quiz untuk kelas ini.
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tombol toggle chatbot -->
<button id="chat-toggle" class="btn btn-lg">
    <i class="bi bi-chat-dots-fill"></i>
</button>

<div id="chatbot" class="card">
    <div class="chatbot-header">
        <span><i class="bi bi-headset me-2"></i>Chat dengan Kami</span>
        <button class="btn-close-chat" onclick="toggleChat()"><i class="bi bi-x"></i></button>
    </div>
    <div class="chatbot-body" id="chat-content">
        <!-- Chat messages will be loaded here -->
    </div>
    <div class="chatbot-footer">
        <div class="chat-input-container">
            <input type="text" id="user-input" class="chat-input" placeholder="Tulis pesan..." autocomplete="off">
            <button class="chat-send-btn" onclick="sendMessage()">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $(".btn-start-test").click(function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var quizDuration = $(this).closest('.card-quiz').find('.quiz-info strong:first').text()
                .split(' ')[0];

            Swal.fire({
                icon: 'warning',
                title: 'Apakah kamu sudah siap?',
                html: 'Latihan soal ini akan berlangsung selama <b>' + quizDuration +
                    ' Menit.</b><br>Manfaatkan waktu semaksimal mungkin.',
                showCancelButton: true,
                confirmButtonText: 'Mulai',
                cancelButtonText: 'Belum',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-cancel-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set a flag in session to indicate we're coming from detail page
                    $.ajax({
                        url: '/set-from-detail',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            // Redirect to the exam page
                            window.location.href = `/kelas/mulai-ujian/${id}`;
                        }
                    });
                }
            });
        });
    });

    function toggleChat() {
        const chat = document.getElementById('chatbot');
        chat.style.display = chat.style.display === 'block' ? 'none' : 'block';
    }

    document.getElementById('chat-toggle').addEventListener('click', toggleChat);

    function sendMessage() {
        const input = document.getElementById('user-input');
        const message = input.value.trim();
        if (message !== '') {
            const chatContent = document.getElementById('chat-content');

            // Tambahkan pesan pengguna
            const userMsg = document.createElement('div');
            userMsg.className = 'chat-msg user';
            userMsg.innerText = message;
            chatContent.appendChild(userMsg);

            // Auto scroll ke bawah
            chatContent.scrollTop = chatContent.scrollHeight;

            // Simulasi balasan bot
            setTimeout(() => {
                const botReply = document.createElement('div');
                botReply.className = 'chat-msg bot';
                botReply.innerText = 'Terima kasih atas pesan Anda! Silahkan hubungi nomor berikut';
                chatContent.appendChild(botReply);
                chatContent.scrollTop = chatContent.scrollHeight;
            }, 600);

            input.value = '';
        }
    }


    let lastMessageId = null;

    $(document).ready(function() {
        fetchChatData(); // first load
        setInterval(fetchChatData, 5000);
    });

    function fetchChatData() {
        $.ajax({
            url: "{{ route('chat.getData') }}",
            type: "GET",
            data: {
                last_id: lastMessageId
            },
            success: function(response) {
                if (response.length > 0) {
                    response.forEach(chat => {
                        const chatHtml = `
                        <div class="chat-msg bot" data-id="${chat.id}">
                            ${chat.message ?? 'Tidak ada pesan'}
                            ${
                                chat.nama_file ?
                                `<div class="audio-wrapper" data-audio="${chat.audio_url}"></div>` :
                                '<p class="text-muted">Audio tidak tersedia</p>'
                            }
                        </div>`;

                        $('#chat-content').append(chatHtml);
                    });

                    // update lastMessageId
                    lastMessageId = response[response.length - 1].id;

                    // buat audio dari div
                    $('#chat-content .audio-wrapper').each(function() {
                        if (!$(this).hasClass('rendered')) {
                            const audioUrl = $(this).data('audio');
                            const audio = document.createElement('audio');
                            audio.src = audioUrl;
                            audio.controls = true;
                            audio.preload = 'auto';
                            $(this).append(audio);
                            $(this).addClass('rendered');
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Gagal ambil data:', error);
            }
        });
    }
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<style>
    /* Existing styles */

    /* Modal styles for exam notification */
    .modal-ujian-error {
        border-radius: 12px;
        overflow: hidden;
    }

    .modal-ujian-error .modal-header {
        background-color: #ff5252;
        color: white;
        border-bottom: none;
    }

    .modal-ujian-error .modal-body {
        padding: 25px;
    }

    .modal-ujian-error .modal-footer {
        border-top: none;
        padding: 0 25px 25px;
    }

    .modal-ujian-error .btn-secondary {
        background-color: #191970;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .modal-ujian-error .btn-secondary:hover {
        background-color: #2a2a8a;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(25, 25, 112, 0.2);
    }

    .error-icon {
        font-size: 48px;
        color: #ff5252;
        margin-bottom: 15px;
    }
</style>

<!-- Existing content -->

<!-- Add this at the end of the file, before the closing body tag -->
@if (session('ujian_error'))
<!-- Modal for exam error notification -->
<div class="modal fade" id="ujianErrorModal" tabindex="-1" role="dialog" aria-labelledby="ujianErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-ujian-error">
            <div class="modal-header">
                <h5 class="modal-title" id="ujianErrorModalLabel">Ujian Tidak Tersedia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h5 class="mb-3">Ujian Sudah Dikerjakan</h5>
                <p>{{ session('ujian_error') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#ujianErrorModal').modal('show');
    });
</script>
@endif
@endsection
@section('script')

@endsection
