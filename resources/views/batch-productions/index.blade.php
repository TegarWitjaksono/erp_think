@extends('dashboard')

@section('konten')
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
                <a href="{{ route('batch-productions.create') }}" class="btn btn-primary mb-3">Tambah Batch</a>

                {{-- Data Table Card --}}
                <div class="card shadow-sm">
                    <div class="card-header text-white">
                        <i class="fas fa-table me-1"></i> Data Batch Production
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="inventory-table" class="table datatable table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>No Batch</th>
                                        <th>Mesin</th>
                                        <th>Method</th>
                                        <th>Berat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                {{ $item->id }}
                                            </td>
                                            <td>
                                                {{ $item->no_batch ?? 'Belum ada no batch !' }}
                                            </td>
                                            <td>
                                                {{ $item->mesin_nama }}
                                            </td>
                                            <td>
                                                {{ $item->method_deskripsi }}
                                            </td>
                                            <td>
                                                {{ $item->keluar ?? 'Belum ada' }}
                                            </td>
                                            <td>
                                                @if ($item->status === 'open')
                                                    <span class="badge bg-success">Open</span>
                                                @elseif ($item->status === 'on process')
                                                    <span class="badge bg-warning text-dark">On Process</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                                @endif

                                            </td>
                                            <td>
                                                <!-- Detail -->
                                                <a href="{{ route('batch.list', $item->id) }}" class="btn btn-info btn-sm"
                                                    title="Detail">
                                                    <i class="fas fa-list"></i>
                                                </a>

                                                <a href="{{ route('batch-production.menu', $item->id) }}"
                                                    class="btn btn-info btn-sm" title="Menu Action">
                                                    <i class="fas fa-list"></i>
                                                </a>

                                                <!-- Edit -->
                                                <a href="{{ route('batch-productions.edit', base64_encode($item->id)) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Status Actions -->
                                                @if ($item->status === 'open')
                                                    <!-- Start Process -->
                                                    <form action="{{ route('batch-productions.start', $item->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Mulai proses batch ini?')">
                                                        @csrf
                                                        <button class="btn btn-primary btn-sm" title="Start Batch">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Cancel Batch -->
                                                    <form action="{{ route('batch-productions.cancel', $item->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Batalkan batch ini?')">
                                                        @csrf
                                                        <button class="btn btn-danger btn-sm" title="Cancel Batch">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    </form>
                                                @elseif ($item->status === 'on process')
                                                    <!-- Close Batch -->
                                                    <form action="{{ route('batch-productions.close', $item->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Anda yakin ingin menutup batch ini?')">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm" title="Close Batch">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Delete -->
                                                <button type="button" class="btn btn-secondary btn-sm delete-btn"
                                                    data-id="{{ $item->id }}" data-toggle="modal"
                                                    data-target="#deleteModal" title="Hapus Batch">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>


                                        </tr>
                                    @endforeach

                                </tbody>

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
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
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

    <script>
        $(document).ready(function() {
            $('#inventory-table').DataTable({
                responsive: true
            });

            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                const url = "{{ route('batch-productions.destroy', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', url);
            });

            setTimeout(() => $('.alert').alert('close'), 5000);
        });
    </script>
@endsection
