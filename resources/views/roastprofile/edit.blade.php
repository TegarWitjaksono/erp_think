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
                                <h3 class="card-title">Edit Roast Profile</h3>
                            </div>

                            <!-- Form untuk Update -->
                            <form id="updateForm" action="{{ route('roast_profile.update', $data->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('PUT')
                            </form>

                            <!-- Form untuk Save As -->
                            <form id="saveAsForm" action="{{ route('roast_profile.store') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>

                            <!-- Form utama untuk input data -->
                            <form id="mainForm">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Phase</th>
                                                    <th>Temperatur (°C)</th>
                                                    <th>Waktu (detik)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Charge</td>
                                                    <td><input type="number" step="0.01" name="charge_temp"
                                                            class="form-control"
                                                            value="{{ old('charge_temp', $data->charge_temp) }}"></td>
                                                    <td><input type="number" name="charge_time_sec" class="form-control"
                                                            value="{{ old('charge_time_sec', $data->charge_time_sec ?? 0) }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>TP</td>
                                                    <td><input type="number" step="0.01" name="tp_temp"
                                                            class="form-control"
                                                            value="{{ old('tp_temp', $data->tp_temp) }}"></td>
                                                    <td><input type="number" name="tp_time_sec" class="form-control"
                                                            value="{{ old('tp_time_sec', $data->tp_time_sec) }}"></td>
                                                </tr>
                                                <tr>
                                                    <td>DE</td>
                                                    <td><input type="number" step="0.01" name="de_temp"
                                                            class="form-control"
                                                            value="{{ old('de_temp', $data->de_temp) }}"></td>
                                                    <td><input type="number" name="de_time_sec" class="form-control"
                                                            value="{{ old('de_time_sec', $data->de_time_sec) }}"></td>
                                                </tr>
                                                <tr>
                                                    <td>FC</td>
                                                    <td><input type="number" step="0.01" name="fc_temp"
                                                            class="form-control"
                                                            value="{{ old('fc_temp', $data->fc_temp ?? $data->fcs_temp) }}">
                                                    </td>
                                                    <td><input type="number" name="fc_time_sec" class="form-control"
                                                            value="{{ old('fc_time_sec', $data->fc_time_sec ?? $data->fcs_time_sec) }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>SC</td>
                                                    <td><input type="number" step="0.01" name="sc_temp"
                                                            class="form-control"
                                                            value="{{ old('sc_temp', $data->sc_temp) }}"></td>
                                                    <td><input type="number" name="sc_time_sec" class="form-control"
                                                            value="{{ old('sc_time_sec', $data->sc_time_sec) }}"></td>
                                                </tr>
                                                <tr>
                                                    <td>Drop</td>
                                                    <td><input type="number" step="0.01" name="drop_temp"
                                                            class="form-control"
                                                            value="{{ old('drop_temp', $data->drop_temp) }}"></td>
                                                    <td><input type="number" name="drop_time_sec" class="form-control"
                                                            value="{{ old('drop_time_sec', $data->drop_time_sec) }}"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.history.back()">Batal</button>
                                    <button type="button" class="btn btn-info" onclick="saveAs()">
                                        <i class="fas fa-copy"></i> Save As New
                                    </button>
                                    <button type="button" class="btn btn-coffee" onclick="updateProfile()">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function updateProfile() {
            // Konfirmasi update
            if (confirm('Apakah Anda yakin ingin mengupdate roast profile ini?')) {
                // Copy semua data dari form utama ke form update
                copyFormData('updateForm');

                // Submit form update
                document.getElementById('updateForm').submit();
            }
        }

        function saveAs() {

            copyFormData('saveAsForm');
            let saveAsForm = document.getElementById('saveAsForm');
            let deskripsiInput = saveAsForm.querySelector('[name="deskripsi"]');
            // Submit form save as
            saveAsForm.submit();
        }

        function copyFormData(targetFormId) {
            const mainForm = document.getElementById('mainForm');
            const targetForm = document.getElementById(targetFormId);

            // Hapus input lama kecuali _token dan _method
            targetForm.querySelectorAll('input[name]:not([name="_token"]):not([name="_method"]), textarea[name]').forEach(
                input => {
                    input.remove();
                });

            // Copy semua input dari mainForm
            const inputs = mainForm.querySelectorAll('input[name], textarea[name]');
            inputs.forEach(input => {
                const clonedInput = input.cloneNode(true);
                clonedInput.style.display = 'none';
                targetForm.appendChild(clonedInput);
            });
        }
    </script>

    <style>
        .btn-coffee {
            background-color: #79523B;
            border-color: #79523B;
            color: white;
        }

        .btn-coffee:hover {
            background-color: #5d3e2d;
            border-color: #5d3e2d;
            color: white;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
        }
    </style>
@endsection
