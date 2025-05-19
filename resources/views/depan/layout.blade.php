<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sabasa - Platform Belajar Online</title>
    <link rel="icon" href="{{ url('img/image.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #191970;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --hover-color: #2a2a8a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 56px;
        }

        /* Modern Navbar Styles */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(25, 25, 112, 0.08);
        }

        .navbar.scrolled {
            padding: 10px 0;
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .navbar-brand img {
            transition: all 0.3s ease;
            width: 38px;
            height: 38px;
        }

        .navbar-brand:hover img {
            transform: translateY(-2px);
        }

        .navbar-nav {
            margin-left: 20px;
        }

        .navbar-nav .nav-item {
            margin: 0 5px;
            position: relative;
        }

        .navbar-nav .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            padding: 10px 18px !important;
            border-radius: 30px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            letter-spacing: 0.3px;
            font-size: 0.95rem;
            overflow: hidden;
        }

        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(25, 25, 112, 0.05), rgba(77, 77, 255, 0.1));
            border-radius: 30px;
            z-index: -1;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link:hover::before {
            opacity: 1;
            transform: scale(1);
        }

        .navbar-nav .nav-link.active {
            color: white !important;
            background: linear-gradient(135deg, var(--primary-color), #4a4ac0);
            box-shadow: 0 4px 12px rgba(25, 25, 112, 0.2);
        }

        .navbar-nav .nav-link.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(25, 25, 112, 0.25);
        }

        .navbar-nav .nav-link i {
            margin-right: 6px;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .navbar-nav .nav-link:hover i {
            transform: translateY(-2px);
        }

        .navbar-toggler {
            border: none;
            padding: 10px;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(25, 25, 112, 0.05), rgba(77, 77, 255, 0.1));
            transition: all 0.3s ease;
        }

        .navbar-toggler:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(25, 25, 112, 0.1);
        }

        .navbar-toggler:hover {
            background: linear-gradient(135deg, rgba(25, 25, 112, 0.1), rgba(77, 77, 255, 0.15));
        }

        /* Responsive navbar adjustments */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: rgba(255, 255, 255, 0.98);
                border-radius: 12px;
                padding: 15px;
                margin-top: 10px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            
            .navbar-nav .nav-link {
                padding: 12px 18px !important;
                margin: 5px 0;
            }
        }
        
        /* Special styling for login button */
        .nav-link.btn-login {
            background: linear-gradient(135deg, var(--primary-color), #4a4ac0);
            color: white !important;
            box-shadow: 0 4px 12px rgba(25, 25, 112, 0.2);
            transition: all 0.3s ease;
        }
        
        .nav-link.btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(25, 25, 112, 0.25);
            background: linear-gradient(135deg, #2a2a8a, #5a5ad0);
        }

        /* When login is active, use the same styling as btn-login but with different hover */
        .navbar-nav .nav-link.active[href$="login"] {
            background: linear-gradient(135deg, var(--primary-color), #4a4ac0);
            color: white !important;
            box-shadow: 0 4px 12px rgba(25, 25, 112, 0.2);
        }

        .navbar-nav .nav-link.active[href$="login"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(25, 25, 112, 0.25);
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.5rem;
        }

        .card-header h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Button Styles */
        .btn-midnight {
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
        }

        .btn-midnight:hover {
            background-color: var(--hover-color);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Feature Card Styles */
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
            margin-bottom: 20px;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-card h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        /* Title Styles */
        .title {
            color: var(--primary-color);
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        /* Testimonial Styles */
        .testimonial-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin: 1rem;
            padding: 2rem;
        }

        .testimonial-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        /* Carousel Styles */
        .carousel {
            background-color: var(--primary-color);
            padding: 20px;
        }

        .carousel-item img {
            border-radius: 15px;
            width: 100%;
            height: 300px;
            margin: 0 auto;
            object-fit:cover;
            .carousel-item img {
                width: 100%;
                object-fit: cover;
                aspect-ratio: 16/9; /* Optional: untuk menjaga rasio aspek */
            }
        }

        /* Footer Styles */

        .footer-section {
            background-color: var(--primary-color);
            color: white;
            padding-top: 20px;
            position: relative;
        }

        .footer-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #191970, #4d4dff, #191970);
        }

        .footer-content {
            padding: 60px 0 40px;
        }

        .footer-logo {
            background-color: white;
            padding: 10px !important;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .footer-logo:hover {
            transform: scale(1.05);
        }

        .footer-section h5 {
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
            display: inline-block;
        }

        .footer-section h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: rgba(255, 255, 255, 0.5);
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 12px;
            padding-left: 0;
            position: relative;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .footer-copyright {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 15px 0;
            font-size: 14px;
        }

        .footer-copyright p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-links a {
            color: white;
            margin-right: 1rem;
            transition: opacity 0.3s ease;
        }

        .social-links a:hover {
            color: white;
            opacity: 0.8;
            text-decoration: none;
        }

        .footer-copyright {
            background-color: rgba(0,0,0,0.1);
        }

        /* Card Text Styles */
        .card-text {
            color: var(--text-color);
            margin-bottom: 0;
        }

        .card-text i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }

            .feature-card {
                margin-bottom: 20px;
            }

            .card-body .row {
                text-align: center;
            }

            .btn-midnight {
                margin-top: 1rem;
            }
        }

        .nav-link[data-widget="fullscreen"] {
            padding: 8px;
            margin-left: 5px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .nav-link[data-widget="fullscreen"]:hover {
            background: rgba(25, 25, 112, 0.1);
            transform: scale(1.1);
        }

        .nav-link[data-widget="fullscreen"] i {
            font-size: 1.1rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .nav-link[data-widget="fullscreen"].is-fullscreen i {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ url('img/image.png') }}" alt="Sabasa Logo" height="40" width="40">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}"><i class="fas fa-home"></i> Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('jadwal*') ? 'active' : '' }}" href="#" data-toggle="modal" data-target="#jadwalLoginModal"><i class="fas fa-clipboard"></i> Jadwal Saya</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="{{ route('login') }}">
                        <i class="fas fa-user"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-widget="fullscreen" role="button">
                        <i class="fas fa-expand"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Carousel -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="{{ url('img/image.png') }}" alt="First slide" style="object-fit: cover;">
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="{{ url('img/image.png') }}" alt="Second slide" style="object-fit: cover;">
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="{{ url('img/image.png') }}" alt="Third slide" style="object-fit: cover;">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

@yield('konten')

<!-- Features Section -->
<section class="container my-5">
    <div class="row text-center">
        <div class="col-12 mb-5">
            <h5 class="title">Keunggulan Kami</h5>
        </div>
        <!-- Feature Cards -->
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <h4>Praktis</h4>
                <p>Kamu bisa mengerjakan soal kapan pun dan di mana saja.</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <h4>Terpercaya</h4>
                <p>Soal dibuat oleh tim ahli sesuai dengan standar UTBK.</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <h4>Gratis</h4>
                <p>Soal latihan dan try out tersedia secara gratis.</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="feature-card">
                <h4>Up to Date</h4>
                <p>Soal terbaru diperbarui setiap pekan.</p>
            </div>
        </div>
    </div>
</section>



<!-- Footer -->
<footer class="footer-section">
    <div class="footer-content py-5">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 mb-4">
                    <img src="{{ url('img/image.png') }}" alt="Sabasa Logo" class="footer-logo mb-4"
                        width="40" height="40"
                        style="background-color: white; padding: 5px; border-radius: 4px;">
                    <h5>Tentang Sabasa</h5>
                    <p class="text-light opacity-75">
                        Platform belajar online yang menyediakan latihan soal dan try out untuk persiapan UTBK
                        dengan materi yang selalu diperbarui.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Tautan</h5>
                    <div class="footer-links">
                        <a href="#">Beranda</a>
                        <a href="#">Tentang Kami</a>
                        <a href="#">Latihan Soal</a>
                        <a href="#">Try Out</a>
                        <a href="#">Blog</a>
                    </div>
                </div>

                <!-- Support -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Dukungan</h5>
                    <div class="footer-links">
                        <a href="#">Pusat Bantuan</a>
                        <a href="#">FAQ</a>
                        <a href="#">Syarat dan Ketentuan</a>
                        <a href="#">Kebijakan Privasi</a>
                    </div>
                </div>

                <!-- Contact -->
                <div class="col-lg-3 mb-4">
                    <h5>Kontak</h5>
                    <div class="footer-links">
                        <a href="mailto:info@sabasa.id">info@sabasa.id</a>
                        <a href="tel:+6281234567890">+62 812-3456-7890</a>
                        <p class="text-light opacity-75 mt-2">
                            Jl. Pendidikan No. 123<br>
                            Jakarta Selatan, 12345
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-copyright py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Sabasa. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Jadwal Login Modal -->
<div class="modal fade" id="jadwalLoginModal" tabindex="-1" role="dialog" aria-labelledby="jadwalLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jadwalLoginModalLabel">Login Diperlukan</h5>
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
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="pulse-ring"></div>
                </div>
                
                <!-- Elegant Title -->
                <div class="title-container">
                    <h4 class="elegant-title">Jadwal Pribadi</h4>
                </div>
                
                <!-- Message with Animation -->
                <div class="message-container">
                    <p class="elegant-message">Untuk melihat jadwal pribadi Anda, silakan login terlebih dahulu ke akun Anda.</p>
                </div>
                
                <!-- Gradient Button -->
                <div class="button-container">
                    <a href="{{ route('login') }}" class="elegant-button">
                        <span>Login Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Navbar scroll effect
    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('.navbar').addClass('scrolled');
        } else {
            $('.navbar').removeClass('scrolled');
        }
    });

    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Fullscreen functionality
    document.querySelector('[data-widget="fullscreen"]').addEventListener('click', function(e) {
        e.preventDefault();
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
            this.querySelector('i').classList.remove('fa-expand');
            this.querySelector('i').classList.add('fa-compress');
            this.classList.add('is-fullscreen');
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                this.querySelector('i').classList.remove('fa-compress');
                this.querySelector('i').classList.add('fa-expand');
                this.classList.remove('is-fullscreen');
            }
        }
    });

    // Handle fullscreen change event
    document.addEventListener('fullscreenchange', function() {
        const fullscreenBtn = document.querySelector('[data-widget="fullscreen"]');
        if (!document.fullscreenElement) {
            fullscreenBtn.querySelector('i').classList.remove('fa-compress');
            fullscreenBtn.querySelector('i').classList.add('fa-expand');
            fullscreenBtn.classList.remove('is-fullscreen');
        }
    });

    // Handle fullscreen error
    document.addEventListener('fullscreenerror', function(event) {
        console.error('Error attempting to enable fullscreen:', event);
    });
</script>

</body>
</html>