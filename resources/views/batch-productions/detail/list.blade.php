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
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Detail Batch Production</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage batch production</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Batch Production Input</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="col-12">
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
                        <strong>Gagal!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif


                <a href="{{ route('batch-productions.index') }}" class="btn btn-light mb-3" style="color: #79523B; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Data
                </button>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Data Batch Production Input
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="list-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Batch Production</th>  
                                        <th>Kadar Air</th>
                                        <th>Bulk Densitas</th>
                                        <th>Jumlah Keluar</th>
                                      
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                {{$no++}}
                                            </td>
                                            <td>
                                                {{$item->batchproduction_id}}
                                            </td>
                                           
                                             <td>
                                                {{$item->kadar_air}}
                                            </td>
                                             <td>
                                                {{$item->bulk_densitas}}
                                            </td>
                                             <td>
                                                {{$item->qty_out}}
                                            </td>
                                            <td>
                                                <a href="{{ route('batch.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $item->id }}" data-toggle="modal"
                                                    data-target="#deleteModal">
                                                    <i class="fas fa-trash"></i>
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
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
     <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Batch Production Input</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('batch.input',$id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_origin">Inventory</label>
                                    <select name="id_origin" id="id_origin"
                                        class="form-control @error('id_origin') is-invalid @enderror" required>
                                        <option value="">Select Inventory</option>
                                        @foreach ($inventory as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('inventory_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->catatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="inventory-info" class="text-muted small"></div>

                                    @error('inventory_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Kadar Air --}}
                                <div class="form-group">
                                    <label for="kadar_air">Kadar Air (%)</label>
                                    <input type="number" step="0.01" name="kadar_air" id="kadar_air"
                                        class="form-control @error('kadar_air') is-invalid @enderror"
                                        value="{{ old('kadar_air') }}" required>
                                    @error('kadar_air')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Bulk --}}
                                <div class="form-group">
                                    <label for="bulk_densitas">bulk densitas</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="bulk_densitas" id="bulk_densitas"
                                            class="form-control @error('bulk_densitas') is-invalid @enderror"
                                            value="{{ old('bulk_densitas') }}" required placeholder="Value">
                                        
                                    </div>
                                    @error('bulk_densitas')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                  
                                </div>

                                <div class="form-group">
                                    <label for="qty_out">Qty Out</label>
                                    <input type="number" step="0.01" name="qty_out" id="qty_out"
                                        class="form-control @error('qty_out') is-invalid @enderror"
                                        value="{{ old('qty_out') }}" required>
                                    @error('qty_out')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                               <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <div class="form-group">
                                       
                                        <textarea name="catatan" class="form-control">{{ old('catatan') }}</textarea>
                                    </div>
                                    @error('catatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            
                        </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-coffee">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });

            // Handle delete button click
             $('.delete-btn').click(function () {
                    const id = $(this).data('id');
                    const url = "{{ route('batch.delete', ':id') }}".replace(':id', id);
                    $('#deleteForm').attr('action', url);
                });

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