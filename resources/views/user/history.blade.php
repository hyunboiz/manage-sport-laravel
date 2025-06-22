@extends('layouts.user.app')

@section('title', 'Lịch sử đặt sân')

@section('styles')
 <style>
    body {
      background-color: #ffffff;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar-custom {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    }

    .navbar-custom .nav-link {
      color: #333;
      font-weight: 500;
      margin-right: 1rem;
    }

    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link.active {
      color: #007BFF;
      border-bottom: 2px solid #007BFF;
    }

    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #e0e0e0;
      margin-bottom: 1rem;
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .form-control:focus {
      border-color: #00bcd4;
      box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.25);
    }

    .profile-section {
      max-width: 800px;
      margin: 0 auto;
      padding-top: 3rem;
    }
  </style>
@endsection

@section('mainsection')
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-primary" href="#">Trang cá nhân</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                <a class="nav-link" href="{{ route('user.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> Thông tin</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" href="{{ route('user.history') }}">Lịch sử</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('user.password') }}">Đổi mật khẩu</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Logout</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

<!-- Profile Section -->
<div class="container profile-section">
  <div class="text-center">
    <img src="{{ asset('theme_user/img/user.png') }}" alt="Avatar" class="profile-img">
    <h4 class="mb-0">{{ optional(Auth::guard('web')->user())->name }}</h4>
    <p class="text-muted">{{ optional(Auth::guard('web')->user())->email }}</p>
  </div>

  <hr class="my-4">

<h5 class="text-center mb-3">🕓 Lịch sử đặt sân</h5>

@if ($bookings->isEmpty())
    <div class="alert alert-info text-center">Bạn chưa có đơn đặt sân nào.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered table-hover history">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Ngày</th>
                    <th>Chi tiết</th>
                    <th>Loại thanh toán</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
               @foreach ($bookings as $booking)
                <tr>
                    <td>{{$booking->id}}</td>
                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @foreach ($booking->bookingDetails as $detail)
                            <div>
                                <strong>{{ $detail->field->sport->name }} - {{ $detail->field->type->name }}</strong><br>
                                Sân: {{ $detail->field->id }}<br>
                                Giờ: {{ $detail->timeframe->start }}:00 - {{ $detail->timeframe->end }}:00<br>
                            </div>
                        @endforeach
                    </td>
                    <td>{{ $booking->paymentMethod->name ?? 'Chưa rõ' }}</td>
                    <td>{{ number_format($booking->total, 0, ',', '.') }} đ</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif


</div>
@endsection

@section('scripts')


@endsection

