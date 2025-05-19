@extends('depan.layout_after')

@section('konten')
    <!-- Available Classes Section -->
    <div class="container mt-5">
        <div class="row align-items-center mb-4">
            <div class="col-md-9">
                <h3 class="title">Kelas hari ini</h3>
            </div>
        </div>
        <div class="row">
            @if (count($jadwal) > 0)
                @php
                    $displayedJadwal = [];
                @endphp

                @foreach ($jadwal as $jadwal_item)
                    @if (!in_array($jadwal_item->nama_jadwal, $displayedJadwal))
                        @php
                            $displayedJadwal[] = $jadwal_item->nama_jadwal;
                        @endphp
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ $jadwal_item->nama_jadwal }}</h5>
                                    <small class="text-muted">{{ $jadwal_item->nama_kelas }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <p class="card-text">
                                                <i class="fas fa-clock"></i>
                                                @if ($jadwal_item->jam_in && $jadwal_item->jam_out)
                                                    {{ $jadwal_item->jam_in }} - {{ $jadwal_item->jam_out }}<br>
                                                @else
                                                    Waktu belum ditentukan<br>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-5 text-right">
                                            @if ($jadwal_item->id_jadwal)
                                                <a href="{{ url('kelas/detail/' . base64_encode($jadwal_item->id_jadwal)) }}"
                                                    class="btn-midnight">Lihat Detail</a>
                                            @else
                                                <button class="btn-midnight" disabled>Tidak tersedia</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-info">
                        Tidak ada kelas pada hari ini.
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
