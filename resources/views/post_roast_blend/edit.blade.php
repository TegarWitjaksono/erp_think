@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        {{-- Header & Breadcrumbs --}}
        <div class="content-header bg-light border-bottom shadow-sm mb-3">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">
                            <i class="fas fa-edit me-2"></i>
                            Edit Post Roast Blend
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('post-roast-blends.index') }}">Post Roast Blend</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                {{-- Alert Messages --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
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
                    <div class="card-header bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-edit me-1"></i>
                                Edit Post Roast Blend
                            </div>
                            <div>
                                <span class="badge bg-primary">ID: {{ $blend->id }}</span>
                                <span class="badge bg-info">
                                    Created: {{ \Carbon\Carbon::parse($blend->timestamp)->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form method="POST" 
                              action="{{ route('post-roast-blends.update', $blend->id) }}"
                              id="blend-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Left Column --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expired_date" class="form-label">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Expired Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" 
                                               name="expired_date" 
                                               id="expired_date"
                                               class="form-control @error('expired_date') is-invalid @enderror"
                                               value="{{ old('expired_date', $blend->est_expired_date) }}"
                                               required>
                                        @error('expired_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="cupping_score" class="form-label">
                                            <i class="fas fa-star me-1"></i>
                                            Cupping Score
                                        </label>
                                        <input type="number" 
                                               step="0.01" 
                                               min="0" 
                                               max="100"
                                               name="cupping_score" 
                                               id="cupping_score"
                                               class="form-control @error('cupping_score') is-invalid @enderror"
                                               value="{{ old('cupping_score', $blend->cupping_score) }}"
                                               placeholder="0.00 - 100.00">
                                        @error('cupping_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="total_weight" class="form-label">
                                            <i class="fas fa-weight me-1"></i>
                                            Berat Total (kg) <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               step="0.001" 
                                               min="0.001"
                                               id="total-weight" 
                                               name="total_weight" 
                                               class="form-control @error('total_weight') is-invalid @enderror"
                                               value="{{ old('total_weight', $blend->berat_total) }}"
                                               placeholder="0.001"
                                               required>
                                        @error('total_weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <small id="weight-comparison" class="text-muted"></small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">
                                            <i class="fas fa-flag me-1"></i>
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="status" 
                                                id="status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                required>
                                            <option value="close" 
                                                {{ old('status', $blend->status) == 'close' ? 'selected' : '' }}>
                                                Close
                                            </option>
                                            <option value="cancel" 
                                                {{ old('status', $blend->status) == 'cancel' ? 'selected' : '' }}>
                                                Cancel
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Right Column --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="note_flavour" class="form-label">
                                            <i class="fas fa-coffee me-1"></i>
                                            Note Flavour
                                        </label>
                                        <textarea name="note_flavour" 
                                                  id="note_flavour"
                                                  class="form-control @error('note_flavour') is-invalid @enderror" 
                                                  rows="3"
                                                  placeholder="Describe the flavor profile...">{{ old('note_flavour', $blend->note_flavour) }}</textarea>
                                        @error('note_flavour')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">
                                            <i class="fas fa-sticky-note me-1"></i>
                                            Catatan
                                        </label>
                                        <textarea name="catatan" 
                                                  id="catatan"
                                                  class="form-control @error('catatan') is-invalid @enderror" 
                                                  rows="3"
                                                  placeholder="Additional notes...">{{ old('catatan', $blend->catatan) }}</textarea>
                                        @error('catatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Record Info
                                        </label>
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <small class="text-muted">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <strong>Created:</strong><br>
                                                            {{ \Carbon\Carbon::parse($blend->timestamp)->format('d/m/Y H:i:s') }}
                                                        </div>
                                                       
                                                    </div>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Detail Blend Section --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">
                                        <i class="fas fa-list me-2"></i>
                                        Detail Blend Components
                                    </h4>
                                    <button type="button" id="add-detail" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus me-1"></i>
                                        Tambah Item
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="details-table">
                                        <thead class="table-warning">
                                            <tr>
                                                <th width="30%">
                                                    <i class="fas fa-boxes me-1"></i>
                                                    Inventory <span class="text-danger">*</span>
                                                </th>
                                                <th width="15%">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Reference ID
                                                </th>
                                                <th width="25%">
                                                    <i class="fas fa-file-alt me-1"></i>
                                                    Description
                                                </th>
                                                <th width="15%">
                                                    <i class="fas fa-weight-hanging me-1"></i>
                                                    Quantity Out (kg) <span class="text-danger">*</span>
                                                </th>
                                                <th width="10%">
                                                    <i class="fas fa-cogs me-1"></i>
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(old('details', $details) as $i => $d)
                                                <tr>
                                                    <td>
                                                        <select name="details[{{ $i }}][inventorifinishgood_id]" 
                                                                class="form-control inventory-select @error('details.'.$i.'.inventorifinishgood_id') is-invalid @enderror">
                                                            <option value="">-- Pilih Inventory --</option>
                                                            @foreach($inventory as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-description="{{ $item->jenis }} / {{ $item->Id_batch_production }} / {{ $item->expired_date }}"
                                                                    data-stock="{{ ($item->jml_masuk - $item->jml_keluar) }}"
                                                                    {{ (old('inventorifinishgood_id', $d['inventorifinishgood_id'] ?? 
                                                                    '') == $item->id) ? 'selected' : '' }}>


                                                                    {{ $item->id }} – {{ $item->jenis }} 
                                                                    {{ $item->Id_batch_production ? '/ ' . $item->Id_batch_production : '' }}
                                                                    (Stock: {{ number_format($item->jml_masuk - $item->jml_keluar, 3) }} kg)
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('details.'.$i.'.inventorifinishgood_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            name="details[{{ $i }}][reference_id]"
                                                            class="form-control @error('details.'.$i.'.reference_id') is-invalid @enderror"
                                                            value="{{ $d['reference_id'] ?? '' }}"

                                                            placeholder="Optional">
                                                        @error('details.'.$i.'.reference_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            name="details[{{ $i }}][description]"
                                                            class="form-control description-field @error('details.'.$i.'.description') is-invalid @enderror"
                                                            readonly
                                                            value="{{ $d['description'] }}"
                                                            placeholder="Auto-filled">
                                                        @error('details.'.$i.'.description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                            step="0.001"
                                                            min="0.001"
                                                            name="details[{{ $i }}][quantity_out]"
                                                            class="form-control qty-out @error('details.'.$i.'.quantity_out') is-invalid @enderror"
                                                            value="{{ $d['quantity_out'] }}"
                                                            placeholder="0.001">
                                                        @error('details.'.$i.'.quantity_out')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <small class="text-muted stock-info"></small>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-detail">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                {{-- Fallback jika tidak ada detail --}}
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        No details found. Please add at least one item.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="table-secondary">
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total Quantity Out:</strong></td>
                                                <td><strong><span id="total-qty-display">0.000</span> kg</strong></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('post-roast-blends.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Kembali
                                </a>
                                <div>
                                    <button type="button" class="btn btn-info me-2" onclick="debugForm()">
                                        <i class="fas fa-bug me-1"></i>
                                        Debug
                                    </button>
                                    <button type="submit" class="btn btn-warning" id="save-btn">
                                        <i class="fas fa-save me-1"></i>
                                        Update Blend
                                    </button>
                                </div>
                            </div>
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

        // Debug function
        window.debugForm = function() {
            const formData = new FormData(document.getElementById('blend-form'));
            console.log('=== FORM DEBUG INFO ===');
            console.log('Form Action:', document.getElementById('blend-form').action);
            console.log('Form Method:', document.getElementById('blend-form').method);
            
            console.log('Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            
            // Cek validation
            const total = parseFloat(document.getElementById('total-weight')?.value || 0);
            const sum = Array.from(document.querySelectorAll('.qty-out'))
                .reduce((a, inp) => a + parseFloat(inp.value || 0), 0);
            
            console.log('Total Weight:', total);
            console.log('Sum Quantity Out:', sum);
            console.log('Difference:', Math.abs(sum - total));
            
            alert('Debug info printed to console. Check Developer Tools (F12)');
        };

        // Function untuk update total quantity
        function updateTotalQuantity() {
            const total = Array.from(document.querySelectorAll('.qty-out'))
                .reduce((sum, input) => sum + parseFloat(input.value || 0), 0);
            
            document.getElementById('total-qty-display').textContent = total.toFixed(3);
            
            // Update weight comparison
            const totalWeight = parseFloat(document.getElementById('total-weight')?.value || 0);
            const comparison = document.getElementById('weight-comparison');
            if (comparison) {
                const diff = Math.abs(total - totalWeight);
                if (diff > 0.001) {
                    comparison.innerHTML = `<i class="fas fa-exclamation-triangle text-warning"></i> Total qty out: ${total.toFixed(3)} kg (difference: ${diff.toFixed(3)} kg)`;
                    comparison.className = 'text-warning';
                } else {
                    comparison.innerHTML = `<i class="fas fa-check text-success"></i> Total qty out matches total weight`;
                    comparison.className = 'text-success';
                }
            }
        }

        // Function untuk handle inventory selection change
        function onInventoryChange(e) {
            const sel = e.target;
            const option = sel.selectedOptions[0];
            const row = sel.closest('tr');
            
            if (option && option.value) {
                const desc = option.dataset.description || '';
                const stock = parseFloat(option.dataset.stock || 0);
                
                row.querySelector('.description-field').value = desc;
                
                // Update stock info
                const stockInfo = row.querySelector('.stock-info');
                if (stockInfo) {
                    stockInfo.textContent = `Available: ${stock.toFixed(3)} kg`;
                    stockInfo.className = stock > 0 ? 'text-success' : 'text-danger';
                }
            } else {
                row.querySelector('.description-field').value = '';
                const stockInfo = row.querySelector('.stock-info');
                if (stockInfo) {
                    stockInfo.textContent = '';
                }
            }
        }

        // Function untuk validate stock
        function validateStock(qtyInput) {
            const row = qtyInput.closest('tr');
            const inventorySelect = row.querySelector('.inventory-select');
            const selectedOption = inventorySelect.selectedOptions[0];
            
            if (selectedOption && selectedOption.value) {
                const availableStock = parseFloat(selectedOption.dataset.stock || 0);
                const requestedQty = parseFloat(qtyInput.value || 0);
                
                if (requestedQty > availableStock) {
                    qtyInput.classList.add('is-invalid');
                    let feedback = row.querySelector('.stock-invalid-feedback');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback stock-invalid-feedback';
                        qtyInput.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = `Insufficient stock. Available: ${availableStock.toFixed(3)} kg`;
                    return false;
                } else {
                    qtyInput.classList.remove('is-invalid');
                    const feedback = row.querySelector('.stock-invalid-feedback');
                    if (feedback) {
                        feedback.remove();
                    }
                    return true;
                }
            }
            return true;
        }

        // Attach event listeners pada baris existing
        document.querySelectorAll('.inventory-select').forEach(el => {
            el.addEventListener('change', onInventoryChange);
            // Trigger change event untuk existing selected values
            if (el.value) {
                onInventoryChange({ target: el });
            }
        });

        // Attach event listeners untuk quantity inputs
        document.querySelectorAll('.qty-out').forEach(input => {
            input.addEventListener('input', () => {
                validateStock(input);
                updateTotalQuantity();
            });
            input.addEventListener('blur', () => {
                validateStock(input);
            });
        });

        // Event listener untuk total weight input
        const totalWeightInput = document.getElementById('total-weight');
        if (totalWeightInput) {
            totalWeightInput.addEventListener('input', updateTotalQuantity);
        }

        // Event listener untuk tombol remove existing
        document.querySelectorAll('.remove-detail').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tbody = document.querySelector('#details-table tbody');
                const rows = tbody.querySelectorAll('tr');
                
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                    updateRowIndexes();
                    updateTotalQuantity();
                } else {
                    alert('Minimal harus ada 1 detail blend');
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
                <td>
                    <input type="text" name="details[${idx}][reference_id]" class="form-control" placeholder="Optional">
                </td>
                <td>
                    <input type="text" name="details[${idx}][description]" class="form-control description-field" readonly placeholder="Auto-filled">
                </td>
                <td>
                    <input type="number" step="0.001" min="0.001" name="details[${idx}][quantity_out]" class="form-control qty-out" value="0" placeholder="0.001">
                    <small class="text-muted stock-info"></small>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-detail">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);

            // Attach event listeners untuk baris baru
            const newInventorySelect = row.querySelector('.inventory-select');
            const newQtyInput = row.querySelector('.qty-out');
            
            newInventorySelect.addEventListener('change', onInventoryChange);
            newQtyInput.addEventListener('input', () => {
                validateStock(newQtyInput);
                updateTotalQuantity();
            });
            newQtyInput.addEventListener('blur', () => {
                validateStock(newQtyInput);
            });
            
            row.querySelector('.remove-detail').addEventListener('click', (e) => {
                const tbody = document.querySelector('#details-table tbody');
                const rows = tbody.querySelectorAll('tr');
                
                if (rows.length > 1) {
                    row.remove();
                    updateRowIndexes();
                    updateTotalQuantity();
                } else {
                    alert('Minimal harus ada 1 detail blend');
                }
            });
        });

        // Function untuk update index setelah remove
        function updateRowIndexes() {
            const tbody = document.querySelector('#details-table tbody');
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach((row, index) => {
                row.querySelector('.inventory-select').name = `details[${index}][inventorifinishgood_id]`;
                row.querySelector('input[name*="reference_id"]').name = `details[${index}][reference_id]`;
                row.querySelector('.description-field').name = `details[${index}][description]`;
                row.querySelector('.qty-out').name = `details[${index}][quantity_out]`;
            });
        }

        // Form validation dan submit
        const form = document.getElementById('blend-form');
        const saveBtn = document.getElementById('save-btn');
        
        form.addEventListener('submit', (e) => {
            console.log('Form submit triggered');
            
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Blend';
            
            // Validasi total weight vs sum quantity
            const total = parseFloat(document.getElementById('total-weight')?.value || 0);
            const sum = Array.from(document.querySelectorAll('.qty-out'))
                .reduce((a, inp) => a + parseFloat(inp.value || 0), 0);
            
            if (Math.abs(sum - total) > 0.001) {
                e.preventDefault();
                alert(`Total quantity out (${sum.toFixed(3)} kg) tidak sama dengan berat total (${total.toFixed(3)} kg).`);
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

            // Validasi quantity tidak boleh 0 atau kosong untuk inventory yang dipilih
            let hasInvalidQuantity = false;
            document.querySelectorAll('tr').forEach(row => {
                const inventorySelect = row.querySelector('.inventory-select');
                const qtyInput = row.querySelector('.qty-out');
                
                if (inventorySelect && inventorySelect.value && qtyInput) {
                    const qty = parseFloat(qtyInput.value || 0);
                    if (qty <= 0) {
                        hasInvalidQuantity = true;
                    }
                }
            });
            
            if (hasInvalidQuantity) {
                e.preventDefault();
                alert('Semua quantity out untuk inventory yang dipilih harus lebih dari 0!');
                return false;
            }

            // Validasi stock availability
            let stockValid = true;
            document.querySelectorAll('.qty-out').forEach(input => {
                const row = input.closest('tr');
                const inventorySelect = row.querySelector('.inventory-select');
                
                // Hanya validasi jika inventory dipilih
                if (inventorySelect && inventorySelect.value) {
                    if (!validateStock(input)) {
                        stockValid = false;
                    }
                }
            });

            if (!stockValid) {
                e.preventDefault();
                alert('Ada quantity yang melebihi stock tersedia!');
                return false;
            }

            // Show loading state hanya jika validasi berhasil
            console.log('All validation passed, submitting form...');
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
            
            // Set timeout untuk reset button jika ada masalah server
            setTimeout(() => {
                if (saveBtn.disabled) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Blend';
                    console.log('Button reset due to timeout');
                }
            }, 15000); // Reset setelah 15 detik
        });

        // Initial calculations
        updateTotalQuantity();
        
        console.log('Edit form initialized');
    });
</script>

<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .inventory-select {
        font-size: 0.9em;
    }
    
    .stock-info {
        font-size: 0.75em;
        display: block;
        margin-top: 2px;
    }
    
    .card-header {
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }
    
    .table-warning th {
        background-color: #fff3cd !important;
        border-color: #ffeaa7;
    }
    
    .btn-warning {
        background-color: #f39c12;
        border-color: #f39c12;
        color: white;
    }
@endsection