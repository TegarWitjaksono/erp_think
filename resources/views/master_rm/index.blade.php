@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-cubes fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Nickname Raw Materials</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage your roasted coffee products inventory</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Nickname</li>
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
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addModalSku">
                    <i class="fas fa-plus-circle mr-2"></i> Add Raw Material New
                </button>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Raw Material New
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="products-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Origin</th>
                                        <th>Jenis</th>
                                        <th>Varietas</th>
                                        <th>Proses</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $rm)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $rm->nama ?? 'Belum ada' }}</td>
                                            <td>{{ $rm->origin_desc ?? 'Belum ada' }}</td>
                                            <td>{{ $rm->jenis_desc ?? 'Belum ada' }}</td>
                                            <td>{{ $rm->varietas_desc }}</td>
                                            <td>{{ $rm->nama_proses }}</td>
                                            <!-- Modify the actions column to include a detail button -->
                                            <td>

                                                <a href="{{ route('master_raw.edit', base64_encode($rm->id)) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $rm->id }}" data-toggle="modal"
                                                    data-target="#deleteModal">
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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Stok ? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addModalSku" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document"> <!-- modal-md added here -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add New Raw Materials</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master_raw.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="id_barang">Nama Raw Material</label>
                            <input type="text" name="nama" class="form-control">
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_barang">Pilih Origin</label>
                            <select name="id_origin" id="id_origin"
                                class="form-control @error('id_origin') is-invalid @enderror" required>
                                <option value="">Select Origin</option>
                                @foreach ($origin as $item)
                                    <option value="{{ $item->id_origin }}"
                                        {{ old('id_origin') == $item->id_origin ? 'selected' : '' }}>
                                        {{ $item->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_origin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_barang">Pilih Jenis</label>
                            <select name="id_jenis" id="id_jenis"
                                class="form-control @error('id_jenis') is-invalid @enderror" required>
                                <option value="">Select Jenis</option>
                                @foreach ($jenis as $item)
                                    <option value="{{ $item->id_jenis }}"
                                        {{ old('id_jenis') == $item->id_jenis ? 'selected' : '' }}>
                                        {{ $item->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_jenis')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_varietas">Pilih Varietas</label>
                            <select name="id_varietas" id="id_varietas"
                                class="form-control @error('id_varietas') is-invalid @enderror" required>
                                <option value="">Select Varietas</option>
                                @foreach ($varietas as $var)
                                    <option value="{{ $var->id_varietas }}"
                                        {{ old('id_varietas') == $var->id_varietas ? 'selected' : '' }}>
                                        {{ $var->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_varietas')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_barang">Pilih proses</label>
                            <select name="id_proses" id="id_proses"
                                class="form-control @error('id_proses') is-invalid @enderror" required>
                                <option value="">Select Proses</option>
                                @foreach ($proses as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('id_proses') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_proses }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_proses')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-coffee">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var url = "{{ route('master_raw.destroy', ':id') }}";
                url = url.replace(':id', id);
                $('#deleteForm').attr('action', url);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@endsection
