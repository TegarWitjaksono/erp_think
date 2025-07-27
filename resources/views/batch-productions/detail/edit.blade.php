@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-clipboard-list fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Detail Penerimaan</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Edit detail penerimaan barang</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('detail_penerimaan.index') }}" style="color: #79523B;">Detail Penerimaan</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Edit</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="col-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Edit Detail Penerimaan
                    </div>
                    <div class="card-body">
                         <form action="{{route('batch.update',$data->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                {{-- Inventory --}}
                                <div class="form-group">
                                    <label for="id_origin">Inventory</label>
                                    <select name="id_origin" id="id_origin"
                                        class="form-control @error('id_origin') is-invalid @enderror" required>
                                        <option value="">Select Inventory</option>
                                        @foreach ($inventory as $item)
                                            <option value="{{ $item->id }}"
                                                {{ (old('id_origin', $data->inventory_id) == $item->id) ? 'selected' : '' }}>
                                                {{ $item->catatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_origin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Kadar Air --}}
                                <div class="form-group">
                                    <label for="kadar_air">Kadar Air (%)</label>
                                    <input type="number" step="0.01" name="kadar_air" id="kadar_air"
                                        class="form-control @error('kadar_air') is-invalid @enderror"
                                        value="{{ old('kadar_air', $data->kadar_air) }}" required>
                                    @error('kadar_air')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Bulk Densitas --}}
                                <div class="form-group">
                                    <label for="bulk_densitas">Bulk Densitas</label>
                                    <input type="number" step="0.01" name="bulk_densitas" id="bulk_densitas"
                                        class="form-control @error('bulk_densitas') is-invalid @enderror"
                                        value="{{ old('bulk_densitas', $data->bulk_densitas) }}" required placeholder="Value">
                                    @error('bulk_densitas')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Qty Out --}}
                                <div class="form-group">
                                    <label for="qty_out">Qty Out</label>
                                    <input type="number" step="0.01" name="qty_out" id="qty_out"
                                        class="form-control @error('qty_out') is-invalid @enderror"
                                        value="{{ old('qty_out', $data->qty_out) }}" required>
                                    @error('qty_out')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Catatan --}}
                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan', $data->catatan) }}</textarea>
                                    @error('catatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                @php
                                    $id = DB::table('batchproduction_input')->where('id',$data->id)->first();
                                   
                                @endphp
                                <a href="{{route('batch.list',$id->batchproduction_id)}}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-coffee">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script>
    const inventoryData = @json($inventory);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inventorySelect = document.getElementById('id_origin');

        inventorySelect.addEventListener('change', function () {
            const selectedId = this.value;
            const data = inventoryData.find(item => item.id == selectedId);

            if (data) {
                // Misal field-field ini tersedia di tabel inventory
                document.getElementById('kadar_air').value = data.kadar_air ?? '';
                document.getElementById('bulk_densitas').value = data.bulk_densitas ?? '';

                // Jika kamu punya unit dan data lain, sesuaikan:
                // document.getElementById('bulk_unit').value = data.bulk_unit ?? 'kg';
                // document.getElementById('berat').value = data.berat ?? '';
                // document.getElementById('jumlah').value = data.jumlah ?? '';
                // document.getElementById('size').value = data.size ?? '';
            } else {
                // Clear kalau tidak ada
                document.getElementById('kadar_air').value = '';
                document.getElementById('bulk_densitas').value = '';
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('catatan');
        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
    @endsection
@endsection