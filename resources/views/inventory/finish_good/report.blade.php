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
                        <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Laporan Inventory Finish Goods</h1>
                        <div style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;"></div>
                        <p class="text-muted mt-2 mb-0">Laporan ringkasan dan detail stok barang jadi.</p>
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
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory-finish-goods.report') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="start_date">Tanggal Mulai:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">Tanggal Akhir:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter mr-1"></i> Filter</button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('inventory-finish-goods.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-success w-100">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        @php
            $summaries = [
                ['label' => 'Total Transaksi', 'value' => number_format($summary->total_transaksi), 'color' => 'primary'],
                ['label' => 'Total Masuk', 'value' => number_format($summary->total_masuk, 2) . ' kg', 'color' => 'success'],
                ['label' => 'Total Keluar', 'value' => number_format($summary->total_keluar, 2) . ' kg', 'color' => 'danger'],
                ['label' => 'Saldo Akhir', 'value' => number_format($summary->saldo_akhir, 2) . ' kg', 'color' => 'info'],
                ['label' => 'Total Batch', 'value' => number_format($summary->total_batch), 'color' => 'warning'],
                ['label' => 'Jenis Produk', 'value' => number_format($summary->total_jenis), 'color' => 'secondary'],
            ];
        @endphp

        @foreach($summaries as $item)
            <div class="col-md-2">
                <div class="card text-white bg-{{ $item['color'] }} shadow-sm">
                    <div class="card-body text-center">
                        <small>{{ $item['label'] }}</small>
                        <h5 class="font-weight-bold">{{ $item['value'] }}</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Expiry Alert --}}
    @if($summary->expired_items > 0 || $summary->expiring_soon > 0)
        <div class="alert alert-warning shadow-sm">
            <h5><i class="fas fa-exclamation-triangle mr-2"></i> Peringatan Expiry</h5>
            <div class="row">
                @if($summary->expired_items > 0)
                    <div class="col-md-6">
                        <strong>Sudah Expired:</strong>
                        <p>{{ $summary->expired_items }} item, total stok {{ number_format($summary->expired_stock, 2) }} kg</p>
                    </div>
                @endif
                @if($summary->expiring_soon > 0)
                    <div class="col-md-6">
                        <strong>Akan Expired (7 hari ke depan):</strong>
                        <p>{{ $summary->expiring_soon }} item, total stok {{ number_format($summary->expiring_soon_stock, 2) }} kg</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Charts --}}
    <div class="row mb-4">
        @foreach([
            ['title' => 'Grafik Stok Harian', 'id' => 'stockChart'],
            ['title' => 'Transaksi Masuk vs Keluar', 'id' => 'transactionChart'],
            ['title' => 'Distribusi Jenis Produk', 'id' => 'jenisChart'],
            ['title' => 'Analisis Expiry', 'id' => 'expiryChart'],
        ] as $chart)
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white font-weight-bold">{{ $chart['title'] }}</div>
                    <div class="card-body">
                        <canvas id="{{ $chart['id'] }}" height="280"></canvas>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header text-white font-weight-bold">
            <i class="fas fa-list-ul mr-2"></i> Detail Transaksi Finish Goods
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-nowrap" id="finishGoodsTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Batch</th>
                            <th>Jenis</th>
                            <th>Blend</th>
                            <th>Penjualan</th>
                            <th>Expired</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Saldo</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $item)
                            <tr class="{{ $item->expired_date && $item->expired_date <= date('Y-m-d') ? 'table-danger' : ($item->expired_date && $item->expired_date <= date('Y-m-d', strtotime('+7 days')) ? 'table-warning' : '') }}">
                                <td>{{ date('d/m/Y H:i', strtotime($item->timestamp)) }}</td>
                                <td><strong>{{ $item->Id_batch_production }}</strong></td>
                                <td><span class="badge badge-{{ $item->jenis == 'PreRoastBlend' ? 'primary' : ($item->jenis == 'PostRoast' ? 'success' : 'info') }}">{{ $item->jenis }}</span></td>
                                <td>{{ $item->Id_postRoastblend ?? '-' }}</td>
                                <td>{{ $item->Id_penjualan ?? '-' }}</td>
                                <td>
                                    @if($item->expired_date)
                                        <span class="badge badge-{{ $item->expired_date <= date('Y-m-d') ? 'danger' : ($item->expired_date <= date('Y-m-d', strtotime('+7 days')) ? 'warning' : 'success') }}">
                                            {{ date('d/m/Y', strtotime($item->expired_date)) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-success">{{ number_format($item->jml_masuk, 2) }}</td>
                                <td class="text-danger">{{ number_format($item->jml_keluar, 2) }}</td>
                                <td><strong>{{ number_format($item->saldo, 2) }}</strong></td>
                                <td><span class="badge badge-{{ $item->production_status == 'closing' ? 'success' : ($item->production_status == 'on process' ? 'warning' : ($item->production_status == 'cancel' ? 'danger' : 'secondary')) }}">{{ ucfirst($item->production_status ?? 'N/A') }}</span></td>
                                <td>{{ $item->Catatan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Chart.js & DataTables -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#finishGoodsTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "desc" ]],
        "language": { "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json" }
    });

    const chartData = @json($chartData);

    new Chart(document.getElementById('stockChart'), {
        type: 'line',
        data: {
            labels: chartData.dailyStock.map(i => i.tanggal),
            datasets: [{
                label: 'Total Stock (kg)',
                data: chartData.dailyStock.map(i => parseFloat(i.total_stock)),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            }]
        }
    });

    new Chart(document.getElementById('transactionChart'), {
        type: 'bar',
        data: {
            labels: chartData.dailyTransactions.map(i => i.tanggal),
            datasets: [
                { label: 'Masuk (kg)', data: chartData.dailyTransactions.map(i => parseFloat(i.total_masuk)), backgroundColor: 'rgba(54, 162, 235, 0.7)' },
                { label: 'Keluar (kg)', data: chartData.dailyTransactions.map(i => parseFloat(i.total_keluar)), backgroundColor: 'rgba(255, 99, 132, 0.7)' }
            ]
        }
    });

    new Chart(document.getElementById('jenisChart'), {
        type: 'pie',
        data: {
            labels: chartData.jenisDistribution.map(i => i.jenis),
            datasets: [{ data: chartData.jenisDistribution.map(i => i.total_stock), backgroundColor: ['#36a2eb','#4bc0c0','#ff6384'] }]
        }
    });

    new Chart(document.getElementById('expiryChart'), {
        type: 'doughnut',
        data: {
            labels: chartData.expiryAnalysis.map(i => i.status_expiry),
            datasets: [{ data: chartData.expiryAnalysis.map(i => i.total_stock), backgroundColor: ['#dc3545','#ffc107','#17a2b8','#28a745','#6c757d'] }]
        }
    });
});
</script>
@endsection