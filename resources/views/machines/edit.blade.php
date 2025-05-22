@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                                <i class="fas fa-coffee fa-2x" style="color: #79523B;"></i>
                            </div>
                            <div>
                                <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">Edit Machine</h1>
                                <div
                                    style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                                </div>
                                <p class="text-muted mt-2 mb-0">Manage your roasted coffee Machine inventory</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="/home" style="color: #79523B;"><i
                                                class="fas fa-home"></i> Home</a></li>
                                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">
                                        Machine</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Machine</h3>
                            </div>
                            <form action="{{ route('machines.update', $machine->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="merk">Merk</label>
                                        <input type="text" name="merk" id="merk"
                                            class="form-control @error('merk') is-invalid @enderror" required
                                            value="{{ old('merk', $machine->merk) }}">
                                        @error('merk')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" name="location" id="location"
                                            class="form-control @error('location') is-invalid @enderror" required
                                            value="{{ old('location', $machine->location) }}">
                                        @error('location')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="serial_number">Serial Number</label>
                                        <input type="text" name="serial_number" id="serial_number"
                                            class="form-control @error('serial_number') is-invalid @enderror" required
                                            value="{{ old('serial_number', $machine->serial_number) }}">
                                        @error('serial_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="typ">Type</label>
                                        <input type="text" name="typ" id="typ"
                                            class="form-control @error('typ') is-invalid @enderror" required
                                            value="{{ old('typ', $machine->typ) }}">
                                        @error('typ')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="kapasitas">Kapasitas</label>
                                        <input type="text" name="kapasitas" id="kapasitas"
                                            class="form-control @error('kapasitas') is-invalid @enderror" required
                                            value="{{ old('kapasitas', $machine->kapasitas) }}">
                                        @error('kapasitas')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status"
                                            class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="">Select status</option>
                                            <option value="active"
                                                {{ old('status', $machine->status) == 'active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="inactive"
                                                {{ old('status', $machine->status) == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="plc">PLC</label>
                                        <select name="plc" id="plc"
                                            class="form-control @error('plc') is-invalid @enderror" required>
                                            <option value="">PLC</option>
                                            <option value="1" {{ old('plc', $machine->plc) == 1 ? 'selected' : '' }}>
                                                Yes</option>
                                            <option value="0" {{ old('plc', $machine->plc) == 0 ? 'selected' : '' }}>
                                                No</option>
                                        </select>
                                        @error('plc')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('machines.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-coffee">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
