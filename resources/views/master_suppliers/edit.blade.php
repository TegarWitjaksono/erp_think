<!-- View: jurusan.edit -->
@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Suppliers</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Edit Suppliers
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_suppliers.update', $data->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama_jurusan">Name Suppliers</label>
                                <input type="text" name="name" class="form-control" value="{{ $data->name }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="nama_jurusan">Contact Info</label>
                                <input type="text" name="contact_info" class="form-control"
                                    value="{{ $data->contact_info }}" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-coffee">Simpan</button>
                                <a href="{{ route('master_suppliers.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
