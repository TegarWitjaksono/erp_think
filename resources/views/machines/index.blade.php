@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-cogs fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Machines</h1>
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
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">Machines
                                        Products</li>
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
                <button type="button" class="btn btn-coffee mb-3" data-toggle="modal" data-target="#addMachinesModal">
                    <i class="fas fa-plus-circle mr-2"></i> Add Machines Product
                </button>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Machines Products Data
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="products-table" class="table datatable table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Merk</th>
                                        <th>Location</th>
                                        <th>Serial Number</th>
                                        <th>Type</th>
                                        <th>Kapasitas</th>
                                        <th>Plc</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($data as $machines)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $machines->merk }}</td>
                                            <td>{{ $machines->location }}</td>
                                            <td>{{ $machines->serial_number }}</td>
                                            <td>{{ $machines->type }}</td>
                                            <td>{{ $machines->kapasitas }}</td>

                                            <td>
                                                @if ($machines->plc_support == '1')
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-danger">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($machines->status == 'active')
                                                    <span class="badge badge-primary">Active</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $machines->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('machines.edit', base64_encode($machines->id)) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $machines->id }}" data-toggle="modal"
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
                    Are you sure you want to delete this Machines machines? This action cannot be undone.
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
    <div class="modal fade" id="addMachinesModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document"> <!-- modal-md added here -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add New Machine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('machines.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="merk">Merk</label>
                            <input type="text" name="merk" id="merk"
                                class="form-control @error('merk') is-invalid @enderror" required
                                value="{{ old('merk') }}">
                            @error('merk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" name="location" id="location"
                                class="form-control @error('location') is-invalid @enderror" required
                                value="{{ old('location') }}">
                            @error('location')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="serial_number">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number"
                                class="form-control @error('serial_number') is-invalid @enderror" required
                                value="{{ old('serial_number') }}">
                            @error('serial_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        

                        <div class="form-group">
                            <label for="type">typee</label>
                            <input typee="text" name="type" id="type"
                                class="form-control @error('type') is-invalid @enderror" required
                                value="{{ old('type') }}">
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kapasitas">Kapasitas</label>
                            <input type="text" name="kapasitas" id="kapasitas"
                                class="form-control @error('kapasitas') is-invalid @enderror" required
                                value="{{ old('kapasitas') }}">
                            @error('kapasitas')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Select status</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="plc_support">plc_support</label>
                            <select name="plc_support" id="plc_support" class="form-control @error('plc_support') is-invalid @enderror"
                                required>
                                <option value="">Pilih PLC</option>
                                <option value="1" {{ old('plc_support') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('plc_support') == 'No' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            @error('plc_support')
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
                var url = "{{ route('machines.destroy', ':id') }}";
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
