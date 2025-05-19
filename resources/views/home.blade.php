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
                                        {{ Auth::user()->role }}!
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
