@extends('depan.layout')

@section('konten')
<!-- Available Classes Section -->
<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-9">
            <h3 class="title">Kelas yang tersedia</h3>
        </div>
        <div class="col-md-3 text-right">
            <a href="#" class="btn-midnight" data-toggle="modal" data-target="#loginModal">Lihat semua</a>
        </div>
    </div>

    <div class="row">
        @php
            $jadwals = App\Models\MasterJadwal::with(['kelas', 'detailJadwal'])->where('sts', 1)->get();
            $displayedJadwal = [];
        @endphp
        
        @forelse($jadwals as $jadwal)
            @if (!in_array($jadwal->nama_jadwal, $displayedJadwal))
                @php
                    $displayedJadwal[] = $jadwal->nama_jadwal;
                    $detailJadwal = $jadwal->detailJadwal->first();
                @endphp
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $jadwal->nama_jadwal }}</h5>
                            <small class="text-muted">{{ $jadwal->kelas ? $jadwal->kelas->nama_kelas : 'Kelas tidak tersedia' }}</small>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <p class="card-text">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $jadwal->hari }}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-clock"></i>
                                        @if ($detailJadwal && $detailJadwal->jam_in && $detailJadwal->jam_out)
                                            {{ $detailJadwal->jam_in }} - {{ $detailJadwal->jam_out }}
                                        @else
                                            Waktu belum ditentukan
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-5 text-right">
                                    <a href="#" class="btn-midnight" data-toggle="modal" data-target="#loginModal">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    Belum ada jadwal yang tersedia saat ini.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login Diperlukan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-5">
                <!-- Elegant Gradient Background -->
                <div class="gradient-background"></div>
                
                <!-- Floating Elements -->
                <div class="floating-elements">
                    <div class="floating-circle circle1"></div>
                    <div class="floating-circle circle2"></div>
                    <div class="floating-circle circle3"></div>
                    <div class="floating-circle circle4"></div>
                </div>
                
                <!-- Animated Icon -->
                <div class="icon-container">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <div class="pulse-ring"></div>
                </div>
                
                <!-- Elegant Title -->
                <div class="title-container">
                    <h4 class="elegant-title">Akses Terbatas</h4>
                </div>
                
                <!-- Message with Animation -->
                <div class="message-container">
                    <p class="elegant-message">Untuk melihat detail jadwal, Anda perlu login terlebih dahulu sebagai siswa.</p>
                </div>
                
                <!-- Gradient Button -->
                <div class="button-container">
                    <a href="{{ url('login') }}" class="elegant-button">
                        <span>Login Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Light Theme Modal Styling */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
        background: #ffffff;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: var(--primary-color);
        border-bottom: none;
        padding: 20px 25px;
        position: relative;
    }
    
    .modal-title {
        font-weight: 600;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 2;
        color: var(--primary-color);
    }
    
    .modal-header .close {
        color: var(--primary-color);
        opacity: 0.8;
        text-shadow: none;
        transition: all 0.3s;
        position: relative;
        z-index: 2;
    }
    
    .modal-header .close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 40px 30px;
        position: relative;
        overflow: hidden;
        background: #ffffff;
    }
    
    /* Elegant Gradient Background */
    .gradient-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, rgba(240, 240, 255, 0.5), rgba(230, 240, 255, 0.8));
        z-index: 1;
    }
    
    /* Floating Elements */
    .floating-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }
    
    .floating-circle {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #6a7bff);
        opacity: 0.1;
        filter: blur(5px);
    }
    
    .circle1 {
        width: 100px;
        height: 100px;
        top: -30px;
        right: -30px;
        animation: float-circle 15s infinite ease-in-out;
    }
    
    .circle2 {
        width: 80px;
        height: 80px;
        bottom: -20px;
        left: -20px;
        animation: float-circle 12s infinite ease-in-out reverse;
    }
    
    .circle3 {
        width: 60px;
        height: 60px;
        top: 40%;
        left: -10px;
        animation: float-circle 10s infinite ease-in-out 2s;
    }
    
    .circle4 {
        width: 40px;
        height: 40px;
        bottom: 30%;
        right: -10px;
        animation: float-circle 8s infinite ease-in-out 1s;
    }
    
    @keyframes float-circle {
        0%, 100% {
            transform: translate(0, 0);
        }
        25% {
            transform: translate(10px, -15px);
        }
        50% {
            transform: translate(20px, 0);
        }
        75% {
            transform: translate(10px, 15px);
        }
    }
    
    /* Icon Animation */
    .icon-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        z-index: 2;
    }
    
    .icon-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f0f7ff, #e6f0ff);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        animation: pulse 2s infinite ease-in-out;
        z-index: 2;
    }
    
    .icon-wrapper i {
        font-size: 40px;
        background: linear-gradient(135deg, var(--primary-color), #6a7bff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.1));
    }
    
    .pulse-ring {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid var(--primary-color);
        opacity: 0;
        animation: pulse-ring 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    @keyframes pulse-ring {
        0% {
            transform: scale(0.8);
            opacity: 0.8;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    
    /* Title Animation */
    .title-container {
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }
    
    .elegant-title {
        font-weight: 700;
        font-size: 28px;
        background: linear-gradient(135deg, var(--primary-color), #6a7bff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        display: inline-block;
        animation: fade-in 1s forwards;
    }
    
    .elegant-title::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background: linear-gradient(90deg, var(--primary-color), #6a7bff);
        animation: line-expand 2s forwards 0.5s;
    }
    
    @keyframes fade-in {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes line-expand {
        0% {
            width: 0;
        }
        100% {
            width: 100%;
        }
    }
    
    /* Message Animation */
    .message-container {
        margin-bottom: 30px;
        position: relative;
        z-index: 2;
    }
    
    .elegant-message {
        font-size: 16px;
        max-width: 85%;
        margin: 0 auto;
        line-height: 1.6;
        color: #6c757d;
        position: relative;
        animation: fade-in 1s forwards 0.7s;
        opacity: 0;
    }
    
    /* Elegant Button */
    .button-container {
        margin-top: 20px;
        position: relative;
        z-index: 2;
    }
    
    .elegant-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 35px;
        background: linear-gradient(135deg, var(--primary-color), #6a7bff);
        background-size: 200% 100%;
        color: white;
        font-weight: 600;
        font-size: 16px;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(106, 123, 255, 0.3);
        position: relative;
        overflow: hidden;
        animation: fade-in 1s forwards 1s, gradient-shift 3s infinite linear;
        opacity: 0;
    }
    
    .elegant-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(106, 123, 255, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .elegant-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: all 0.6s;
    }
    
    .elegant-button:hover::before {
        left: 100%;
    }
    
    .elegant-button i {
        margin-left: 8px;
        transition: all 0.3s;
    }
    
    .elegant-button:hover i {
        transform: translateX(5px);
    }
    
    @keyframes gradient-shift {
        0% {
            background-position: 0% 0%;
        }
        100% {
            background-position: 200% 0%;
        }
    }
    
    /* Modal transition effect */
    .modal.fade .modal-dialog {
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        transform: translateY(20px) scale(0.95);
        opacity: 0;
    }
    
    .modal.show .modal-dialog {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
    
    /* Card styling (keeping existing) */
    .card-header {
        display: flex;
        flex-direction: column;
    }
    
    .card-header small {
        margin-top: 5px;
    }
</style>
@endsection