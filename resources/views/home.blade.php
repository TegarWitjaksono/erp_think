@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-primary">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right text-muted">
                            <i class="far fa-clock mr-1"></i> Last updated: {{ now()->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Welcome Card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-gradient-primary text-white overflow-hidden shadow-lg">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="mr-4">
                                    <div class="avatar bg-white text-primary rounded-circle p-3 shadow-sm">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-weight-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                                    <p class="mb-0 opacity-75">Sistem Informasi Pembelajaran Online</p>
                                </div>
                                <div class="ml-auto text-right">
                                    <span class="badge badge-light font-weight-bold py-2 px-3">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        @if (Auth::user()->role == 0)
                                            Siswa
                                        @elseif (Auth::user()->role == 1)
                                            Guru
                                        @else
                                            Administrator
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="wave-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"
                                    class="wave">
                                    <path fill="rgba(255, 255, 255, 0.1)"
                                        d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,133.3C672,139,768,181,864,181.3C960,181,1056,139,1152,122.7C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Overview -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0 mr-2">Analytics Overview</h5>
                            <div class="flex-grow-1 border-bottom"></div>
                        </div>
                    </div>
                </div>

                <!-- Primary Stats -->
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-bg bg-primary-light rounded-lg p-3 mr-3">
                                        <i class="fas fa-chalkboard-teacher text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Guru</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="font-weight-bold mb-0">{{ $guruCount }}</h3>
                                    <span class="badge badge-soft-success ml-2">
                                        <i class="fas fa-user-check"></i> {{ $activeGuruCount ?? 0 }} active
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent p-0">
                                <a href="{{ route('master_guru.index') }}" class="btn btn-link text-primary btn-block">
                                    Kelola Guru <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-bg bg-success-light rounded-lg p-3 mr-3">
                                        <i class="fas fa-user-graduate text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Siswa</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="font-weight-bold mb-0">{{ $siswaCount }}</h3>
                                    <span class="badge badge-soft-success ml-2">
                                        <i class="fas fa-user-check"></i> {{ $activeSiswaCount ?? 0 }} active
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent p-0">
                                <a href="{{ route('master_siswa.index') }}" class="btn btn-link text-success btn-block">
                                    Kelola Siswa <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-bg bg-warning-light rounded-lg p-3 mr-3">
                                        <i class="fas fa-book-open text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Materi</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="font-weight-bold mb-0">{{ $materiCount }}</h3>
                                    <span class="badge badge-soft-warning ml-2">
                                        <i class="fas fa-check-circle"></i> {{ $publishedMateriCount ?? 0 }} published
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent p-0">
                                <a href="{{ route('master_materi.index') }}" class="btn btn-link text-warning btn-block">
                                    Kelola Materi <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-bg bg-danger-light rounded-lg p-3 mr-3">
                                        <i class="fas fa-users text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Users</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="font-weight-bold mb-0">{{ $userCount }}</h3>
                                    <span class="badge badge-soft-danger ml-2">
                                        <i class="fas fa-circle"></i> {{ $onlineUserCount ?? 0 }} online
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent p-0">
                                <a href="{{ route('master_user.index') }}" class="btn btn-link text-danger btn-block">
                                    Kelola Users <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Stats Row -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0 mr-2">Module Management</h5>
                            <div class="flex-grow-1 border-bottom"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-left-info border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="icon-circle bg-info text-white mr-3">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div>
                                    <h6 class="text-info mb-1">Kelas</h6>
                                    <h4 class="font-weight-bold mb-0">{{ $kelasCount }}</h4>
                                </div>
                                <div class="ml-auto">
                                    <a href="{{ route('master_kelas.index') }}" class="btn btn-sm btn-info rounded-pill">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-left-success border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="icon-circle bg-success text-white mr-3">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div>
                                    <h6 class="text-success mb-1">Bank Soal</h6>
                                    <h4 class="font-weight-bold mb-0">{{ $soalCount }}</h4>
                                </div>
                                <div class="ml-auto">
                                    <a href="{{ route('master_soal.index') }}"
                                        class="btn btn-sm btn-success rounded-pill">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-left-warning border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="icon-circle bg-warning text-white mr-3">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <h6 class="text-warning mb-1">Jurusan</h6>
                                    <h4 class="font-weight-bold mb-0">{{ $jurusanCount }}</h4>
                                </div>
                                <div class="ml-auto">
                                    <a href="{{ route('master_jurusan.index') }}"
                                        class="btn btn-sm btn-warning rounded-pill">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Stats -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0 mr-2">System Overview</h5>
                            <div class="flex-grow-1 border-bottom"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="row m-0">
                                    <div class="col-sm-3 p-4 border-right">
                                        <div class="text-center">
                                            <div class="display-4 font-weight-bold text-primary mb-1">
                                                {{ $quizCount ?? 0 }}</div>
                                            <div class="text-uppercase text-muted small">Total Quiz</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 p-4 border-right">
                                        <div class="text-center">
                                            <div class="display-4 font-weight-bold text-success mb-1">
                                                {{ $jadwalCount ?? 0 }}</div>
                                            <div class="text-uppercase text-muted small">Jadwal Aktif</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 p-4 border-right">
                                        <div class="text-center">
                                            <div class="display-4 font-weight-bold text-warning mb-1">
                                                {{ $kategoriCount ?? 0 }}</div>
                                            <div class="text-uppercase text-muted small">Kategori</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 p-4">
                                        <div class="text-center">
                                            <div class="display-4 font-weight-bold text-info mb-1">
                                                {{ $totalLoginToday ?? 0 }}</div>
                                            <div class="text-uppercase text-muted small">Login Hari Ini</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        /* Base styles */
        .content-wrapper {
            background-color: #f8f9fc;
            padding: 1.5rem;
        }

        /* Card styles */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }

        /* Icon styles */
        .icon-bg {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .bg-primary-light {
            background-color: rgba(78, 115, 223, 0.1);
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.1);
        }

        .icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Badge styles */
        .badge-soft-success {
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }

        .badge-soft-warning {
            color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
        }

        .badge-soft-danger {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* Animation for wave */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave {
            width: 100%;
            height: 60px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .display-4 {
                font-size: 2rem;
            }
        }
    </style>
@endsection
