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
                            <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Inventory Bahan Baku</h1>
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
                                    Inventory Bahan Baku
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
                <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Data
                </a>

                {{-- Data Table Card --}}
                <div class="card shadow-sm">
                    <div class="card-header text-white">
                        <i class="fas fa-table me-1"></i> Data Inventory
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="inventory-table" class="table datatable table-hover text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Batch</th>
                                        <th>No Inventory</th>
                                        <th>Supplier</th>
                                        <th>Kadar Air</th>
                                        <th>Bulk Density</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Sisa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->no_batch_penerimaan }}</td>
                                            <td>{{ $item->no_inventory }}</td>
                                            <td>{{ $item->nama_supplier }}</td>
                                            <td>{{ $item->kadar_air }}</td>
                                            <td>{{ $item->bulk }}</td>
                                            <td>{{ $item->masuk }}</td>
                                            <td>{{ $item->keluar }}</td>
                                            <td>{{ number_format($item->masuk - $item->keluar, 2, ',', '.') }}</td>


                                            <td>
                                                <a href="{{ route('inventory.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('inventory.cancel', $item->id_detail_penerimaan) }}">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        title="Batalkan">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $item->id }}" data-toggle="modal"
                                                    data-target="#deleteModal" title="Hapus">
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


    <script>
        $(document).ready(function() {
            $('#inventory-table').DataTable({
                responsive: true
            });

            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                const url = "{{ route('inventory.destroy', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', url);
            });

            setTimeout(() => $('.alert').alert('close'), 5000);
        });
    </script>
@endsection
