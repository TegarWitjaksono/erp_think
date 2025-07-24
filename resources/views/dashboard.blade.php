<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP THINK - Sistem ERP Coffee</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ url('img/coffe.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ url('img/coffe.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ url('img/coffe.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}  ">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}  ">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ url('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ url('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    @yield('style')

    <style>
        /* Modern Coffee Theme Variables */
        :root {
            --coffee-primary: #6f4e37;
            --coffee-secondary: #4b2e23;
            --coffee-light: #f3ece7;
            --coffee-accent: #a98467;
            --coffee-cream: #f8f5f0;
            --coffee-text: #5a5c69;
            --coffee-text-light: #858796;
            --coffee-danger: #dc3545;
            --coffee-success: #28a745;
            --coffee-warning: #ffc107;
            --coffee-info: #17a2b8;
            --coffee-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            --coffee-shadow-hover: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
            --coffee-gradient: linear-gradient(135deg, var(--coffee-primary) 0%, var(--coffee-secondary) 100%);
            --coffee-gradient-reverse: linear-gradient(135deg, var(--coffee-secondary) 0%, var(--coffee-primary) 100%);
        }

        /* Core minimalist styling */
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: var(--coffee-light);
            color: var(--coffee-text);
        }

        /* Elegant brand styling */
        .brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        .brand-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
        }

        .brand-logo {
            width: 42px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .brand:hover .brand-logo {
            transform: scale(1.05);
        }

        .brand-text-container {
            display: flex;
            flex-direction: column;
        }

        .brand-text {
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.2rem;
            margin: 0;
            line-height: 1.2;
            color: var(--coffee-primary);
            transition: color 0.3s ease;
        }

        .brand:hover .brand-text {
            color: var(--coffee-secondary);
        }

        .brand-subtext {
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            color: var(--coffee-text-light);
        }

        /* User info styling */
        .user-info {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0.5rem;
            width: 90%;
            margin: 0.8rem auto;
            background: var(--coffee-gradient);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(111, 78, 55, 0.3);
            transition: all 0.3s ease;
        }

        .user-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.4);
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
        }

        /* Update sidebar structure */
        .sidebar {
            position: relative;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 70px);
            padding: 0;
        }

        /* Update menu area with max height */
        .menu-area {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0;
            margin-bottom: 65px;
            max-height: calc(100vh - 200px);
        }

        /* Fixed logout button at bottom */
        .sidebar-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 250px;
            padding: 1rem;
            background: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.03);
            z-index: 1000;
            transition: width 0.3s ease-in-out, left 0.3s ease-in-out;
        }

        /* Adjust sidebar footer when sidebar is collapsed */
        .sidebar-collapse .sidebar-footer {
            width: 4.6rem;
            padding: 0.5rem;
        }

        /* Enhanced logout button */
        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            color: var(--coffee-light);
            background: var(--coffee-gradient);
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(111, 78, 55, 0.2);
        }

        .logout-button:hover {
            background: var(--coffee-gradient-reverse);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.3);
            text-decoration: none;
            color: var(--coffee-light);
        }

        .logout-button i,
        .logout-button span {
            color: var(--coffee-light);
        }

        .logout-button i {
            margin-right: 10px;
            font-size: 1rem;
        }

        /* Adjust logout button for collapsed sidebar */
        .sidebar-collapse .logout-button {
            justify-content: center;
            padding: 0.75rem 0;
        }

        .sidebar-collapse .logout-button i {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .sidebar-collapse .logout-button span {
            display: none;
        }

        /* Ensure proper scrollbar styling */
        .menu-area::-webkit-scrollbar {
            width: 4px;
        }

        .menu-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .menu-area::-webkit-scrollbar-thumb {
            background-color: var(--coffee-accent);
            border-radius: 20px;
            opacity: 0.5;
        }

        .menu-area::-webkit-scrollbar-thumb:hover {
            background-color: var(--coffee-primary);
        }

        /* Enhanced hover animations for sidebar items */
        .nav-sidebar .nav-item {
            margin: 4px 8px;
            width: calc(100% - 16px);
        }

        .nav-sidebar .nav-link {
            position: relative;
            overflow: hidden;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            color: var(--coffee-text);
            transition: all 0.3s ease;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .nav-sidebar .nav-link:not(.active):hover {
            background-color: var(--coffee-light);
            transform: translateX(3px);
        }

        .nav-sidebar .nav-link:not(.active):before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 0;
            background-color: var(--coffee-primary);
            transition: width 0.3s ease;
        }

        .nav-sidebar .nav-link:not(.active):hover:before {
            width: 100%;
        }

        .nav-sidebar .nav-link:not(.active):hover i {
            color: var(--coffee-primary);
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:not(.active):hover p {
            color: var(--coffee-primary);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link.active {
            background: var(--coffee-gradient);
            color: white;
            box-shadow: 0 2px 8px rgba(111, 78, 55, 0.4);
            position: relative;
        }

        /* Active menu indicator */
        .nav-sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: white;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.7);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .nav-sidebar .nav-link.active:before {
            width: 100%;
        }

        .nav-sidebar .nav-link.active i {
            color: white;
        }

        /* Smooth transitions for all elements */
        .nav-sidebar .nav-link i,
        .nav-sidebar .nav-link p {
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link i {
            width: 22px;
            text-align: center;
            margin-right: 10px;
            font-size: 1rem;
            color: var(--coffee-accent);
        }

        .nav-sidebar .nav-link p {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-right: 15px;
        }

        #masterMenu {
            padding-left: 15px;
            max-width: 100%;
        }

        #masterMenu .nav-item {
            width: calc(100% - 15px);
        }

        /* Section headers with subtle styling */
        .nav-header {
            font-size: 0.75rem;
            padding: 0.75rem 1.3rem 0.5rem;
            color: var(--coffee-text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-top: 15px;
        }

        /* Refined navbar */
        .main-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: white;
            box-shadow: var(--coffee-shadow);
        }

        /* Content wrapper styling */
        .content-wrapper {
            background-color: var(--coffee-light);
            background-image:
                radial-gradient(var(--coffee-accent) 0.5px, transparent 0.5px),
                radial-gradient(var(--coffee-accent) 0.5px, transparent 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            background-attachment: fixed;
            opacity: 0.8;
        }

        /* Card styling */
        .card {
            border-radius: 12px;
            box-shadow: var(--coffee-shadow);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            background: white;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--coffee-shadow-hover);
        }

        .card-header {
            background: var(--coffee-gradient);
            color: white;
            border-bottom: none;
            padding: 1rem 1.25rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Footer styling */
        .main-footer {
            background: white;
            color: var(--coffee-text);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem;
            font-weight: 500;
        }

        /* Button styling */
        .btn-coffee {
            background: var(--coffee-gradient);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(111, 78, 55, 0.3);
            transition: all 0.3s ease;
        }

        .btn-coffee:hover {
            background: var(--coffee-gradient-reverse);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.4);
            color: white;
        }

        /* Form controls */
        .form-control {
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--coffee-primary);
            box-shadow: 0 0 0 0.2rem rgba(111, 78, 55, 0.25);
        }

        /* Table styling */
        .table {
            color: var(--coffee-text);
        }

        .table thead th {
            border-bottom: 2px solid var(--coffee-light);
            color: var(--coffee-primary);
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: var(--coffee-light);
        }

        /* DataTables styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--coffee-gradient) !important;
            border-color: var(--coffee-primary) !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--coffee-light) !important;
            border-color: var(--coffee-accent) !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ url('plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ url('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ url('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ url('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
    <script type="text/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{ url('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ url('dist/js/adminlte.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <div class="wrapper">

    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" id="sidebarToggle" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-2">
        <!-- Brand Logo -->
        <div class="brand-link">
            <a href="#" class="brand">
                <img src="{{ url('img/coffe.png') }}" alt="Logo" class="brand-logo">
                <div class="brand-text-container">
                    <span class="brand-text">ERP THINK</span>
                    <span class="brand-subtext">Sistem ERP Coffee</span>
                </div>
            </a>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
            </div>
        </div>


        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Menu area -->
            <div class="menu-area">
                <nav class="mt-1">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="/home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-header">Master</li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->is('master_suppliers*') || request()->is('finished_products*') || request()->is('sales*') || request()->is('master_jenis*') || request()->is('master_varietas*') || request()->is('master_origin*') || request()->is('master_grade*') || request()->is('master_barang*') || request()->is('machines*') || request()->is('master_sku*') || request()->is('sku*') || request()->is('master_penerimaan*') ? 'active' : '' }}"
                                data-toggle="collapse" data-target="#masterMenu">
                                <i class="fas fa-cogs"></i>
                                <p>Data Master <i class="fas fa-chevron-down float-right"></i></p>
                            </a>
                            <ul id="masterMenu"
                                class="collapse nav flex-column {{ request()->is('master_suppliers*') || request()->is('finished_products*') || request()->is('sales*') || request()->is('master_jenis*') || request()->is('master_varietas*') || request()->is('master_origin*') || request()->is('master_grade*') ? 'show' : '' }}">

                                <li class="nav-item">
                                    <a href="/master_suppliers"
                                        class="nav-link {{ request()->is('master_suppliers*') ? 'active' : '' }}">
                                        <i class="fas fa-box"></i>
                                        <p>Master Suppliers</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/finished_products"
                                        class="nav-link {{ request()->is('finished_products*') ? 'active' : '' }}">
                                        <i class="fas fa-coffee"></i>
                                        <p>Finished Products</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/sales" class="nav-link {{ request()->is('sales*') ? 'active' : '' }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        <p>Sales Records</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/master_jenis"
                                        class="nav-link {{ request()->is('master_jenis*') ? 'active' : '' }}">
                                        <i class="fas fa-tags"></i>
                                        <p>Master Type/Jenis</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/master_varietas"
                                        class="nav-link {{ request()->is('master_varietas*') ? 'active' : '' }}">
                                        <i class="fas fa-seedling"></i>
                                        <p>Master Varietas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/master_origin"
                                        class="nav-link {{ request()->is('master_origin*') ? 'active' : '' }}">
                                        <i class="fas fa-globe-americas"></i>
                                        <p>Master Origin</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/master_grade"
                                        class="nav-link {{ request()->is('master_grade*') ? 'active' : '' }}">
                                        <i class="fas fa-star"></i>
                                        <p>Master Grade</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/master_barang"
                                        class="nav-link {{ request()->is('master_barang*') ? 'active' : '' }}">
                                        <i class="fas fa-boxes"></i>
                                        <p>Master Barang</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/machines"
                                        class="nav-link {{ request()->is('machines*') ? 'active' : '' }}">
                                        <i class="fas fa-cogs"></i>
                                        <p>Machines</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/master_sku"
                                        class="nav-link {{ request()->is('master_sku*') ? 'active' : '' }}">
                                        <i class="fas fa-cubes"></i>
                                        <p>Stok Barang</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/sku"
                                        class="nav-link {{ request()->is('sku*') ? 'active' : '' }}">
                                        <i class="fas fa-tags"></i>
                                        <p>SKU Management</p>
                                    </a>
                                </li>
                                
                                <!-- Add Master Penerimaan Menu Item -->
                                <li class="nav-item">
                                    <a href="/master_penerimaan"
                                        class="nav-link {{ request()->is('master_penerimaan*') ? 'active' : '' }}">
                                        <i class="fas fa-truck-loading"></i>
                                        <p>Penerimaan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- Footer Section for Logout -->
            <div class="sidebar-footer">
                <a href="{{ route('actionLogout') }}" class="logout-button">
                    <i class="fas fa-power-off"></i> <span>Logout</span>
                </a>
            </div>
        </div>
        <!-- /.sidebar -->
    </aside>

    @yield('konten')

    <footer class="main-footer">
        <strong>Copyright &copy; 2025 </strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if this is the first load after login
            const isFirstLoad = localStorage.getItem('hasLoggedIn') !== 'true';

            if (isFirstLoad) {
                // Store that user has logged in
                localStorage.setItem('hasLoggedIn', 'true');

                // Get current path
                const currentPath = window.location.pathname;

                // If we're just logged in and not already on dashboard
                if (currentPath === '/' || currentPath === '/login') {
                    // Redirect to dashboard
                    window.location.href = '/home';
                } else {
                    // Force the dashboard link to be active regardless of current page
                    const dashboardLink = document.querySelector('a[href="/home"]');
                    if (dashboardLink) {
                        // Remove active class from any other link that might have it
                        document.querySelectorAll('.nav-link.active').forEach(function(el) {
                            el.classList.remove('active');
                        });

                        // Add active class to dashboard
                        dashboardLink.classList.add('active');
                    }
                }
            }

            // Handle sidebar toggle for logout button
            const sidebarToggleBtn = document.querySelector('[data-widget="pushmenu"]');
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function() {
                    // Add a small delay to match AdminLTE's sidebar animation
                    setTimeout(function() {
                        if (document.body.classList.contains('sidebar-collapse')) {
                            document.querySelector('.sidebar-footer').style.width = '4.6rem';
                        } else {
                            document.querySelector('.sidebar-footer').style.width = '250px';
                        }
                    }, 50);
                });
            }

            // Check initial state on page load
            if (document.body.classList.contains('sidebar-collapse')) {
                document.querySelector('.sidebar-footer').style.width = '4.6rem';
            }

            // Alternative approach: Force dashboard active on specific pages
            // This helps if the user refreshes the page after login
            (function() {
                const currentPath = window.location.pathname;
                // If we're on login page or root
                if (currentPath === '/' || currentPath === '/login') {
                    // Mark for redirect on next page load
                    sessionStorage.setItem('redirectToDashboard', 'true');
                }

                // Check if we need to redirect
                if (sessionStorage.getItem('redirectToDashboard') === 'true') {
                    // Clear the flag
                    sessionStorage.removeItem('redirectToDashboard');

                    // Force dashboard active
                    const dashboardLink = document.querySelector('a[href="/home"]');
                    if (dashboardLink) {
                        document.querySelectorAll('.nav-link.active').forEach(function(el) {
                            el.classList.remove('active');
                        });
                        dashboardLink.classList.add('active');
                    }
                }
            })();
        });

        
// Toggle sidebar and fullscreen layout
    document.getElementById('sidebarToggle').addEventListener('click', function(e) {
        e.preventDefault();
        const body = document.body;
        
        if (body.classList.contains('sidebar-collapse') || body.classList.contains('layout-top-nav')) {
            // Restore normal layout
            body.classList.remove('layout-top-nav');
            body.classList.remove('sidebar-collapse');
            body.classList.add('sidebar-mini', 'layout-fixed');
            localStorage.setItem('layoutState', 'normal');
        } else {
            // Minimize sidebar and enable fullscreen content
            body.classList.remove('sidebar-mini', 'layout-fixed');
            body.classList.add('layout-top-nav', 'sidebar-collapse');
            localStorage.setItem('layoutState', 'minimized');
        }
    });

    // Check saved layout state
    const savedState = localStorage.getItem('layoutState');
    if (savedState === 'minimized') {
        document.body.classList.add('layout-top-nav', 'sidebar-collapse');
        document.body.classList.remove('sidebar-mini', 'layout-fixed');
    }

    </script>

    @yield('script')

</body>

</html>