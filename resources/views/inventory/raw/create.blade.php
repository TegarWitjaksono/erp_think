@extends('layouts.app')
@section('content')
<h1>Tambah Inventory Bahan Baku</h1>
<form action="{{ route('inventory.raw.store') }}" method="POST">
  @csrf
  @include('inventory.raw._form')
  <button class="btn btn-success">Simpan</button>
  <a href="{{ route('inventory.raw.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection

// resources/views/inventory/raw/edit.blade.php
@extends('layouts.app')
@section('content')
<h1>Edit Inventory Bahan Baku</h1>
<form action="{{ route('inventory.raw.update',$inventoryBahanBaku) }}" method="POST">
  @csrf @method('PUT')
  @include('inventory.raw._form')
  <button class="btn btn-primary">Update</button>
  <a href="{{ route('inventory.raw.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection