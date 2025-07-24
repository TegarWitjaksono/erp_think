@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        {{-- Header & Breadcrumbs (sama seperti sebelumnya) --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <!-- ... -->
        </div>

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

                            {{-- Field-field utama --}}
                            <div class="mb-3">
                                <label>Expired Date</label>
                                <input type="date" name="expired_date" class="form-control"
                                       value="{{ old('expired_date', $blend->expired_date ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label>Cupping Score</label>
                                <input type="number" step="0.01" name="cupping_score" class="form-control"
                                       value="{{ old('cupping_score', $blend->cupping_score ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label>Note Flavour</label>
                                <textarea name="note_flavour" class="form-control">{{ old('note_flavour', $blend->note_flavour ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Catatan</label>
                                <textarea name="catatan" class="form-control">{{ old('catatan', $blend->catatan ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Berat Total</label>
                                <input type="number" step="0.001" id="total-weight" name="total_weight" class="form-control"
                                       value="{{ old('total_weight', $blend->total_weight ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="close"  {{ old('status', $blend->status ?? '')=='close'  ? 'selected':'' }}>Close</option>
                                    <option value="cancel" {{ old('status', $blend->status ?? '')=='cancel'? 'selected':'' }}>Cancel</option>
                                </select>
                            </div>

                            {{-- Detail Blend --}}
                            <h4 class="mt-4">Detail Blend</h4>
                            <table class="table" id="details-table">
                                <thead>
                                    <tr>
                                        <th>Inventory</th>
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
                                                <select name="details[{{ $i }}][inventorifinishgood_id]" class="form-control inventory-select">
                                                    <option value="">-- Pilih Inventory --</option>
                                                    @foreach($inventory as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-description="{{ $item->bean }} / {{ $item->level_roast }} / {{ $item->note_flavour }}"
                                                            {{ ($d->inventorifinishgood_id??'') == $item->id ? 'selected':'' }}>
                                                            {{ $item->id }} – {{ $item->bean }} / {{ $item->level_roast }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       name="details[{{ $i }}][reference_id]"
                                                       class="form-control"
                                                       value="{{ $d->reference_id }}">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       name="details[{{ $i }}][description]"
                                                       class="form-control description-field"
                                                       readonly
                                                       value="{{ $d->description }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.001"
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Ambil opsi dari select pertama sebagai template
                const firstSelect = document.querySelector('.inventory-select');
                if (!firstSelect) return;
                const optionsHTML = firstSelect.innerHTML;

                // Handler change untuk mengisi description
                function onInventoryChange(e) {
                    const sel = e.target;
                    const desc = sel.selectedOptions[0]?.dataset.description || '';
                    sel.closest('tr').querySelector('.description-field').value = desc;
                }

                // Pasang listener pada baris existing
                document.querySelectorAll('.inventory-select').forEach(el => {
                    el.addEventListener('change', onInventoryChange);
                    if (el.value) {
                        // trigger sekali untuk mengisi field jika sudah ada value
                        onInventoryChange({ target: el });
                    }
                });

                // Tambah baris baru
                document.getElementById('add-detail').addEventListener('click', () => {
                    const tbody = document.querySelector('#details-table tbody');
                    const idx = tbody.children.length;
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <select name="details[${idx}][inventorifinishgood_id]" class="form-control inventory-select">
                                ${optionsHTML}
                            </select>
                        </td>
                        <td><input type="text" name="details[${idx}][reference_id]" class="form-control"></td>
                        <td><input type="text" name="details[${idx}][description]" class="form-control description-field" readonly></td>
                        <td><input type="number" step="0.001" name="details[${idx}][quantity_out]" class="form-control qty-out"></td>
                        <td><button type="button" class="btn btn-sm btn-danger remove-detail">-</button></td>
                    `;
                    tbody.append(row);
                    // pasang listener
                    row.querySelector('.remove-detail').addEventListener('click', () => row.remove());
                    row.querySelector('.inventory-select').addEventListener('change', onInventoryChange);
                });

                // Hapus baris (backup handler)
                document.addEventListener('click', e => {
                    if (e.target.matches('.remove-detail')) {
                        e.target.closest('tr').remove();
                    }
                });

                // Validasi total berat vs jumlah out
                document.getElementById('save-btn').addEventListener('click', e => {
                    const total = parseFloat(document.getElementById('total-weight').value) || 0;
                    const sum = Array.from(document.querySelectorAll('.qty-out'))
                        .reduce((a, inp) => a + parseFloat(inp.value || 0), 0);
                    if (Math.abs(sum - total) > 0.001) {
                        e.preventDefault();
                        alert('Total Jumlah out harus sama dengan Berat total');
                    }
                });
            });
        </script>
    @endpush
@endsection
