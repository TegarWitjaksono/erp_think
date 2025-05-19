@extends('dashboard')
@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">List Chat</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="text-center mt-3" id="loading" class="loading" style="display: none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Tersedia
                    </div>
                    <div class="card-body">
                        <table id="tabel-data" class="table datatable table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Nama Murid</th>
                                    <th>File</th>
                                    <th>Pesan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#tabel-data').DataTable({
                ajax: '{{ url('/getAllData/chat') }}',
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    }, // No
                    {
                        data: 'title'
                    },
                    {
                        data: 'nama_murid'
                    },
                    {
                        data: 'file_name',
                        render: function(data) {
                            return data ?
                                `<audio controls src="/chataudio/${data}" style="max-width:150px;"></audio>` :
                                'Tidak ada file';
                        }
                    },
                    {
                        data: 'message'
                    },
                    {
                        data: 'created_at'
                    }
                ]
            })
        });
    </script>
@endsection
