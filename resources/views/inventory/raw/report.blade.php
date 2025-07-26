@extends('dashboard')

@section('konten')
    <div class="content-wrapper">

        {{-- Header --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6 d-flex">
                        <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                            <i class="fas fa-clipboard-list fa-2x" style="color: #79523B;"></i>
                        </div>
                        <div>
                            <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Laporan Inventory</h1>
                            <div style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;"></div>
                            <p class="text-muted mt-2 mb-0">Rekapitulasi transaksi bahan baku</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        <div class="container-fluid mb-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Filter Card --}}
        <div class="container-fluid">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light text-dark">
                    <h5 class="mb-0">Filter Laporan</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('inventory.report') }}">
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
                                <a href="{{ route('inventory.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-success">Export Excel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <h6>Total Transaksi</h6>
                            <h3>{{ number_format($summary->total_transaksi) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <h6>Total Masuk</h6>
                            <h3>{{ number_format($summary->total_masuk, 2) }} kg</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white shadow-sm">
                        <div class="card-body">
                            <h6>Total Keluar</h6>
                            <h3>{{ number_format($summary->total_keluar, 2) }} kg</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white shadow-sm">
                        <div class="card-body">
                            <h6>Saldo Akhir</h6>
                            <h3>{{ number_format($summary->saldo_akhir, 2) }} kg</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light text-dark">Grafik Stok Harian</div>
                        <div class="card-body">
                            <canvas id="stockChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light text-dark">Grafik Transaksi Masuk vs Keluar</div>
                        <div class="card-body">
                            <canvas id="transactionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light text-dark">Grafik Kadar Air Rata-rata</div>
                        <div class="card-body">
                            <canvas id="moistureChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light text-dark">Grafik Bulk Density Rata-rata</div>
                        <div class="card-body">
                            <canvas id="bulkDensityChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Ringkasan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light text-dark">Ringkasan Statistik</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr><td><strong>Kadar Air Rata-rata:</strong></td><td>{{ number_format($summary->avg_kadar_air, 2) }}%</td></tr>
                                <tr><td><strong>Kadar Air Minimum:</strong></td><td>{{ number_format($summary->min_kadar_air, 2) }}%</td></tr>
                                <tr><td><strong>Kadar Air Maksimum:</strong></td><td>{{ number_format($summary->max_kadar_air, 2) }}%</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr><td><strong>Bulk Density Rata-rata:</strong></td><td>{{ number_format($summary->avg_bulk_density, 3) }} kg/L</td></tr>
                                <tr><td><strong>Bulk Density Minimum:</strong></td><td>{{ number_format($summary->min_bulk_density, 3) }} kg/L</td></tr>
                                <tr><td><strong>Bulk Density Maksimum:</strong></td><td>{{ number_format($summary->max_bulk_density, 3) }} kg/L</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Data --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light text-dark">Detail Transaksi Inventory</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm" id="inventoryTable">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Batch MP</th>
                                    <th>Batch Detail</th>
                                    <th>Keterangan</th>
                                    <th>Kadar Air (%)</th>
                                    <th>Bulk Density</th>
                                    <th>Qty Masuk</th>
                                    <th>Qty Keluar</th>
                                    <th>Saldo</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $item)
                                <tr>
                                    <td>{{ date('d/m/Y H:i', strtotime($item->timestamp)) }}</td>
                                    <td>{{ $item->id_batch_mp }}</td>
                                    <td>{{ $item->id_batch }}</td>
                                    <td>{{ $item->penerimaan_keterangan }}</td>
                                    <td>{{ number_format($item->kadar_air, 2) }}</td>
                                    <td>{{ number_format($item->bulk_densitas, 3) }}</td>
                                    <td class="text-success">{{ number_format($item->credit_qty, 2) }}</td>
                                    <td class="text-danger">{{ number_format($item->debit_qty, 2) }}</td>
                                    <td class="font-weight-bold">{{ number_format($item->saldo_qty, 2) }}</td>
                                    <td title="{{ $item->catatan }}">{{ Str::limit($item->catatan, 25) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    $('#inventoryTable').DataTable({
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
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Transaction Chart
    const transactionCtx = document.getElementById('transactionChart').getContext('2d');
    new Chart(transactionCtx, {
        type: 'bar',
        data: {
            labels: chartData.dailyTransactions.map(item => item.tanggal),
            datasets: [{
                label: 'Qty Masuk (kg)',
                data: chartData.dailyTransactions.map(item => parseFloat(item.total_masuk)),
                backgroundColor: 'rgba(54, 162, 235, 0.8)'
            }, {
                label: 'Qty Keluar (kg)',
                data: chartData.dailyTransactions.map(item => parseFloat(item.total_keluar)),
                backgroundColor: 'rgba(255, 99, 132, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Moisture Chart
    const moistureCtx = document.getElementById('moistureChart').getContext('2d');
    new Chart(moistureCtx, {
        type: 'line',
        data: {
            labels: chartData.dailyMoisture.map(item => item.tanggal),
            datasets: [{
                label: 'Kadar Air Rata-rata (%)',
                data: chartData.dailyMoisture.map(item => parseFloat(item.avg_kadar_air)),
                borderColor: 'rgb(255, 159, 64)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Bulk Density Chart
    const bulkDensityCtx = document.getElementById('bulkDensityChart').getContext('2d');
    new Chart(bulkDensityCtx, {
        type: 'line',
        data: {
            labels: chartData.dailyBulkDensity.map(item => item.tanggal),
            datasets: [{
                label: 'Bulk Density Rata-rata (kg/L)',
                data: chartData.dailyBulkDensity.map(item => parseFloat(item.avg_bulk_density)),
                borderColor: 'rgb(153, 102, 255)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: 1px solid #e3e6f0;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}

canvas {
    max-height: 300px !important;
}
</style>
@endsection