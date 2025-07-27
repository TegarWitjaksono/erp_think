@extends('dashboard')

@section('konten')
<div class="content-wrapper">

    {{-- Header --}}
    <div class="content-header bg-light border-bottom shadow-sm mb-3">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex">
                    <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                        <i class="fas fa-warehouse fa-2x" style="color: #79523B;"></i>
                    </div>
                    <div>
                        <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Stok Inventory Real-time</h1>
                        <div style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;"></div>
                        <p class="text-muted mt-2 mb-0">Pantau stok bahan baku terkini secara real-time</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right mt-3">
                        <button class="btn btn-primary mr-2" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <a href="{{ route('inventory.report') }}" class="btn btn-info">
                            <i class="fas fa-chart-line"></i> Laporan Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert untuk Stok Rendah --}}
    @php
        $lowStockItems = $currentStock->filter(fn($item) => $item->current_stock < 100);
    @endphp
    @if($lowStockItems->count() > 0)
    <div class="container-fluid mb-3">
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Peringatan Stok Rendah</h5>
            <p>Terdapat <strong>{{ $lowStockItems->count() }}</strong> batch dengan stok di bawah 100kg:</p>
            <ul class="mb-0">
                @foreach($lowStockItems as $item)
                    <li>{{ $item->id_batch_mp }} - {{ number_format($item->current_stock, 2) }}kg</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <section class="content">
        <div class="container-fluid">

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <x-summary-card title="Total Batch" value="{{ $currentStock->count() }}" bg="primary" />
                <x-summary-card title="Total Stok" value="{{ number_format($currentStock->sum('current_stock'), 2) }} kg" bg="success" />
                <x-summary-card title="Kadar Air Rata-rata" value="{{ number_format($currentStock->avg('avg_kadar_air'), 2) }}%" bg="warning" />
                <x-summary-card title="Bulk Density Rata-rata" value="{{ number_format($currentStock->avg('avg_bulk_density'), 3) }}" bg="info" />
            </div>

            {{-- Chart --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i> Grafik Stok per Batch</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockPerBatchChart" height="100"></canvas>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-table mr-2"></i> Detail Stok per Batch</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-nowrap" id="currentStockTable">
                            <thead>
                                <tr>
                                    <th>Batch MP</th>
                                    <th>Batch Detail</th>
                                    <th>Keterangan</th>
                                    <th>Stok (kg)</th>
                                    <th>Kadar Air (%)</th>
                                    <th>Bulk Density</th>
                                    <th>Update Terakhir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currentStock as $item)
                                <tr class="{{ $item->current_stock < 100 ? 'table-warning' : '' }}">
                                    <td><strong>{{ $item->id_batch_mp }}</strong></td>
                                    <td>{{ $item->id_batch ?? '-' }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td class="text-right">
                                        <span class="badge badge-{{ $item->current_stock < 100 ? 'warning' : 'success' }} badge-lg">
                                            {{ number_format($item->current_stock, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ number_format($item->avg_kadar_air, 2) }}</td>
                                    <td class="text-center">{{ number_format($item->avg_bulk_density, 3) }}</td>
                                    <td>{{ date('d/m/Y H:i', strtotime($item->last_update)) }}</td>
                                    <td>
                                        @if($item->current_stock >= 500)
                                            <span class="badge badge-success">Aman</span>
                                        @elseif($item->current_stock >= 100)
                                            <span class="badge badge-warning">Sedang</span>
                                        @else
                                            <span class="badge badge-danger">Rendah</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <th colspan="3">TOTAL</th>
                                    <th class="text-right">{{ number_format($currentStock->sum('current_stock'), 2) }} kg</th>
                                    <th class="text-center">{{ number_format($currentStock->avg('avg_kadar_air'), 2) }}%</th>
                                    <th class="text-center">{{ number_format($currentStock->avg('avg_bulk_density'), 3) }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

{{-- Script --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        $('#currentStockTable').DataTable({
            "pageLength": 25,
            "order": [[3, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
            }
        });

        const stockData = @json($currentStock);
        const ctx = document.getElementById('stockPerBatchChart').getContext('2d');
        const labels = stockData.map(item => item.id_batch_mp);
        const data = stockData.map(item => parseFloat(item.current_stock));
        const backgroundColors = data.map(value => {
            if (value >= 500) return 'rgba(40, 167, 69, 0.8)';
            if (value >= 100) return 'rgba(255, 193, 7, 0.8)';
            return 'rgba(220, 53, 69, 0.8)';
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stok (kg)',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors.map(c => c.replace('0.8', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Stok (kg)' }},
                    x: { title: { display: true, text: 'Batch' }}
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'Stok: ' + ctx.parsed.y.toLocaleString('id-ID') + ' kg'
                        }
                    }
                }
            }
        });
    });

    function refreshData() {
        location.reload();
    }

    setInterval(() => refreshData(), 300000); // auto-refresh tiap 5 menit
</script>
@endsection
