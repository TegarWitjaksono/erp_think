@extends('dashboard')

@section('konten')

@php
    // buat data dummy: waktu tiap 30 detik hingga 10 menit
    $times = range(0, 600, 30);

    // dummy Bean Temperature (BT) naik dari ~25°C, +0.4°C per detik + noise
    $bt = array_map(function($t) {
        return round(25 + 0.4 * $t + rand(-20, 20) / 100, 2);
    }, $times);

    // dummy Environment Temperature (ET) naik dari ~30°C, +0.3°C per detik + noise
    $et = array_map(function($t) {
        return round(30 + 0.3 * $t + rand(-20, 20) / 100, 2);
    }, $times);
@endphp
    <div class="content-wrapper">

        {{-- Header --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6 d-flex">
                        <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                            <i class="fas fa-boxes fa-2x" style="color: #79523B;"></i>
                        </div>
                        <div>
                            <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Batch Production</h1>
                            <div
                                style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                            </div>
                            <p class="text-muted mt-2 mb-0">Kelola stok bahan baku dengan mudah</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb" class="float-sm-right mt-3">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item">
                                    <a href="/home" style="color: #79523B;">
                                        <i class="fas fa-home"></i> Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item active font-weight-bold" aria-current="page">
                                    Batch Production
                                </li>
                            </ol>
                        </nav>
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

        {{-- Main Content --}}
        <section class="content">
            <div class="container-fluid">

                {{-- Button Tambah --}}
                <a href="{{ route('log-mesins.import') }}" class="btn btn-primary mb-3">Import Log</a>

                {{-- Data Table Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <i class="fas fa-table me-1"></i> Data Batch Production
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="inventory-table" class="table datatable table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                    <th>ID</th>
                <th>Mesin</th>
                <th>Batch</th>
                <th>Waktu Mesin</th>
                <th>Time Roast</th>
                <th>BT</th>
                <th>ET</th>
                <th>Event</th>
                <th>Aksi</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                        
                    </div>
                </div>

            </div>
        </section>

    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="deleteModalLabel" class="modal-title">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Data Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="addModalLabel" class="modal-title">Tambah Data Bahan Baku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        {{-- Tambah form fields di sini --}}
                        <div class="form-group">
                            <label for="penerimaan">Penerimaan</label>
                            <input type="text" name="penerimaan" id="penerimaan"
                                class="form-control @error('penerimaan') is-invalid @enderror" required
                                value="{{ old('penerimaan') }}">
                            @error('penerimaan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Lanjutkan untuk kadar_air, bulk_densitas, debit_qty, credit_qty --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script --}}
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#inventory-table').DataTable({ responsive: true });

                $('.delete-btn').click(function () {
                    const id = $(this).data('id');
                    const url = "{{ route('inventory.destroy', ':id') }}".replace(':id', id);
                    $('#deleteForm').attr('action', url);
                });

                setTimeout(() => $('.alert').alert('close'), 5000);
            });
        </script>
    @endpush
    <div class="container">
    <h1>Roast Curve - Batch </h1>
    <canvas id="roastChart" width="600" height="300"></canvas>
    
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('roastChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($times) !!},
        datasets: [
            {
                label: 'BT',
                data: {!! json_encode($bt) !!},
                fill: false,
                borderColor: 'rgba(220, 53, 69, 1)',
                tension: 0.1
            },
            {
                label: 'ET',
                data: {!! json_encode($et) !!},
                fill: false,
                borderColor: 'rgba(0, 123, 255, 1)',
                tension: 0.1
            }
        ]
    },
    options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Time Roast (s)'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Temperature (°C)'
                }
            }
        }
    }
});
</script>
@endpush

@endsection

