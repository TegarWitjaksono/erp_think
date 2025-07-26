@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        {{-- Header & Breadcrumbs (sama seperti sebelumnya) --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <!-- ... -->
        </div>

        <section class="content">
            <div class="container-fluid">
                 @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Validation Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card shadow-sm">
                    <div class="card-header text-white">
                        <i class="fas fa-table me-1"></i>
                        {{ isset($blend) ? 'Edit' : 'Tambah' }} Post Roast Blend
                    </div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{route('post-roast-blends.store')}}">
                            @csrf
                          

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
                                    @forelse(old('details', $details ?? []) as $i => $d)
                                        <tr>
                                            <td>
                                                <select name="details[{{ $i }}][inventorifinishgood_id]" class="form-control inventory-select">
                                                    <option value="">-- Pilih Inventory --</option>
                                                    @foreach($inventory as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-description="{{ $item->jenis }} / {{ $item->Id_batch_production }} / {{ $item->expired_date }}"
                                                            {{ (is_array($d) ? ($d['inventorifinishgood_id'] ?? '') : ($d->inventorifinishgood_id ?? '')) == $item->id ? 'selected' : '' }}>
                                                            {{ $item->id }} – {{ $item->jenis }} {{ $item->Id_batch_production ? '/ ' . $item->Id_batch_production : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="details[{{ $i }}][reference_id]"
                                                    class="form-control"
                                                    value="{{ is_array($d) ? ($d['reference_id'] ?? '') : ($d->reference_id ?? '') }}">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="details[{{ $i }}][description]"
                                                    class="form-control description-field"
                                                    readonly
                                                    value="{{ is_array($d) ? ($d['description'] ?? '') : ($d->description ?? '') }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01"
                                                    name="details[{{ $i }}][quantity_out]"
                                                    class="form-control qty-out"
                                                    value="{{ is_array($d) ? ($d['quantity_out'] ?? 0) : ($d->quantity_out ?? 0) }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-detail">-</button>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Baris kosong jika tidak ada data --}}
                                        <tr>
                                            <td>
                                                <select name="details[0][inventorifinishgood_id]" class="form-control inventory-select">
                                                    <option value="">-- Pilih Inventory --</option>
                                                    @foreach($inventory as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-description="{{ $item->jenis }} / {{ $item->Id_batch_production }} / {{ $item->expired_date }}">
                                                            {{ $item->id }} – {{ $item->jenis }} {{ $item->Id_batch_production ? '/ ' . $item->Id_batch_production : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="details[0][reference_id]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="details[0][description]" class="form-control description-field" readonly>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="details[0][quantity_out]" class="form-control qty-out" value="0">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-detail">-</button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>




                            <button type="submit" class="btn btn-primary" id="save-btn">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const firstSelect = document.querySelector('.inventory-select');
        if (!firstSelect) return;
        const optionsHTML = firstSelect.innerHTML;

        function onInventoryChange(e) {
            const sel = e.target;
            const desc = sel.selectedOptions[0]?.dataset.description || '';
            sel.closest('tr').querySelector('.description-field').value = desc;
        }

        // Pasang listener pada baris existing
        document.querySelectorAll('.inventory-select').forEach(el => {
            el.addEventListener('change', onInventoryChange);
            // Trigger change event untuk existing selected values
            if (el.value) {
                onInventoryChange({ target: el });
            }
        });

        // Event listener untuk tombol remove existing
        document.querySelectorAll('.remove-detail').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tbody = document.querySelector('#details-table tbody');
                const rows = tbody.querySelectorAll('tr');
                
                // Jangan hapus jika hanya ada 1 baris
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                    // Update index setelah remove
                    updateRowIndexes();
                } else {
                    // Reset baris terakhir jika hanya ada 1
                    const row = e.target.closest('tr');
                    row.querySelector('.inventory-select').value = '';
                    row.querySelector('input[name*="reference_id"]').value = '';
                    row.querySelector('.description-field').value = '';
                    row.querySelector('.qty-out').value = '0';
                }
            });
        });

        // Event listener untuk tombol add
        document.getElementById('add-detail').addEventListener('click', () => {
            const tbody = document.querySelector('#details-table tbody');
            const idx = tbody.querySelectorAll('tr').length;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="details[${idx}][inventorifinishgood_id]" class="form-control inventory-select">
                        ${optionsHTML}
                    </select>
                </td>
                <td><input type="text" name="details[${idx}][reference_id]" class="form-control"></td>
                <td><input type="text" name="details[${idx}][description]" class="form-control description-field" readonly></td>
                <td><input type="number" step="0.01" name="details[${idx}][quantity_out]" class="form-control qty-out" value="0"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-detail">-</button></td>
            `;
            tbody.appendChild(row);

            // Attach event listeners untuk baris baru
            row.querySelector('.inventory-select').addEventListener('change', onInventoryChange);
            row.querySelector('.remove-detail').addEventListener('click', (e) => {
                const tbody = document.querySelector('#details-table tbody');
                const rows = tbody.querySelectorAll('tr');
                
                if (rows.length > 1) {
                    row.remove();
                    updateRowIndexes();
                }
            });
        });

        // Function untuk update index setelah remove
        function updateRowIndexes() {
            const tbody = document.querySelector('#details-table tbody');
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach((row, index) => {
                // Update name attributes
                row.querySelector('.inventory-select').name = `details[${index}][inventorifinishgood_id]`;
                row.querySelector('input[name*="reference_id"]').name = `details[${index}][reference_id]`;
                row.querySelector('.description-field').name = `details[${index}][description]`;
                row.querySelector('.qty-out').name = `details[${index}][quantity_out]`;
            });
        }

        // Validation sebelum submit
        const saveBtn = document.getElementById('save-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', (e) => {
                const total = parseFloat(document.getElementById('total-weight')?.value || 0);
                const sum = Array.from(document.querySelectorAll('.qty-out'))
                    .reduce((a, inp) => a + parseFloat(inp.value || 0), 0);
                
                if (Math.abs(sum - total) > 0.001) {
                    e.preventDefault();
                    alert(`Jumlah total out (${sum.toFixed(2)}) tidak sama dengan berat total (${total.toFixed(2)}).`);
                    return false;
                }

                // Validasi minimal ada 1 inventory dipilih
                const selectedInventories = Array.from(document.querySelectorAll('.inventory-select'))
                    .filter(sel => sel.value !== '');
                
                if (selectedInventories.length === 0) {
                    e.preventDefault();
                    alert('Minimal pilih 1 inventory!');
                    return false;
                }

                // Validasi quantity tidak boleh 0 atau kosong
                const invalidQuantities = Array.from(document.querySelectorAll('.qty-out'))
                    .filter(inp => parseFloat(inp.value || 0) <= 0);
                
                if (invalidQuantities.length > 0) {
                    e.preventDefault();
                    alert('Jumlah quantity out harus lebih dari 0!');
                    return false;
                }
            });
        }
    });
</script>

   
@endsection
