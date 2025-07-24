@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
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
                        <form action="{{ route('detail_penerimaan.update', $data->id_detail_penerimaan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- Master Penerimaan --}}
                                        <div class="form-group">
                                            <label>Master Penerimaan</label>
                                            <input type="text" class="form-control"
                                                value="{{ $master_penerimaan->firstWhere('id_penerimaan', $data->id_penerimaan)->keterangan ?? 'No Description' }}"
                                                readonly>
                                        </div>
                                        {{-- ID Penerimaan --}}
                                        <div class="form-group">
                                            <label>ID Penerimaan</label>
                                            <input type="text" class="form-control"
                                                value="{{ $data->id_penerimaan }}"
                                                readonly>
                                            <input type="hidden" name="id_penerimaan" value="{{ $data->id_penerimaan }}">
                                            @error('id_penerimaan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Batch ID --}}
                                        <div class="form-group">
                                            <label for="id_batch">Batch ID</label>
                                            <input type="text" name="id_batch" id="id_batch"
                                                class="form-control @error('id_batch') is-invalid @enderror"
                                                value="{{ old('id_batch', $data->id_batch_mp ?? $data->id_batch ?? '') }}" readonly required>
                                            @error('id_batch')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Supplier --}}
                                        <div class="form-group">
                                            <label for="id_suplier">Supplier</label>
                                            <select name="id_suplier" id="id_suplier"
                                                class="form-control @error('id_suplier') is-invalid @enderror" required>
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $data->id_suplier == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_suplier')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Jenis --}}
                                        <div class="form-group">
                                            <label for="id_jenis">Jenis</label>
                                            <select name="id_jenis" id="id_jenis"
                                                class="form-control @error('id_jenis') is-invalid @enderror" required>
                                                <option value="">Select Jenis</option>
                                                @foreach ($jenis as $item)
                                                    <option value="{{ $item->id_jenis }}"
                                                        {{ $data->id_jenis == $item->id_jenis ? 'selected' : '' }}>
                                                        {{ $item->deskripsi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_jenis')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Varietas --}}
                                        <div class="form-group">
                                            <label for="id_varietas">Varietas</label>
                                            <select name="id_varietas" id="id_varietas"
                                                class="form-control @error('id_varietas') is-invalid @enderror" required>
                                                <option value="">Select Varietas</option>
                                                @foreach ($varietas as $item)
                                                    <option value="{{ $item->id_varietas }}"
                                                        {{ $data->id_varietas == $item->id_varietas ? 'selected' : '' }}>
                                                        {{ $item->deskripsi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_varietas')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Grade --}}
                                        <div class="form-group">
                                            <label for="id_grade">Grade</label>
                                            <select name="id_grade" id="id_grade"
                                                class="form-control @error('id_grade') is-invalid @enderror" required>
                                                <option value="">Select Grade</option>
                                                @foreach ($grade as $item)
                                                    <option value="{{ $item->id_grade }}"
                                                        {{ $data->id_grade == $item->id_grade ? 'selected' : '' }}>
                                                        {{ $item->deskripsi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_grade')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- Origin --}}
                                        <div class="form-group">
                                            <label for="id_origin">Origin</label>
                                            <select name="id_origin" id="id_origin"
                                                class="form-control @error('id_origin') is-invalid @enderror" required>
                                                <option value="">Select Origin</option>
                                                @foreach ($origin as $item)
                                                    <option value="{{ $item->id_origin }}"
                                                        {{ $data->id_origin == $item->id_origin ? 'selected' : '' }}>
                                                        {{ $item->deskripsi }}
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
                                                value="{{ $data->kadar_air }}" required>
                                            @error('kadar_air')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Bulk --}}
                                        <div class="form-group">
                                            <label for="bulk">Bulk</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="bulk_value" id="bulk_value"
                                                    class="form-control @error('bulk_value') is-invalid @enderror"
                                                    value="{{ $data->bulk_value }}" required placeholder="Value">
                                                <div class="input-group-append">
                                                    <select name="bulk_unit" id="bulk_unit" class="form-control @error('bulk_unit') is-invalid @enderror" required>
                                                        <option value="kg" {{ $data->bulk_unit == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                                        <option value="liter" {{ $data->bulk_unit == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @error('bulk_value')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @error('bulk_unit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Kemasan --}}
                                        <div class="form-group">
                                            <label for="id_kemasan">Kemasan</label>
                                            <input type="text" name="id_kemasan" id="id_kemasan"
                                                class="form-control @error('id_kemasan') is-invalid @enderror"
                                                value="{{ $data->id_kemasan }}" required>
                                            @error('id_kemasan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Berat --}}
                                        <div class="form-group">
                                            <label for="berat">Berat</label>
                                            <input type="number" step="0.01" name="berat" id="berat"
                                                class="form-control @error('berat') is-invalid @enderror"
                                                value="{{ $data->berat }}" required>
                                            @error('berat')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Jumlah --}}
                                        <div class="form-group">
                                            <label for="jumlah">Jumlah</label>
                                            <input type="number" name="jumlah" id="jumlah"
                                                class="form-control @error('jumlah') is-invalid @enderror"
                                                value="{{ $data->jumlah }}" required>
                                            @error('jumlah')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- Size --}}
                                        <div class="form-group">
                                            <label for="size">Size</label>
                                            <input type="text" name="size" id="size"
                                                class="form-control @error('size') is-invalid @enderror"
                                                value="{{ $data->size }}" required>
                                            @error('size')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('detail_penerimaan.index', ['id_penerimaan' => $data->id_penerimaan]) }}"
                                    class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-coffee">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Auto calculate jumlah_tot when berat or jumlah changes
            $('#berat, #jumlah').on('input', function() {
                const berat = parseFloat($('#berat').val()) || 0;
                const jumlah = parseInt($('#jumlah').val()) || 0;
                const jumlah_tot = berat * jumlah;
                // If you want to display the total somewhere
                // $('#jumlah_tot').val(jumlah_tot);
            });
            
            // Auto-fill fields when master_penerimaan is selected
            $('#id_penerimaan').on('change', function() {
                const id_penerimaan = $(this).val();
                if (id_penerimaan) {
                    $.ajax({
                        url: `{{ url('get-master-penerimaan') }}/${id_penerimaan}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                $('#id_suplier').val(data.id_suplier);
                                $('#id_jenis').val(data.id_jenis);
                                $('#id_varietas').val(data.id_varietas);
                                $('#id_grade').val(data.id_grade);
                                $('#id_origin').val(data.id_origin);
                                $('#kadar_air').val(data.kadar_air);
                                
                                // Handle bulk
                                const bulkParts = data.bulk.split(' ');
                                $('#bulk_value').val(bulkParts[0] || '');
                                $('#bulk_unit').val(bulkParts[1] || 'kg');
                                
                                $('#id_kemasan').val(data.id_kemasan);
                                $('#berat').val(data.berat);
                                $('#jumlah').val(data.jumlah);
                                $('#size').val(data.size);
                            }
                        }
                    });
                }
            });
        });
    </script>
    @endsection
@endsection