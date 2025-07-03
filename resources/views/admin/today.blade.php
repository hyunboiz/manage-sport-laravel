@extends('layouts.admin.app')

@section('title', 'Quản lý danh sách admin')

@section('styles')

@endsection

@section('mainsection')
<style>
    .schedule-grid {
        display: grid;
        grid-template-columns: 120px repeat({{ count($fields) }}, 1fr);
        border: 1px solid #dee2e6;
    }

    .grid-cell {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: center;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .grid-header {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .grid-booked {
        background-color: #ffc10733;
        color: #856404;
        font-weight: 500;
    }

    .grid-empty {
        background-color: #d4edda;
        color: #155724;
        font-weight: 500;
    }

    .time-label {
        background-color: #f1f1f1;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .schedule-grid {
            grid-template-columns: 100px repeat({{ count($fields) }}, 1fr);
        }
    }
</style>

<div class="container mt-4">
    <h2 class="mb-4">Lịch đặt sân hôm nay ({{ $today }})</h2>

    <div class="schedule-grid mb-5">
        {{-- Header Row --}}
        <div class="grid-cell grid-header">Khung giờ</div>
        @foreach ($fields as $field)
            <div class="grid-cell grid-header">{{ $field->sport->name }} {{ $field->id }}</div>
        @endforeach

        {{-- Body Rows --}}
        @foreach ($timeFrames as $time)
            <div class="grid-cell time-label">{{ $time->start }}:00 - {{ $time->end }}:00</div>

            @foreach ($fields as $field)
                @php
                    $bookingDetail = null;
                    foreach ($bookings as $booking) {
                        $match = $booking->bookingDetails->first(fn($d) =>
                            $d->field_id == $field->id &&
                            $d->time_id == $time->id &&
                            $d->date_book == $today
                        );
                        if ($match) {
                            $bookingDetail = $match;
                            $customerName = $booking->customer->name ?? 'Khách';
                            $customerPhone = $booking->customer->hotline ?? '';
                            break;
                        }
                    }
                @endphp

                @if ($bookingDetail)
                <a href="/admin/detail/{{ $booking->id }}">
                    <div class="grid-cell grid-booked">
                        <div>
                            <strong>{{ $customerName }}</strong>
                            <p>{{ $customerPhone }}</p>
                        </div>
                    </div>
                </a>
                @else       
                    <div class="grid-cell grid-empty">Trống</div>
                @endif
            @endforeach
        @endforeach
    </div>
</div>
@endsection

@section('scripts')

@endsection

