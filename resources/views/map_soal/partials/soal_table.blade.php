@php
    function renderMedia($content)
    {
        $ext = strtolower(pathinfo($content, PATHINFO_EXTENSION));

        if (strpos($content, 'uploads/') !== false) {
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                return '<img src="' . asset($content) . '" alt="Gambar" width="100" class="img-fluid d-block mx-auto">';
            }

            if (in_array($ext, ['mp3', 'wav', 'ogg'])) {
                return '<audio controls class="d-block mx-auto" style="max-width: 200px;">
                            <source src="' .
                    asset($content) .
                    '" type="audio/' .
                    $ext .
                    '">
                            Browser tidak mendukung pemutar audio.
                        </audio>';
            }

            if ($ext === 'pdf') {
                return '<a href="' .
                    asset($content) .
                    '" target="_blank" class="btn btn-sm btn-outline-primary d-block mx-auto">
                            <i class="fas fa-file-pdf"></i> Lihat Dokumen
                        </a>';
            }

            return '<a href="' . asset($content) . '" target="_blank">Lihat File</a>';
        }

        return '<p class="m-0">' . e($content) . '</p>';
    }
@endphp
@foreach ($soals as $soal)
    <tr data-id="{{ $soal->id_soal }}">
        <td>{!! renderMedia($soal->soal) !!}</td>
        <td>{!! renderMedia($soal->pilihan_1) !!}</td>
        <td>{!! renderMedia($soal->pilihan_2) !!}</td>
        <td>{!! renderMedia($soal->pilihan_3) !!}</td>
        <td>{!! renderMedia($soal->pilihan_4) !!}</td>
        <td>{{ $soal->jawaban }}</td>
        <td>
            <button type="button" class="btn btn-success add-soal">
                <i class="fas fa-plus"></i>
            </button>
        </td>
    </tr>
@endforeach
