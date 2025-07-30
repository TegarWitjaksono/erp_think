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
                            <form action="{{ route('roast_profile.update', $data->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea name="deskripsi" id="deskripsi"
                                            class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="charge_temp">Charge Temp (°C)</label>
                                            <input type="number" step="0.01" name="charge_temp" id="charge_temp" class="form-control"
                                                value="{{ old('charge_temp', $data->charge_temp) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tp_temp">Turning Point Temp (°C)</label>
                                            <input type="number" step="0.01" name="tp_temp" id="tp_temp" class="form-control"
                                                value="{{ old('tp_temp', $data->tp_temp) }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="tp_time_sec">TP Time (sec)</label>
                                            <input type="number" name="tp_time_sec" id="tp_time_sec" class="form-control"
                                                value="{{ old('tp_time_sec', $data->tp_time_sec) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="de_temp">Dry End Temp (°C)</label>
                                            <input type="number" step="0.01" name="de_temp" id="de_temp" class="form-control"
                                                value="{{ old('de_temp', $data->de_temp) }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="de_time_sec">DE Time (sec)</label>
                                            <input type="number" name="de_time_sec" id="de_time_sec" class="form-control"
                                                value="{{ old('de_time_sec', $data->de_time_sec) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fcs_temp">First Crack Start Temp (°C)</label>
                                            <input type="number" step="0.01" name="fcs_temp" id="fcs_temp" class="form-control"
                                                value="{{ old('fcs_temp', $data->fcs_temp) }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="fcs_time_sec">FCS Time (sec)</label>
                                            <input type="number" name="fcs_time_sec" id="fcs_time_sec" class="form-control"
                                                value="{{ old('fcs_time_sec', $data->fcs_time_sec) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="drop_temp">Drop Temp (°C)</label>
                                            <input type="number" step="0.01" name="drop_temp" id="drop_temp" class="form-control"
                                                value="{{ old('drop_temp', $data->drop_temp) }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="drop_time_sec">Drop Time (sec)</label>
                                        <input type="number" name="drop_time_sec" id="drop_time_sec" class="form-control"
                                            value="{{ old('drop_time_sec', $data->drop_time_sec) }}">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
