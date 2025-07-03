@extends('layouts.user.app')

@section('title', 'Trang chủ')

@section('styles')
<style>
    .sport-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 24px;
    }

    .sport-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .sport-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .sport-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .sport-card-body {
        padding: 16px;
        text-align: center;
    }

    .sport-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        height: 48px;
        line-height: 1.4;
        overflow: hidden;
    }

    .sport-card a {
        display: inline-block;
        margin-top: 12px;
        padding: 8px 20px;
        background: #007bff;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s ease;
    }

    .sport-card a:hover {
        background: #0056b3;
    }

    @media (max-width: 768px) {
        .sport-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .sport-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('mainsection')
<div class="container py-5">
    <h3 class="mb-4 text-center">Chọn Môn Thể Thao</h3>
    <div class="sport-grid">
        @foreach ($sports as $sport)
        <div class="sport-card">
            <img src="{{ asset($sport->icon) }}" alt="{{ $sport->name }}">
            <div class="sport-card-body">
                <div class="sport-card-title">Đặt Sân {{ $sport->name }}</div>
                <a href="/field/sport/{{ $sport->id }}">Đặt sân ngay</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
