<div class="form-group">
  <label for="penerimaan_id">Penerimaan</label>
  <select name="penerimaan_id" id="penerimaan_id" class="form-control">
    @foreach($penerimaanList as $id => $nama)
      <option value="{{ $id }}" {{ old('penerimaan_id',$inventoryBahanBaku->penerimaan_id ?? '')==$id?'selected':'' }}>{{ $nama }}</option>
    @endforeach
  </select>
</div>
<div class="form-group">
  <label for="kadar_air">Kadar Air</label>
  <input type="number" step="0.01" name="kadar_air" id="kadar_air" class="form-control" value="{{ old('kadar_air',$inventoryBahanBaku->kadar_air ?? '') }}">
</div>
<div class="form-group">
  <label for="bulk_density">Bulk Density</label>
  <input type="number" step="0.001" name="bulk_density" id="bulk_density" class="form-control" value="{{ old('bulk_density',$inventoryBahanBaku->bulk_density ?? '') }}">
</div>
<div class="form-group">
  <label for="qty_masuk">Qty Masuk</label>
  <input type="number" step="0.001" name="qty_masuk" id="qty_masuk" class="form-control" value="{{ old('qty_masuk',$inventoryBahanBaku->qty_masuk ?? '') }}">
</div>
<div class="form-group">
  <label for="expired_date">Expired Date</label>
  <input type="date" name="expired_date" id="expired_date" class="form-control" value="{{ old('expired_date',$inventoryBahanBaku->expired_date ?? '') }}">
</div>
<div class="form-group">
  <label for="catatan">Catatan</label>
  <textarea name="catatan" id="catatan" class="form-control">{{ old('catatan',$inventoryBahanBaku->catatan ?? '') }}</textarea>
</div>
