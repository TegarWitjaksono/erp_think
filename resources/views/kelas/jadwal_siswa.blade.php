@extends('depan.layout_after')
@section('konten')
<style>
    .schedule-container {
        background-color: #f4f6f9;
        padding: 50px 0;
        min-height: 100vh;
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .schedule-item {
        background-color: white;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-left: 5px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .schedule-item:hover {
        transform: translateY(-10px);
        border-left-color: #4a90e2;
        box-shadow: 0 15px 30px rgba(74,144,226,0.15);
    }

    .schedule-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, #4a90e2, #50c878);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }

    .schedule-item:hover::before {
        transform: scaleX(1);
        transform-origin: left;
    }

    .schedule-day {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .schedule-name {
        color: #7f8c8d;
        margin-bottom: 15px;
    }

    .schedule-time {
        background-color: #f1f4f8;
        padding: 10px;
        border-radius: 8px;
        display: inline-block;
        color: #4a90e2;
        font-weight: 600;
    }

    .schedule-icon {
        font-size: 2.5rem;
        color: #4a90e2;
        margin-bottom: 15px;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .schedule-item:hover .schedule-icon {
        opacity: 1;
    }

    .page-title {
        text-align: center;
        margin-bottom: 40px;
        position: relative;
        color: #2c3e50;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(to right, #4a90e2, #50c878);
    }
</style>

<div class="schedule-container">
    <div class="container">
        <h2 class="page-title">Jadwal Saya</h2>
        
        @php
            $dayOrder = [
                'Senin' => 1,
                'Selasa' => 2,
                'Rabu' => 3,
                'Kamis' => 4,
                'Jumat' => 5,
                'Sabtu' => 6,
                'Minggu' => 7
            ];
            
            $groupedSchedules = [];
            foreach ($data as $result) {
                $groupedSchedules[$result->hari][] = $result;
            }
            
            // Sort the grouped schedules by day order
            uksort($groupedSchedules, function($a, $b) use ($dayOrder) {
                return ($dayOrder[$a] ?? 999) <=> ($dayOrder[$b] ?? 999);
            });
        @endphp

        <div class="schedule-grid">
            @foreach ($groupedSchedules as $day => $schedules)
                <div class="schedule-item">
                    <div class="schedule-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="schedule-day">{{ $day }}</h3>
                    @foreach ($schedules as $schedule)
                        <p class="schedule-name">{{ $schedule->nama_jadwal }}</p>
                        <div class="schedule-time mb-2">
                            <i class="fas fa-clock me-2"></i>
                            {{ $schedule->jam_in }} - {{ $schedule->jam_out }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection