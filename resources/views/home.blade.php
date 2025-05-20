@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="coffee-header">
                            <div class="coffee-header-content">
                                <h1>Welcome to ERP THINK</h1>
                                <p>Your Coffee Management System</p>
                                <div class="coffee-date">
                                    <i class="fas fa-calendar-alt"></i> {{ now()->format('l, d F Y') }}
                                </div>
                            </div>
                            <div class="coffee-header-image"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Quick Stats -->
                <div class="row">
                    <div class="col-12">
                        <div class="stats-container">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-coffee"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="counter">{{ rand(10, 50) }}</h3>
                                    <p>Coffee Varieties</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="counter">{{ rand(100, 500) }}</h3>
                                    <p>Inventory Items</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Rp {{ number_format(rand(5000000, 20000000), 0, ',', '.') }}</h3>
                                    <p>Monthly Revenue</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="counter">{{ rand(5, 20) }}</h3>
                                    <p>Active Suppliers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard Sections -->
                <div class="row mt-4">
                    <!-- Left Column -->
                    <div class="col-lg-8">


                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-4">
                        <!-- User Profile Card -->
                        <div class="dashboard-card user-profile-card">
                            <div class="user-profile-header">
                                <div class="user-info-top">
                                    <h3>{{ Auth::user()->name }}</h3>
                                    <p class="user-role">{{ Auth::user()->role }}</p>
                                </div>
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                    <!-- Steam animation will be added by JS -->
                                </div>
                            </div>
                            
                            <!-- User Profile Card - Bottom Section -->
                            <div class="user-profile-body">
                                <div class="user-stats">
                                    <div class="user-stat-item">
                                        <span class="stat-value">{{ rand(5, 30) }}</span>
                                        <span class="stat-label">Tasks</span>
                                    </div>
                                    <div class="user-stat-item">
                                        <span class="stat-value">{{ rand(1, 10) }}</span>
                                        <span class="stat-label">Projects</span>
                                    </div>
                                    <div class="user-stat-item">
                                        <span class="stat-value">{{ rand(10, 100) }}</span>
                                        <span class="stat-label">Activities</span>
                                    </div>
                                </div>
                                <div class="user-actions">
                                    <a href="#" class="btn-user-action"><i class="fas fa-cog"></i> Settings</a>
                                    <a href="#" class="btn-user-action"><i class="fas fa-user-edit"></i> Profile</a>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Status -->
                        <div class="dashboard-card mt-4">
                            <div class="dashboard-card-header">
                                <h2><i class="fas fa-cubes"></i> Inventory Status</h2>
                                <div class="card-actions">
                                    <button class="btn-card-action"><i class="fas fa-sync-alt"></i></button>
                                </div>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="inventory-item">
                                    <div class="inventory-info">
                                        <div class="inventory-name">
                                            <h4>Arabica Aceh Gayo</h4>
                                            <span class="inventory-quantity">15 kg remaining</span>
                                        </div>
                                        <div class="inventory-percentage">75%</div>
                                    </div>
                                    <div class="inventory-progress">
                                        <div class="progress-bar" style="width: 75%; background-color: #28a745;"></div>
                                    </div>
                                </div>
                                <div class="inventory-item">
                                    <div class="inventory-info">
                                        <div class="inventory-name">
                                            <h4>Robusta Lampung</h4>
                                            <span class="inventory-quantity">9 kg remaining</span>
                                        </div>
                                        <div class="inventory-percentage">45%</div>
                                    </div>
                                    <div class="inventory-progress">
                                        <div class="progress-bar" style="width: 45%; background-color: #17a2b8;"></div>
                                    </div>
                                </div>
                                <div class="inventory-item">
                                    <div class="inventory-info">
                                        <div class="inventory-name">
                                            <h4>Sumatra Mandheling</h4>
                                            <span class="inventory-quantity">4 kg remaining</span>
                                        </div>
                                        <div class="inventory-percentage">20%</div>
                                    </div>
                                    <div class="inventory-progress">
                                        <div class="progress-bar" style="width: 20%; background-color: #ffc107;"></div>
                                    </div>
                                </div>
                                <div class="inventory-item">
                                    <div class="inventory-info">
                                        <div class="inventory-name">
                                            <h4>Java Preanger</h4>
                                            <span class="inventory-quantity">2 kg remaining</span>
                                        </div>
                                        <div class="inventory-percentage">10%</div>
                                    </div>
                                    <div class="inventory-progress">
                                        <div class="progress-bar" style="width: 10%; background-color: #dc3545;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-card-footer">
                                <a href="#" class="btn-view-all">Manage Inventory <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="dashboard-card mt-4">
                            <div class="dashboard-card-header">
                                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="quick-actions">
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>New Order</span>
                                    </a>
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-truck"></i>
                                        <span>Deliveries</span>
                                    </a>
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-file-invoice"></i>
                                        <span>Invoices</span>
                                    </a>
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-chart-bar"></i>
                                        <span>Reports</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        /* Base Styles */
        :root {
            --coffee-dark: #3A2618;
            --coffee-medium: #6F4E37;
            --coffee-light: #A67C52;
            --coffee-cream: #D2B48C;
            --coffee-bg: #F5F1E9;
            --coffee-accent: #C87941;
            --text-dark: #2D2926;
            --text-medium: #5F5F5F;
            --text-light: #8E8E8E;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --primary: #007bff;
            --border-radius: 12px;
            --card-shadow: 0 8px 24px rgba(149, 157, 165, 0.1);
            --transition-speed: 0.3s;
        }

        .content-wrapper {
            background-color: var(--coffee-bg);
            background-image: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path fill="%236F4E37" fill-opacity="0.03" d="M50 50m-40 0a40 40 0 1 0 80 0a40 40 0 1 0 -80 0"/></svg>');
            background-size: 300px 300px;
            padding: 1.5rem;
            min-height: 100vh;
        }

        /* Header Styles */
        .coffee-header {
            display: flex;
            background: linear-gradient(135deg, var(--coffee-dark) 0%, var(--coffee-medium) 100%);
            border-radius: var(--border-radius);
            overflow: hidden;
            position: relative;
            height: 180px;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .coffee-header-content {
            padding: 2rem;
            color: white;
            width: 60%;
            position: relative;
            z-index: 2;
        }

        .coffee-header-content h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .coffee-header-content p {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 1.5rem;
        }

        .coffee-date {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-size: 0.9rem;
        }

        .coffee-header-image {
            position: absolute;
            right: 0;
            top: 0;
            width: 50%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><path fill="%23ffffff" fill-opacity="0.1" d="M100 100m-80 0a80 80 0 1 0 160 0a80 80 0 1 0 -160 0"/></svg>'), url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path fill="%23ffffff" fill-opacity="0.05" d="M20 50 Q 40 20, 50 50 T 80 50 Q 100 80, 120 50 T 180 50"/></svg>');
            background-size: 300px 300px, 400px 400px;
            background-position: right -100px top -50px, right -200px bottom -100px;
            background-repeat: no-repeat;
        }

        /* Stats Container */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: var(--card-shadow);
            transition: transform var(--transition-speed);
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }

        .stat-info p {
            color: var(--text-light);
            margin: 0;
            font-size: 0.9rem;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .dashboard-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--coffee-dark);
            display: flex;
            align-items: center;
        }

        .dashboard-card-header h2 i {
            margin-right: 0.75rem;
            color: var(--coffee-medium);
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-card-action {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.03);
            border: none;
            color: var(--text-medium);
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .btn-card-action:hover {
            background: var(--coffee-medium);
            color: white;
        }

        .dashboard-card-body {
            padding: 1.5rem;
        }

        .dashboard-card-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .btn-view-all {
            color: var(--coffee-medium);
            font-weight: 500;
            text-decoration: none;
            transition: all var(--transition-speed);
            display: inline-block;
        }

        .btn-view-all:hover {
            color: var(--coffee-dark);
            text-decoration: none;
        }

        .btn-view-all i {
            margin-left: 0.5rem;
            transition: transform var(--transition-speed);
        }

        .btn-view-all:hover i {
            transform: translateX(3px);
        }

        /* Production Timeline */
        .production-timeline {
            position: relative;
            padding-left: 30px;
        }

        .production-timeline::before {
            content: '';
            position: absolute;
            left: 9px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-point {
            position: absolute;
            left: -30px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .timeline-point.completed {
            background-color: var(--success);
        }

        .timeline-point.in-progress {
            background-color: var(--primary);
        }

        .timeline-point.pending {
            background-color: var(--warning);
        }

        .timeline-content {
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            padding: 1rem;
        }

        .timeline-content h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }

        .timeline-content p {
            color: var(--text-medium);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .timeline-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* Transactions Table */
        .transactions-table-wrapper {
            overflow-x: auto;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th {
            text-align: left;
            padding: 0.75rem;
            font-weight: 600;
            color: var(--text-medium);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.85rem;
        }

        .transactions-table td {
            padding: 0.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .transactions-table tr:last-child td {
            border-bottom: none;
        }

        .transactions-table tr:hover td {
            background-color: rgba(0, 0, 0, 0.01);
        }

        /* User Profile Card Styles */
        .user-profile-card {
            overflow: visible;
            margin-bottom: 1.5rem;
        }

        /* Top Section */
        .user-profile-header {
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            padding: 2rem 1.5rem;
            color: white;
            text-align: center;
            position: relative;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .user-info-top {
            margin-bottom: 1.5rem;
        }

        .user-info-top h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
        }

        .user-role {
            font-size: 1rem;
            opacity: 0.8;
            margin: 0;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--coffee-medium);
            border: 4px solid rgba(255, 255, 255, 0.2);
            margin: 0 auto;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Bottom Section */
        .user-profile-body {
            padding: 1.5rem;
            background: white;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        .user-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .user-stat-item {
            text-align: center;
        }

        .user-stat-item .stat-value {
            display: block;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 0.25rem;
        }

        .user-stat-item .stat-label {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .user-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-user-action {
            flex: 1;
            padding: 0.875rem;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            color: var(--text-medium);
            text-decoration: none;
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-user-action:hover {
            background-color: var(--coffee-medium);
            color: white;
            transform: translateY(-2px);
        }

        .btn-user-action i {
            font-size: 1.1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .user-profile-header {
                padding: 1.5rem 1rem;
            }

            .user-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .user-info-top h3 {
                font-size: 1.25rem;
            }

            .user-stat-item .stat-value {
                font-size: 1.5rem;
            }
        }

        /* Inventory Status */
        .inventory-item {
            margin-bottom: 1.25rem;
        }

        .inventory-item:last-child {
            margin-bottom: 0;
        }

        .inventory-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 0.5rem;
        }

        .inventory-name h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }

        .inventory-quantity {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .inventory-percentage {
            font-weight: 700;
            color: var(--text-dark);
        }

        .inventory-progress {
            height: 8px;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
            overflow: hidden;
        }

        .inventory-progress .progress-bar {
            height: 100%;
            border-radius: 4px;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            color: var(--text-dark);
            text-decoration: none;
            transition: all var(--transition-speed);
        }

        .quick-action-btn:hover {
            background-color: var(--coffee-medium);
            color: white;
            text-decoration: none;
        }

        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            color: var(--coffee-medium);
            transition: all var(--transition-speed);
        }

        .quick-action-btn:hover i {
            color: white;
            transform: translateY(-3px);
        }

        .quick-action-btn span {
            font-weight: 500;
        }

        /* Badges */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 600;
            border-radius: 30px;
        }

        .badge-success {
            background-color: var(--success);
            color: white;
        }

        .badge-primary {
            background-color: var(--primary);
            color: white;
        }

        .badge-warning {
            background-color: var(--warning);
            color: #212529;
        }

        .badge-info {
            background-color: var(--info);
            color: white;
        }

        .badge-danger {
            background-color: var(--danger);
            color: white;
        }

        .badge-soft-success, 
        .badge-soft-info, 
        .badge-soft-warning, 
        .badge-soft-danger {
            font-size: 0.75rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 1199.98px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 767.98px) {
            .coffee-header {
                height: auto;
                flex-direction: column;
            }

            .coffee-header-content {
                width: 100%;
                padding: 1.5rem;
            }

            .coffee-header-image {
                display: none;
            }

            .stats-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .user-profile-header {
                padding: 1rem;
            }

            .user-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .timeline-content h4 {
                font-size: 0.9rem;
            }

            .timeline-content p {
                font-size: 0.8rem;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-card {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .dashboard-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .dashboard-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .stat-item {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .stat-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        /* Coffee cup animation */
        .coffee-steam {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 30px;
            opacity: 0;
            animation: steam 3s ease-out infinite;
        }

        @keyframes steam {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(0);
            }
            20% {
                opacity: 0.5;
            }
            80% {
                opacity: 0.2;
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate counters
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.innerText.replace(/[^\d]/g, ''));
                const duration = 1500;
                const step = Math.max(1, Math.floor(target / (duration / 30)));
                let current = 0;
                
                const updateCounter = () => {
                    current += step;
                    if (current > target) current = target;
                    
                    // Check if the counter contains "types" or other text
                    if (counter.innerText.includes('types')) {
                        counter.innerText = current + ' types';
                    } else {
                        counter.innerText = current;
                    }
                    
                    if (current < target) {
                        setTimeout(updateCounter, 30);
                    }
                };
                
                // Set initial value
                if (counter.innerText.includes('types')) {
                    counter.innerText = '0 types';
                } else {
                    counter.innerText = '0';
                }
                
                setTimeout(updateCounter, 300);
            });

            // Add hover effects to timeline items
            const timelineItems = document.querySelectorAll('.timeline-item');
            timelineItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.querySelector('.timeline-content').style.transform = 'translateX(5px)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.querySelector('.timeline-content').style.transform = 'translateX(0)';
                });
            });

            // Add coffee steam animation to user avatar
            const userAvatar = document.querySelector('.user-avatar');
            if (userAvatar) {
                const steamSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                steamSvg.setAttribute('class', 'coffee-steam');
                steamSvg.setAttribute('viewBox', '0 0 60 30');
                
                const path1 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path1.setAttribute('d', 'M10 25 Q 18 10, 20 25 T 30 25');
                path1.setAttribute('fill', 'none');
                path1.setAttribute('stroke', 'rgba(255,255,255,0.5)');
                path1.setAttribute('stroke-width', '2');
                
                const path2 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path2.setAttribute('d', 'M30 25 Q 38 5, 40 25 T 50 25');
                path2.setAttribute('fill', 'none');
                path2.setAttribute('stroke', 'rgba(255,255,255,0.5)');
                path2.setAttribute('stroke-width', '2');
                
                steamSvg.appendChild(path1);
                steamSvg.appendChild(path2);
                
                userAvatar.style.position = 'relative';
                userAvatar.appendChild(steamSvg);
            }

            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('.btn-user-action, .btn-view-all, .quick-action-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const ripple = document.createElement('span');
                    ripple.style.position = 'absolute';
                    ripple.style.width = '1px';
                    ripple.style.height = '1px';
                    ripple.style.borderRadius = '50%';
                    ripple.style.backgroundColor = 'rgba(255,255,255,0.7)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.animation = 'ripple 0.6s linear';
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add keyframe for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(100);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
@endsection
