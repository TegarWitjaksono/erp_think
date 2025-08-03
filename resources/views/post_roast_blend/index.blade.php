@extends('dashboard')

@section('konten')
<div class="content-wrapper">
    {{-- Header --}}
    <div class="content-header bg-light border-bottom shadow-sm mb-3">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                        <i class="fas fa-boxes fa-2x" style="color: #79523B;"></i>
                    </div>
                    <div>
                        <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Post Roast Blends</h1>
                        <div class="mt-1 mb-2" style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); border-radius: 3px;"></div>
                        <p class="text-muted mb-0">Kelola stok bahan baku dengan mudah</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-right mt-3">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="/home" class="text-decoration-none" style="color: #79523B;"><i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="breadcrumb-item active font-weight-bold" aria-current="page">Post Roast Blends</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    <div class="container-fluid mb-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <section class="content">
        <div class="container-fluid">
            <a href="{{ route('post-roast-blends.create') }}" class="btn btn-coffee mb-3"><i class="fas fa-plus-circle mr-2"></i>Tambah Blend</a>

            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <i class="fas fa-table me-1"></i> Data Post Roast Blends
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="roastBlendsTable" class="table table-striped table-hover text-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Timestamp</th>
                                    <th>Est Expired Date</th>
                                    <th>Cupping Score</th>
                                    <th>Note Flavour</th>
                                    <th>Catatan</th>
                                    <th>Berat Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blends as $blend)
                                <tr>
                                    <td>{{ $blend->id }}</td>
                                    <td>{{$blend->timestamp}}</td>
                                    <td>{{$blend->est_expired_date}}</td>
                                    <td>{{ $blend->cupping_score }}</td>
                                    <td>{{ $blend->note_flavour }}</td>
                                    <td>{{ $blend->catatan }}</td>
                                    <td>{{ $blend->berat_total }} kg</td>
                                    <td>
                                        <span class="badge badge-{{ $blend->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($blend->status) }}
                                        </span>
                                    </td>
                                        <td>
                                            <a href="{{route('post-roast-blends.show',$blend->id)}}" class="btn btn-sm btn-secondary"> <i class="fas fa-list"></i></a>
                                            <a href="{{ route('post-roast-blends.edit', $blend->id) }}" class="btn btn-sm btn-warning"> <i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $blend->id }}" data-toggle="modal" data-target="#deleteModal"> <i class="fas fa-trash-alt"></i></button>
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
                <h5 class="modal-title" id="deleteModalLabel">Hapus Data</h5>
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
                <h5 class="modal-title" id="addModalLabel">Tambah Data Bahan Baku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="penerimaan">Penerimaan</label>
                        <input type="text" name="penerimaan" id="penerimaan" class="form-control @error('penerimaan') is-invalid @enderror" value="{{ old('penerimaan') }}" required>
                        @error('penerimaan')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="kadar_air">Kadar Air (%)</label>
                        <input type="text" name="kadar_air" id="kadar_air" class="form-control @error('kadar_air') is-invalid @enderror" value="{{ old('kadar_air') }}" required>
                        @error('kadar_air')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="bulk_densitas">Bulk Densitas (g/ml)</label>
                        <input type="text" name="bulk_densitas" id="bulk_densitas" class="form-control @error('bulk_densitas') is-invalid @enderror" value="{{ old('bulk_densitas') }}" required>
                        @error('bulk_densitas')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="debit_qty">Debit Qty</label>
                        <input type="number" name="debit_qty" id="debit_qty" class="form-control @error('debit_qty') is-invalid @enderror" value="{{ old('debit_qty') }}" required>
                        @error('debit_qty')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="credit_qty">Credit Qty</label>
                        <input type="number" name="credit_qty" id="credit_qty" class="form-control @error('credit_qty') is-invalid @enderror" value="{{ old('credit_qty') }}" required>
                        @error('credit_qty')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
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
        $('#roastBlendsTable').DataTable({ responsive: true });

        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            let url = "{{ route('post-roast-blends.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', url);
        });

        setTimeout(() => $('.alert').alert('close'), 5000);
    });
</script>
@endsection