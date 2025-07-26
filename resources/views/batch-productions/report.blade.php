@extends('dashboard')

@section('konten')
<div class="content-wrapper">

    {{-- Header Section --}}
    <div class="content-header bg-light border-bottom shadow-sm mb-3">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex">
                    <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(52, 58, 64, 0.1);">
                        <i class="fas fa-industry fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <h1 class="m-0 font-weight-bold text-dark">Laporan Batch Production</h1>
                        <div style="height: 3px; width: 60px; background: linear-gradient(to right, #6c757d, #adb5bd); margin-top: 5px; border-radius: 3px;"></div>
                        <p class="text-muted mt-2 mb-0">Pantau performa dan kualitas produksi secara menyeluruh</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('batch-production.report') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label>Tanggal Mulai:</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Akhir:</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        @php
            $cards = [
                ['label' => 'Total Batch', 'value' => number_format($summary->total_batch), 'color' => 'primary'],
                ['label' => 'Total Input', 'value' => number_format($summary->total_input, 2) . ' kg', 'color' => 'success'],
                ['label' => 'Total Output', 'value' => number_format($summary->total_output, 2) . ' kg', 'color' => 'info'],
                ['label' => 'Avg. Cupping Score', 'value' => number_format($summary->avg_cupping_score, 2), 'color' => 'warning'],
            ];
        @endphp
        @foreach($cards as $card)
            <div class="col-md-3">
                <div class="card bg-{{ $card['color'] }} text-white shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="mb-1">{{ $card['label'] }}</h6>
                        <h4 class="font-weight-bold">{{ $card['value'] }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Breakdown Section --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white font-weight-bold">Status Produksi</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($summary->status_breakdown as $status)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ ucfirst($status->status) }}
                                <span class="badge bg-primary badge-pill">{{ $status->total }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-danger shadow-sm">
                <div class="card-header bg-danger text-white font-weight-bold">Perhatian</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($summary->attention_breakdown as $attention)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ ucfirst($attention->attention) }}
                                <span class="badge bg-danger badge-pill">{{ $attention->total }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white font-weight-bold">
            <i class="fas fa-table mr-2"></i> Detail Batch Production
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-nowrap" id="batchTable">
                <thead class="thead-light">
                    <tr>
                        <th>Batch ID</th>
                        <th>Tanggal</th>
                        <th>Mesin</th>
                        <th>Lokasi Mesin</th>
                        <th>Level Roast</th>
                        <th>Status</th>
                        <th>Perhatian</th>
                        <th>Input (kg)</th>
                        <th>Output (kg)</th>
                        <th>Kadar Air In</th>
                        <th>Kadar Air Out</th>
                        <th>Agtron</th>
                        <th>Cupping</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($item->datetime)) }}</td>
                            <td>{{ $item->mesin }}</td>
                            <td>{{ $item->location }}</td>
                            <td>{{ $item->level_roast }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ ucfirst($item->attention) }}</td>
                            <td>{{ number_format($item->total_input, 2) }}</td>
                            <td>{{ number_format($item->total_output, 2) }}</td>
                            <td>{{ number_format($item->avg_kadar_air_input, 2) }}%</td>
                            <td>{{ number_format($item->avg_kadar_air_output, 2) }}%</td>
                            <td>{{ number_format($item->avg_agtron, 2) }}</td>
                            <td>{{ number_format($item->avg_cupping_score, 2) }}</td>
                            <td>{{ $item->catatan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#batchTable').DataTable({
            "pageLength": 25,
            "order": [[1, 'desc']]
        });
    });
</script>
@endsection