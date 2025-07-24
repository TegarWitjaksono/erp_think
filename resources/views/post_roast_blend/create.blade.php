@extends('dashboard')

@section('konten')
    <div class="content-wrapper">

        {{-- Header --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6 d-flex">
                        <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121, 82, 59, 0.15);">
                            <i class="fas fa-flask fa-2x" style="color: #79523B;"></i>
                        </div>
                        <div>
                            <h1 class="m-0 font-weight-bold" style="color: #4A2C1A;">
                                {{ isset($blend) ? 'Edit' : 'Tambah' }} Post Roast Blend
                            </h1>
                            <div
                                style="height: 3px; width: 60px; background: linear-gradient(to right, #79523B, #D2B48C); margin-top: 5px; border-radius: 3px;">
                            </div>
                            <p class="text-muted mt-2 mb-0">Kelola detail post-roast blend Anda</p>
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('post-roast-blends.index') }}" style="color: #79523B;">
                                        Post Roast Blends
                                    </a>
                                </li>
                                <li class="breadcrumb-item active font-weight-bold" aria-current="page">
                                    {{ isset($blend) ? 'Edit' : 'Tambah' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <section class="content">
            <div class="container-fluid">

                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <i class="fas fa-table me-1"></i>
                        {{ isset($blend) ? 'Edit' : 'Tambah' }} Post Roast Blend
                    </div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{ isset($blend)
                                        ? route('post-roast-blends.update', $blend->id)
                                        : route('post-roast-blends.store') }}">
                            @csrf
                            @isset($blend) @method('PUT') @endisset

                            <div class="mb-3">
                                <label>Expired Date</label>
                                <input type="date"
                                       name="expired_date"
                                       class="form-control"
                                       value="{{ old('expired_date', $blend->expired_date ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label>Cupping Score</label>
                                <input type="number"
                                       step="0.01"
                                       name="cupping_score"
                                       class="form-control"
                                       value="{{ old('cupping_score', $blend->cupping_score ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label>Note Flavour</label>
                                <textarea name="note_flavour"
                                          class="form-control">{{ old('note_flavour', $blend->note_flavour ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label>Catatan</label>
                                <textarea name="catatan"
                                          class="form-control">{{ old('catatan', $blend->catatan ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label>Berat Total</label>
                                <input type="number"
                                       step="0.001"
                                       id="total-weight"
                                       name="total_weight"
                                       class="form-control"
                                       value="{{ old('total_weight', $blend->total_weight ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="close"  {{ old('status', $blend->status ?? '') == 'close'  ? 'selected' : '' }}>Close</option>
                                    <option value="cancel" {{ old('status', $blend->status ?? '') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                                </select>
                            </div>

                            <h4 class="mt-4">Detail Blend</h4>
                            <table class="table" id="details-table">
                                <thead>
                                    <tr>
                                        <th>Inventory ID</th>
                                        <th>Ref ID</th>
                                        <th>Description</th>
                                        <th>Jumlah Out</th>
                                        <th>
                                            <button type="button" id="add-detail" class="btn btn-sm btn-success">+</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(old('details', $details ?? []) as $i => $d)
                                        <tr>
                                            <td>
                                                <input readonly class="form-control" value="{{ $d->inventory_finish_good_id }}">
                                                <input type="hidden"
                                                       name="details[{{ $i }}][inventory_finish_good_id]"
                                                       value="{{ $d->inventory_finish_good_id }}">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       name="details[{{ $i }}][reference_id]"
                                                       class="form-control"
                                                       value="{{ $d->reference_id }}">
                                            </td>
                                            <td>
                                                <input readonly class="form-control" value="{{ $d->description }}">
                                            </td>
                                            <td>
                                                <input type="number"
                                                       step="0.001"
                                                       name="details[{{ $i }}][quantity_out]"
                                                       class="form-control qty-out"
                                                       value="{{ $d->quantity_out }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-detail">-</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <button type="submit" class="btn btn-primary" id="save-btn">Simpan</button>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </div>

    {{-- Scripts --}}
    @push('scripts')
        <script>
            // Tambah baris detail
            document.getElementById('add-detail').addEventListener('click', () => {
                const tbody = document.querySelector('#details-table tbody');
                const idx = tbody.children.length;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <input readonly class="form-control">
                        <input type="hidden" name="details[\${idx}][inventory_finish_good_id]">
                    </td>
                    <td>
                        <input type="text" name="details[\${idx}][reference_id]" class="form-control">
                    </td>
                    <td>
                        <input readonly class="form-control">
                    </td>
                    <td>
                        <input type="number" step="0.001" name="details[\${idx}][quantity_out]" class="form-control qty-out">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-detail">-</button>
                    </td>
                `;
                tbody.append(row);
                row.querySelector('.remove-detail').addEventListener('click', () => row.remove());
            });

            // Hapus baris detail
            document.addEventListener('click', e => {
                if (e.target.matches('.remove-detail')) {
                    e.target.closest('tr').remove();
                }
            });

            // Validasi total berat vs jumlah out
            document.getElementById('save-btn').addEventListener('click', e => {
                const total = parseFloat(document.getElementById('total-weight').value) || 0;
                const sum = Array.from(document.querySelectorAll('.qty-out'))
                    .reduce((a, i) => a + parseFloat(i.value || 0), 0);
                if (Math.abs(sum - total) > 0.001) {
                    e.preventDefault();
                    alert('Total Jumlah out harus sama dengan Berat total');
                }
            });
        </script>
    @endpush

@endsection
