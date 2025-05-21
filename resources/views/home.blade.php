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
                                    <h3 class="counter">{{ Schema::hasTable('varietas') ? DB::table('varietas')->count() : 0 }}</h3>
                                    <p>Coffee Varieties</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="counter">{{ Schema::hasTable('finished_products') ? DB::table('finished_products')->count() : 0 }}</h3>
                                    <p>Finished Products</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Rp {{ Schema::hasTable('sales') ? number_format(DB::table('sales')->sum('total_price'), 0, ',', '.') : '0' }}</h3>
                                    <p>Total Revenue</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="counter">{{ Schema::hasTable('suppliers') ? DB::table('suppliers')->count() : 0 }}</h3>
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
                        <!-- Recent Products -->
                        <div class="dashboard-card mb-4 fade-in-card">
                            <div class="dashboard-card-header">
                                <h2><i class="fas fa-coffee"></i> Recent Products</h2>
                                <div class="card-actions">
                                    <button class="btn-card-action"><i class="fas fa-sync-alt"></i></button>
                                    <button class="btn-card-action"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="production-timeline">
                                    @php
                                        $products = [];
                                        if (Schema::hasTable('finished_products')) {
                                            $query = DB::table('finished_products');
                                            
                                            // Join with jenis table if exists and has the required columns
                                            if (Schema::hasTable('jenis') && 
                                                Schema::hasColumn('finished_products', 'jenis_id')) {
                                                $query->leftJoin('jenis', 'finished_products.jenis_id', '=', 'jenis.id_jenis');
                                                $select[] = 'jenis.nama_jenis as jenis'; // Change deskripsi to nama_jenis
                                            }
                                            
                                            // Join with varietas table if exists
                                            if (Schema::hasTable('varietas') && 
                                                Schema::hasColumn('finished_products', 'varietas_id')) {
                                                $query->leftJoin('varietas', 'finished_products.varietas_id', '=', 'varietas.id_varietas');
                                                $select[] = 'varietas.nama_varietas as varietas'; // Change deskripsi to nama_varietas
                                            }
                                            
                                            // Join with origin table if exists
                                            if (Schema::hasTable('origin') && 
                                                Schema::hasColumn('finished_products', 'origin_id')) {
                                                $query->leftJoin('origin', 'finished_products.origin_id', '=', 'origin.id_origin');
                                                $select[] = 'origin.nama_origin as origin'; // Change deskripsi to nama_origin
                                            }
                                            
                                            // Join with grade table if exists
                                            if (Schema::hasTable('grade') && 
                                                Schema::hasColumn('finished_products', 'grade_id')) {
                                                $query->leftJoin('grade', 'finished_products.grade_id', '=', 'grade.id_grade');
                                                $select[] = 'grade.nama_grade as grade'; // Change deskripsi to nama_grade
                                            }
                                            
                                            try {
                                                $products = $query->select(array_merge(['finished_products.*'], $select ?? []))
                                                    ->orderBy('finished_products.id', 'desc')
                                                    ->limit(4)
                                                    ->get();
                                            } catch (\Exception $e) {
                                                $products = collect([]); // Return empty collection on error
                                            }
                                        }
                                    @endphp

                                    <!-- Display Products -->
                                    @if(count($products) > 0)
                                        @foreach($products as $product)
                                            <div class="timeline-item animate-timeline">
                                                <div class="timeline-point {{ isset($product->stock_status) && $product->stock_status == 'ready' ? 'completed' : (isset($product->stock_status) && $product->stock_status == 'reserved' ? 'in-progress' : 'pending') }}"></div>
                                                <div class="timeline-content">
                                                    <h4>
                                                        {{ $product->varietas ?? 'Unknown Variety' }} 
                                                        {{ isset($product->origin) ? '- ' . $product->origin : '' }}
                                                    </h4>
                                                    <p>
                                                        {{ $product->jenis ?? 'Coffee Product' }} | 
                                                        {{ isset($product->grade) ? 'Grade: ' . $product->grade : '' }}
                                                    </p>
                                                    <div class="timeline-meta">
                                                        <span>Weight: {{ $product->weight_final ?? 0 }} kg</span>
                                                        <span>Price: Rp {{ number_format($product->harga_jual ?? 0, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="empty-state">
                                            <i class="fas fa-coffee"></i>
                                            <p>No products available yet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="dashboard-card-footer">
                                <a href="{{ route('finished_products.index') }}" class="btn-view-all">View All Products <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <!-- Recent Sales -->
                        <div class="dashboard-card mb-4 fade-in-card">
                            <div class="dashboard-card-header">
                                <h2><i class="fas fa-shopping-cart"></i> Recent Sales</h2>
                                <div class="card-actions">
                                    <button class="btn-card-action"><i class="fas fa-sync-alt"></i></button>
                                    <button class="btn-card-action"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="transactions-table-wrapper">
                                    <table class="transactions-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sales = [];
                                                if (Schema::hasTable('sales')) {
                                                    $query = DB::table('sales');
                                                    
                                                    if (Schema::hasTable('finished_products') && 
                                                        Schema::hasColumn('sales', 'finished_product_id')) {
                                                        $query->leftJoin('finished_products', 'sales.finished_product_id', '=', 'finished_products.id');
                                                    }
                                                    
                                                    if (Schema::hasTable('varietas') && 
                                                        Schema::hasColumn('finished_products', 'varietas_id')) {
                                                        $query->leftJoin('varietas', 'finished_products.varietas_id', '=', 'varietas.id_varietas');
                                                        // Change deskripsi to nama_varietas
                                                        $select[] = 'varietas.nama_varietas as product_name';
                                                    }
                                                    
                                                    try {
                                                        $sales = $query->select(array_merge(['sales.*'], $select ?? []))
                                                            ->orderBy('sales.id', 'desc')
                                                            ->limit(5)
                                                            ->get();
                                                    } catch (\Exception $e) {
                                                        $sales = collect([]); // Return empty collection on error
                                                    }
                                                }
                                            @endphp
                                            
                                            @if(count($sales) > 0)
                                                @foreach($sales as $index => $sale)
                                                    <tr class="animate-table-row" style="animation-delay: {{ $index * 0.1 }}s">
                                                        <td>{{ isset($sale->sale_date) ? date('d M Y', strtotime($sale->sale_date)) : '-' }}</td>
                                                        <td>{{ $sale->product_name ?? 'Product #' . $sale->finished_product_id }}</td>
                                                        <td>{{ $sale->qty_sold ?? 0 }}</td>
                                                        <td>Rp {{ number_format($sale->total_price ?? 0, 0, ',', '.') }}</td>
                                                        <td><span class="badge badge-soft-success">Completed</span></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No sales records available</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="dashboard-card-footer">
                                <a href="{{ route('sales.index') }}" class="btn-view-all">View All Sales <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
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
                                        <span class="stat-value">{{ Schema::hasTable('finished_products') ? DB::table('finished_products')->count() : 0 }}</span>
                                        <span class="stat-label">Products</span>
                                    </div>
                                    <div class="user-stat-item">
                                        <span class="stat-value">{{ Schema::hasTable('sales') ? DB::table('sales')->count() : 0 }}</span>
                                        <span class="stat-label">Sales</span>
                                    </div>
                                    <div class="user-stat-item">
                                        <span class="stat-value">{{ Schema::hasTable('suppliers') ? DB::table('suppliers')->count() : 0 }}</span>
                                        <span class="stat-label">Suppliers</span>
                                    </div>
                                </div>
                                <div class="user-actions">
                                    <a href="#" class="btn-user-action"><i class="fas fa-cog"></i> Settings</a>
                                    <a href="#" class="btn-user-action"><i class="fas fa-user-edit"></i> Profile</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions Card -->
                        <div class="dashboard-card mb-4 fade-in-card">
                            <div class="dashboard-card-header">
                                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="quick-actions">
                                    <a href="{{ route('finished_products.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-box"></i>
                                        <span>Products</span>
                                    </a>
                                    <a href="{{ route('sales.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Sales</span>
                                    </a>
                                    <a href="{{ route('master_suppliers.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-truck"></i>
                                        <span>Suppliers</span>
                                    </a>
                                    <a href="{{ route('master_varietas.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-leaf"></i>
                                        <span>Varieties</span>
                                    </a>
                                    <a href="{{ route('master_origin.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Origins</span>
                                    </a>
                                    <a href="{{ route('master_grade.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-star"></i>
                                        <span>Grades</span>
                                    </a>
                                    <a href="{{ route('master_jenis.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-tags"></i>
                                        <span>Jenis</span>
                                    </a>
                                    <a href="{{ route('master_barang.index') }}" class="quick-action-btn pulse-on-hover">
                                        <i class="fas fa-boxes"></i>
                                        <span>Barang</span>
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
        
        /* Grade Distribution */
        .grade-distribution {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .grade-item {
            margin-bottom: 0.5rem;
        }

        .grade-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .grade-name {
            font-weight: 600;
            color: var(--text-dark);
        }

        .grade-count {
            color: var(--text-medium);
        }

        .grade-progress-container {
            height: 8px;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
            overflow: hidden;
        }

        .grade-progress {
            height: 100%;
            background: linear-gradient(90deg, var(--coffee-medium), var(--coffee-light));
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }

        /* Animation for progress bars */
        .animate-progress {
            opacity: 0;
            animation: fadeInRight 0.6s ease-out forwards;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .content-wrapper {
            background-color: var(--coffee-bg);
            background-image: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path fill="%236F4E37" fill-opacity="0.03" d="M50 50m-40 0a40 40 0 1 0 80 0a40 40 0 1 0 -80 0"/></svg>');
            background-size: 300px 300px;
            padding: 1.5rem;
            min-height: 100vh;
        }

        /* Animation Classes */
        .fade-in-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .fade-in-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .fade-in-card:nth-child(3) {
            animation-delay: 0.4s;
        }

        .animate-timeline {
            opacity: 0;
            transform: translateX(-20px);
            animation: slideInRight 0.5s ease-out forwards;
        }

        .animate-timeline:nth-child(1) {
            animation-delay: 0.1s;
        }

        .animate-timeline:nth-child(2) {
            animation-delay: 0.2s;
        }

        .animate-timeline:nth-child(3) {
            animation-delay: 0.3s;
        }

        .animate-timeline:nth-child(4) {
            animation-delay: 0.4s;
        }

        .animate-table-row {
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .pulse-on-hover {
            transition: all 0.3s ease;
        }

        .pulse-on-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: var(--text-light);
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--coffee-light);
            opacity: 0.5;
        }

        /* Animation Keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulseEffect {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
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
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            padding: 0.5rem;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1rem;
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            color: var(--coffee-medium);
            transition: all 0.3s ease;
        }

        .quick-action-btn span {
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
        }

        .quick-action-btn:hover {
            background-color: var(--coffee-medium);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-decoration: none;
        }

        .quick-action-btn:hover i {
            color: white;
            transform: scale(1.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Pulse Animation */
        .pulse-on-hover {
            position: relative;
            overflow: hidden;
        }

        .pulse-on-hover::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease-out, height 0.3s ease-out;
        }

        .pulse-on-hover:hover::after {
            width: 120%;
            height: 120%;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Animation for pulse effect */
        .pulse-on-hover {
            position: relative;
            overflow: hidden;
        }

        .pulse-on-hover::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease-out, height 0.3s ease-out;
        }

        .pulse-on-hover:hover::after {
            width: 120%;
            height: 120%;
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
            // Add hover effect to timeline items
            const timelineItems = document.querySelectorAll('.timeline-item');
            timelineItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.querySelector('.timeline-content').style.transform = 'translateX(5px)';
                    this.querySelector('.timeline-content').style.transition = 'transform 0.3s ease';
                    this.querySelector('.timeline-content').style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.querySelector('.timeline-content').style.transform = 'translateX(0)';
                    this.querySelector('.timeline-content').style.boxShadow = 'none';
                });
            });

            // Add hover effects to quick action buttons
            const quickActions = document.querySelectorAll('.quick-action-btn');
            quickActions.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.querySelector('i').style.animation = 'pulseEffect 1s infinite';
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.querySelector('i').style.animation = 'none';
                });
            });

            // Animate grade progress bars on scroll
            const animateOnScroll = () => {
                const gradeItems = document.querySelectorAll('.grade-item');
                gradeItems.forEach(item => {
                    const position = item.getBoundingClientRect();
                    if(position.top < window.innerHeight) {
                        const progressBar = item.querySelector('.grade-progress');
                        if(progressBar && !progressBar.classList.contains('animated')) {
                            progressBar.classList.add('animated');
                            const width = progressBar.style.width;
                            progressBar.style.width = '0';
                            setTimeout(() => {
                                progressBar.style.width = width;
                            }, 100);
                        }
                    }
                });
            };

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
            window.addEventListener('scroll', animateOnScroll);
            // Trigger once on load
            setTimeout(animateOnScroll, 500);
        });
    </script>
@endsection
