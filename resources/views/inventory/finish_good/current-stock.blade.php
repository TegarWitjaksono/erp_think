@extends('layouts.app')

@section('title', 'Laporan Inventory Finish Goods')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Inventory Finish Goods</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('inventory-finish-goods.report') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date">Tanggal Mulai:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date">Tanggal Akhir:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('inventory-finish-goods.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-success">Export Excel</a>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6>Total Transaksi</h6>
                                    <h4>{{ number_format($summary->total_transaksi) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Total Masuk</h6>
                                    <h4>{{ number_format($summary->total_masuk, 2) }} kg</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h6>Total Keluar</h6>
                                    <h4>{{ number_format($summary->total_keluar, 2) }} kg</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>Saldo Akhir</h6>
                                    <h4>{{ number_format($summary->saldo_akhir, 2) }} kg</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6>Total Batch</h6>
                                    <h4>{{ number_format($summary->total_batch) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h6>Jenis Produk</h6>
                                    <h4>{{ number_format($summary->total_jenis) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Alert -->
                    @if($summary->expired_items > 0 || $summary->expiring_soon > 0)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> Peringatan Expiry</h5>
                                <div class="row">
                                    @if($summary->expired_items > 0)
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Sudah Expired:</strong></p>
                                        <p>{{ $summary->expired_items }} item dengan stok {{ number_format($summary->expired_stock, 2) }} kg</p>
                                    </div>
                                    @endif
                                    @if($summary->expiring_soon > 0)
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Akan Expired (7 hari ke depan):</strong></p>
                                        <p>{{ $summary->expiring_soon }} item dengan stok {{ number_format($summary->expiring_soon_stock, 2) }} kg</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Charts Row 1 -->
                    <div class="row mb-4">
                        <!-- Stock Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Grafik Stok Harian</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="stockChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Grafik Transaksi Masuk vs Keluar</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="transactionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row 2 -->
                    <div class="row mb-4">
                        <!-- Jenis Distribution -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Distribusi per Jenis Produk</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="jenisChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Expiry Analysis -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Analisis Status Expiry</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="expiryChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Batch Production Chart -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Top 10 Produksi per Batch</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="batchChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Detail Transaksi Finish Goods</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="finishGoodsTable">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Batch Production</th>
                                            <th>Jenis</th>
                                            <th>Post Roast Blend</th>
                                            <th>ID Penjualan</th>
                                            <th>Expired Date</th>
                                            <th>Qty Masuk</th>
                                            <th>Qty Keluar</th>
                                            <th>Saldo</th>
                                            <th>Status</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportData as $item)
                                        <tr class="{{ 
                                            $item->expired_date && $item->expired_date <= date('Y-m-d') ? 'table-danger' : 
                                            ($item->expired_date && $item->expired_date <= date('Y-m-d', strtotime('+7 days')) ? 'table-warning' : '') 
                                        }}">
                                            <td>{{ date('d/m/Y H:i', strtotime($item->timestamp)) }}</td>
                                            <td><strong>{{ $item->Id_batch_production }}</strong></td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $item->jenis == 'PreRoastBlend' ? 'primary' : 
                                                    ($item->jenis == 'PostRoast' ? 'success' : 'info') 
                                                }}">
                                                    {{ $item->jenis }}
                                                </span>
                                            </td>
                                            <td>{{ $item->Id_postRoastblend ?? '-' }}</td>
                                            <td>{{ $item->Id_penjualan ?? '-' }}</td>
                                            <td>
                                                @if($item->expired_date)
                                                    <span class="badge badge-{{ 
                                                        $item->expired_date <= date('Y-m-d') ? 'danger' : 
                                                        ($item->expired_date <= date('Y-m-d', strtotime('+7 days')) ? 'warning' : 'success') 
                                                    }}">
                                                        {{ date('d/m/Y', strtotime($item->expired_date)) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-success">{{ number_format($item->jml_masuk, 2) }}</td>
                                            <td class="text-danger">{{ number_format($item->jml_keluar, 2) }}</td>
                                            <td class="font-weight-bold">{{ number_format($item->saldo, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $item->production_status == 'closing' ? 'success' : 
                                                    ($item->production_status == 'on process' ? 'warning' : 
                                                    ($item->production_status == 'cancel' ? 'danger' : 'secondary')) 
                                                }}">
                                                    {{ ucfirst($item->production_status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>{{ $item->Catatan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#finishGoodsTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "desc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        }
    });

    // Chart Data
    const chartData = @json($chartData);

    // Stock Chart
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'line',
        data: {
            labels: chartData.dailyStock.map(item => item.tanggal),
            datasets: [{
                label: 'Total Stock (kg)',
                data: chartData.dailyStock.map(item => parseFloat(item.total_stock)),
                borderColor: 'rgb',
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.2)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Transaction Chart
    const transactionCtx = document.getElementById('transactionChart').getContext('2d');
    new Chart(transactionCtx, {
        type: 'bar',
        data: {
            labels: chartData.dailyTransactions.map(item => item.tanggal),
            datasets: [
                {
                    label: 'Masuk (kg)',
                    data: chartData.dailyTransactions.map(item => parseFloat(item.total_masuk)),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                    label: 'Keluar (kg)',
                    data: chartData.dailyTransactions.map(item => parseFloat(item.total_keluar)),
                    backgroundColor: 'rgba(255, 99, 132, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Jenis Chart
    const jenisCtx = document.getElementById('jenisChart').getContext('2d');
    new Chart(jenisCtx, {
        type: 'pie',
        data: {
            labels: chartData.jenisDistribution.map(item => item.jenis),
            datasets: [{
                label: 'Distribusi Jenis',
                data: chartData.jenisDistribution.map(item => item.total_stock),
                backgroundColor: ['#36a2eb', '#4bc0c0', '#ff6384']
            }]
        }
    });

    // Expiry Chart
    const expiryCtx = document.getElementById('expiryChart').getContext('2d');
    new Chart(expiryCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.expiryAnalysis.map(item => item.status_expiry),
            datasets: [{
                label: 'Status Expiry',
                data: chartData.expiryAnalysis.map(item => item.total_stock),
                backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#28a745', '#6c757d']
            }]
        }
    });

    // Batch Chart
    const batchCtx = document.getElementById('batchChart').getContext('2d');
    new Chart(batchCtx, {
        type: 'horizontalBar',
        data: {
            labels: chartData.batchProduction.map(item => item.Id_batch_production),
            datasets: [
                {
                    label: 'Produksi (kg)',
                    data: chartData.batchProduction.map(item => item.total_produksi),
                    backgroundColor: '#007bff'
                },
                {
                    label: 'Keluar (kg)',
                    data: chartData.batchProduction.map(item => item.total_keluar),
                    backgroundColor: '#dc3545'
                },
                {
                    label: 'Sisa Stock (kg)',
                    data: chartData.batchProduction.map(item => item.sisa_stock),
                    backgroundColor: '#28a745'
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { position: 'right' },
                title: {
                    display: true,
                    text: 'Top 10 Produksi Batch'
                }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

});
</script>
@endsection
