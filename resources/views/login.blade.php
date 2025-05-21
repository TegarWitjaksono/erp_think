<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ERP THINK - Sistem ERP Coffee</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ url('img/coffe.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ url('img/coffe.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ url('img/coffe.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
    <style>
        /* Base styles matching dashboard */
        body {
            background-color: #f3ece7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background-image: url('data:image/svg+xml;utf8,<svg width="100" height="100" transform="rotate(45)" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path fill="%236F4E37" fill-opacity="0.03" d="M50 50m-40 0a40 40 0 1 0 80 0a40 40 0 1 0 -80 0"/></svg>');
            background-size: 200px 200px;
        }
        
        /* Floating coffee beans background */
        .coffee-beans-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }
        
        .coffee-bean {
            position: absolute;
            width: 20px;
            height: 12px;
            background-color: #6f4e37;
            border-radius: 50%;
            opacity: 0.1;
            transform: rotate(0deg);
            animation: float-rotate linear infinite;
        }
        
        @keyframes float-rotate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.1;
            }
            50% {
                opacity: 0.2;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0.1;
            }
        }

        .login-box {
            width: 400px;
            max-width: 90%;
            animation: fadeInUp 0.8s ease-out forwards;
            position: relative;
            z-index: 1;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card styles matching dashboard */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            position: relative;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-outline.card-primary {
            border-top: none;
        }

        /* Header styling */
        .card-header {
            background: linear-gradient(135deg, #6f4e37 0%, #4b2e23 100%);
            color: white;
            border-bottom: none;
            padding: 25px 20px 70px;
            position: relative;
            overflow: hidden;
        }

        .card-header .h1 {
            color: white;
            margin-top: 10px;
            font-weight: 700;
            animation: fadeIn 1s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .brand-subtext {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            animation: fadeIn 1s 0.3s ease-out forwards;
            opacity: 0;
        }

        /* Wave effect like in dashboard */
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
            fill: rgba(255, 255, 255, 0.1);
            animation: wave-animation 10s linear infinite;
        }
        
        @keyframes wave-animation {
            0% { transform: translateX(0); }
            50% { transform: translateX(-5%); }
            100% { transform: translateX(0); }
        }

        /* Card body styling */
        .card-body {
            padding: 30px 25px;
            position: relative;
            z-index: 1;
            margin-top: -20px;
            background-color: #fff;
            border-radius: 12px 12px 0 0;
        }

        .login-box-msg {
            color: #5a5c69;
            font-size: 16px;
            margin-bottom: 25px;
            font-weight: 500;
            animation: fadeIn 1s 0.5s ease-out forwards;
            opacity: 0;
        }

        /* Form elements styling */
        .form-control {
            border-radius: 10px;
            padding: 12px;
            height: auto;
            border: 1px solid #d1d3e2;
            font-size: 15px;
            transition: all 0.3s;
            transform: translateX(-20px);
            opacity: 0;
            animation: slideIn 0.5s forwards;
        }
        
        .input-group:nth-child(2) .form-control {
            animation-delay: 0.1s;
        }
        
        @keyframes slideIn {
            to { transform: translateX(0); opacity: 1; }
        }

        .form-control:focus {
            border-color: #6f4e37;
            box-shadow: 0 0 0 0.2rem rgba(111, 78, 55, 0.25);
        }

        .input-group-text {
            border-radius: 0 10px 10px 0;
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
            border-left: none;
            color: #6f4e37;
        }

        /* Button styling matching dashboard */
        .btn-primary {
            background: linear-gradient(135deg, #6f4e37 0%, #4b2e23 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInButton 0.5s 0.4s forwards;
            position: relative;
            overflow: hidden;
        }
        
        @keyframes fadeInButton {
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4b2e23 0%, #6f4e37 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-primary:hover::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        /* Alert styling */
        .alert {
            border-radius: 10px;
            border-left: 4px solid #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            animation: alertSlideDown 0.5s ease-out forwards;
            transform: translateY(-20px);
            opacity: 0;
        }
        
        @keyframes alertSlideDown {
            to { transform: translateY(0); opacity: 1; }
        }

        /* Logo styling - UPDATED */
        .logo-container {
            background-color: #ffffff;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 5px;
            position: relative;
            z-index: 2;
            animation: pulse 3s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            50% {
                box-shadow: 0 4px 20px rgba(111, 78, 55, 0.3);
            }
            100% {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
        }
        
        .logo-container::before {
            content: '';
            position: absolute;
            width: 110%;
            height: 110%;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(111, 78, 55, 0.2) 0%, rgba(111, 78, 55, 0.1) 100%);
            z-index: -1;
            animation: rotate-gradient 10s linear infinite;
        }
        
        @keyframes rotate-gradient {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-container img {
            width: 100px;
            height: auto;
            object-fit: contain;
            animation: bounce 2s ease infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* Avatar decoration like in dashboard */
        .avatar-decoration {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeInRotate 1s ease-out forwards;
            opacity: 0;
            transform: rotate(-45deg);
        }
        
        @keyframes fadeInRotate {
            to { opacity: 1; transform: rotate(0); }
        }

        /* Password toggle styling */
        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: #6f4e37;
            background-color: #f0f0f0;
        }

        /* Loading overlay for login animation */
        .login-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s;
        }
        
        .login-loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Coffee cup loading animation */
        .coffee-cup-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin-bottom: 30px;
        }
        
        .coffee-cup {
            position: absolute;
            width: 80px;
            height: 100px;
            background-color: white;
            border-radius: 0 0 40px 40px;
            top: 20px;
            left: 35px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .coffee-liquid {
            position: absolute;
            width: 70px;
            height: 0;
            background: linear-gradient(135deg, #6f4e37 0%, #4b2e23 100%);
            border-radius: 0 0 35px 35px;
            top: 30px;
            left: 5px;
            animation: fill-coffee 2s ease-in-out forwards;
        }
        
        @keyframes fill-coffee {
            0% { height: 0; }
            100% { height: 70px; }
        }
        
        .coffee-steam {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 50px;
            opacity: 0;
            animation: show-steam 0.5s ease-out 2s forwards;
        }
        
        @keyframes show-steam {
            to { opacity: 1; }
        }
        
        .steam-particle {
            position: absolute;
            background-color: rgba(111, 78, 55, 0.2);
            border-radius: 50%;
            animation: steam-float 3s ease-out infinite;
        }
        
        @keyframes steam-float {
            0% { 
                transform: translateY(0) scale(1);
                opacity: 0.8;
            }
            100% { 
                transform: translateY(-40px) scale(1.5);
                opacity: 0;
            }
        }
        
        .login-success-message {
            font-size: 24px;
            color: #6f4e37;
            margin-bottom: 10px;
            animation: fadeInMessage 0.5s 2.5s forwards;
            opacity: 0;
        }
        
        .login-redirect-message {
            font-size: 16px;
            color: #5a5c69;
            animation: fadeInMessage 0.5s 2.8s forwards;
            opacity: 0;
        }
        
        @keyframes fadeInMessage {
            to { opacity: 1; }
        }

        /* Modal styling */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-header.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        }

        .modal-body {
            padding: 1.5rem;
            font-size: 1rem;
            color: #5a5c69;
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
        }

        .btn-secondary {
            background-color: #858796;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background-color: #717384;
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transform: translate(0, -50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        /* Inactive account modal specific styles */
        #inactiveAccountModal .modal-content {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.15);
        }

        #inactiveAccountModal .modal-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-bottom: none;
            padding: 1.5rem;
        }

        #inactiveAccountModal .modal-body {
            padding: 2rem;
        }

        #inactiveAccountModal .fa-user-lock {
            color: #dc3545;
            opacity: 0.8;
        }

        #inactiveAccountModal .close {
            color: white;
            opacity: 0.8;
            text-shadow: none;
            transition: opacity 0.3s;
        }

        #inactiveAccountModal .close:hover {
            opacity: 1;
        }

        #inactiveAccountModal .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        #inactiveAccountModal .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .login-box {
                width: 90%;
            }

            .card-header {
                padding: 20px 15px 60px;
            }

            .card-body {
                padding: 25px 15px;
            }

            .logo-container {
                width: 80px;
                height: 80px;
            }

            .logo-container img {
                width: 60px;
            }
        }
    </style>
</head>

<body class="hold-transition login-page">
    <!-- Coffee beans background animation -->
    <div class="coffee-beans-container"></div>
    
    <!-- Loading overlay for login animation -->
    <div class="login-loading-overlay">
        <div class="coffee-cup-container">
            <div class="coffee-cup">
                <div class="coffee-liquid"></div>
            </div>
            <div class="coffee-steam">
                <!-- Steam particles will be added by JS -->
            </div>
        </div>
        <div class="login-success-message">Login Successful!</div>
        <div class="login-redirect-message">Brewing your dashboard...</div>
    </div>
    
    <div class="login-box">
        <!-- Login card -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="avatar-decoration">
                    <i class="fas fa-user-shield text-white"></i>
                </div>
                <!-- Updated logo container with white background -->
                <div class="logo-container">
                    <img src="{{ url('img/coffe.png') }}" alt="Logo">
                </div>
                <a href="#" class="h1"><b>ERP THINK</b></a><br>
                <span class="brand-subtext">Sistem ERP Coffee</span>

                <!-- Wave effect -->
                <div class="wave-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"
                        class="wave">
                        <path
                            d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,133.3C672,139,768,181,864,181.3C960,181,1056,139,1152,122.7C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @error('nip')
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ $message }}
                    </div>
                @enderror

                @if (session('inactive'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-ban mr-2"></i>
                        {{ session('inactive') }}
                    </div>
                @endif

                <form action="{{ route('actionLogin') }}" method="post" name="login-form" id="login-form">
                    @csrf
                    <div class="input-group mb-4">
                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text password-toggle" id="togglePassword">
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- Inactive Account Modal -->
    <div class="modal fade" id="inactiveAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-circle mr-2"></i>Akun Nonaktif
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-lock fa-3x text-danger mb-3"></i>
                        <h5>Akses Ditolak</h5>
                    </div>
                    <p>{{ session('inactive') ?: 'Akun Anda sedang dinonaktifkan. Silakan hubungi administrator untuk informasi lebih lanjut.' }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @if (session('inactive'))
        <script>
            $(document).ready(function() {
                $('#inactiveAccountModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#inactiveAccountModal').modal('show');
            });
        </script>
    @endif

    <!-- jQuery -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.js') }}"></script>

    <!-- Enhanced Animation Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    // Toggle password visibility
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    // Toggle eye icon
                    this.querySelector('span').classList.toggle('fa-eye');
                    this.querySelector('span').classList.toggle('fa-eye-slash');
                });
            }
            
            // Create background coffee beans with enhanced variety
            const beansContainer = document.querySelector('.coffee-beans-container');
            const beanCount = 20; // Increased count
            
            if (beansContainer) {
                for (let i = 0; i < beanCount; i++) {
                    const bean = document.createElement('div');
                    bean.classList.add('coffee-bean');
                    
                    // Random position and animation
                    const posX = Math.random() * 100;
                    const posY = Math.random() * 100;
                    const duration = Math.random() * 20 + 10;
                    const delay = Math.random() * 5;
                    const rotation = Math.random() * 360;
                    const size = Math.random() * 10 + 15; // Varied sizes
                    const opacity = Math.random() * 0.1 + 0.05; // Varied opacity
                    
                    bean.style.left = posX + '%';
                    bean.style.top = posY + '%';
                    bean.style.width = size + 'px';
                    bean.style.height = (size * 0.6) + 'px';
                    bean.style.opacity = opacity;
                    bean.style.animationDuration = duration + 's';
                    bean.style.animationDelay = delay + 's';
                    bean.style.transform = `rotate(${rotation}deg)`;
                    
                    beansContainer.appendChild(bean);
                }
            }
            
            // Create steam particles for coffee cup animation with enhanced variety
            const steamParticlesContainer = document.querySelector('.coffee-steam');
            
            function createSteamParticles() {
                if (!steamParticlesContainer) return;
                
                for (let i = 0; i < 12; i++) { // Increased count
                    const steamParticle = document.createElement('div');
                    steamParticle.classList.add('steam-particle');
                    
                    // Random size, position and animation
                    const size = Math.random() * 10 + 5;
                    const posX = Math.random() * 60 - 5; // Wider spread
                    const delay = Math.random() * 2;
                    const duration = Math.random() * 1.5 + 2;
                    const opacity = Math.random() * 0.3 + 0.5; // Varied opacity
                    
                    steamParticle.style.width = size + 'px';
                    steamParticle.style.height = size + 'px';
                    steamParticle.style.left = posX + 'px';
                    steamParticle.style.opacity = opacity;
                    steamParticle.style.animationDuration = duration + 's';
                    steamParticle.style.animationDelay = delay + 's';
                    
                    steamParticlesContainer.appendChild(steamParticle);
                }
            }
            
            createSteamParticles();
            
            // Enhanced login form submission animation
            const loginForm = document.getElementById('login-form');
            const loginLoadingOverlay = document.querySelector('.login-loading-overlay');
            
            if (loginForm && loginLoadingOverlay) {
                loginForm.addEventListener('submit', function(e) {
                    // Don't prevent default - let the form submit normally
                    
                    // Add shake animation to cup before showing overlay
                    const cup = document.querySelector('.coffee-cup');
                    if (cup) {
                        cup.style.animation = 'shake 0.5s ease-in-out';
                        setTimeout(() => {
                            cup.style.animation = '';
                        }, 500);
                    }
                    
                    // Show coffee cup loading animation with delay
                    setTimeout(() => {
                        loginLoadingOverlay.classList.add('active');
                    }, 300);
                });
            }
            
            // Enhanced ripple effect for buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const x = e.clientX - e.target.getBoundingClientRect().left;
                    const y = e.clientY - e.target.getBoundingClientRect().top;
                    
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple');
                    
                    const diameter = Math.max(this.clientWidth, this.clientHeight);
                    const radius = diameter / 2;
                    
                    ripple.style.width = ripple.style.height = `${diameter}px`;
                    ripple.style.left = `${x - radius}px`;
                    ripple.style.top = `${y - radius}px`;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add 3D tilt effect to card
            const card = document.querySelector('.card');
            if (card) {
                card.addEventListener('mousemove', function(e) {
                    const cardRect = this.getBoundingClientRect();
                    const cardCenterX = cardRect.left + cardRect.width / 2;
                    const cardCenterY = cardRect.top + cardRect.height / 2;
                    const mouseX = e.clientX - cardCenterX;
                    const mouseY = e.clientY - cardCenterY;
                    
                    // Calculate rotation (limited to small angles)
                    const rotateY = mouseX * 0.01;
                    const rotateX = -mouseY * 0.01;
                    
                    // Apply transform
                    this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(0)`;
                });
                
                card.addEventListener('mouseleave', function() {
                    // Reset transform
                    this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
                });
            }
            
            // Add shake animation keyframes dynamically
            const style = document.createElement('style');
            style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    20% { transform: translateX(-5px) rotate(-5deg); }
                    40% { transform: translateX(5px) rotate(5deg); }
                    60% { transform: translateX(-3px) rotate(-3deg); }
                    80% { transform: translateX(3px) rotate(3deg); }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>

</html>
