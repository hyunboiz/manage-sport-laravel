@extends('layouts.admin.app')

@section('title', 'Dashboard Admin')

@section('styles')
<style>
  .dashboard-card {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
  }

  .dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .dashboard-icon {
    font-size: 2rem;
    opacity: 0.85;
  }

  .dashboard-title {
    font-size: 0.875rem;
    color: #eee;
  }

  .dashboard-value {
    font-size: 1.75rem;
    font-weight: bold;
    color: #fff;
  }
</style>
@endsection

@section('mainsection')
<div class="row">
    <div class="col-md-12">
        <div class="d-md-flex align-items-center mb-3 mx-2">
        <div class="mb-md-0 mb-3">
            <h3 class="font-weight-bold mb-0">Hello, {{ optional(Auth::guard('admin')->user())->name }}</h3>
            <p class="mb-0">Welcome Back</p>
        </div>
        <button type="button" class="btn btn-sm btn-white btn-icon d-flex align-items-center mb-0 ms-md-auto mb-sm-0 mb-2 me-2">
             <span class="btn-inner--icon">
            <i class="fa fa-share-square-o" aria-hidden="true"></i>
            </span>
            <a href="/admin/logout" class="btn-inner--text">Đăng xuất</a>
        </button>
        </div>
    </div>
</div>
<div class="container py-4">

  {{-- Filter thời gian --}}
  <div class="d-flex justify-content-end mb-3">
    <form method="get">
      <select name="range" class="form-select" onchange="this.form.submit()">
        <option value="today" {{ $range == 'today' ? 'selected' : '' }}>Hôm nay</option>
        <option value="7days" {{ $range == '7days' ? 'selected' : '' }}>7 ngày</option>
        <option value="1month" {{ $range == '1month' ? 'selected' : '' }}>1 tháng</option>
        <option value="all" {{ $range == 'all' ? 'selected' : '' }}>Tổng toàn bộ</option>
      </select>
    </form>
  </div>
  @php
  $rangeLabel = match($range) {
    'today' => 'hôm nay',
    '7days' => '7 ngày',
    '1month' => '1 tháng',
    default => 'toàn bộ',
  };
@endphp
  {{-- Thống kê chính --}}
  <div class="row g-4">
    <x-dashboard-card bg="primary" icon="calendar-check-o" title="Lượt đặt" :value="$totalBookings" />
    <x-dashboard-card bg="success" icon="users" title="Khách hàng" :value="$totalCustomers" />
    <x-dashboard-card bg="warning" icon="money" title="Doanh thu" :value="number_format($totalRevenue) . ' đ'" />
    <x-dashboard-card bg="danger" icon="ban" title="Tỷ lệ huỷ" :value="$cancelRate . '%'" />
    <x-dashboard-card bg="secondary" icon="map-marker" title="Sân trống hôm nay" :value="$emptyFieldsToday" />
    <x-dashboard-card bg="info" icon="clock-o" title="Tổng giờ đã đặt ({{ $rangeLabel }})" :value="$bookedHours . ' giờ'" />
  </div>

  {{-- Biểu đồ --}}
 <div class="row mt-5 g-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-line-chart"></i>
        <strong> Doanh thu theo ngày – {{ $rangeLabel }}</strong>
      </div>
      <div class="card-body">
        <canvas id="revenueChart" height="200"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-clock-o"></i>
        <strong> Đặt sân theo khung giờ – {{ $rangeLabel }}</strong>
      </div>
      <div class="card-body">
        <canvas id="timeChart" height="200"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-soccer-ball-o"></i>
        <strong> Đặt theo loại sân – {{ $rangeLabel }}</strong>
      </div>
      <div class="card-body">
        <canvas id="typeChart" height="200"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-pie-chart"></i>
        <strong> Tỷ lệ lấp đầy sân theo ngày – {{ $rangeLabel }}</strong>
      </div>
      <div class="card-body">
        <canvas id="fillRateChart" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: {!! json_encode($revenuePerDay->keys()) !!},
      datasets: [{
        label: 'Doanh thu',
        data: {!! json_encode($revenuePerDay->values()) !!},
        backgroundColor: 'rgba(0,123,255,0.2)',
        borderColor: 'rgba(0,123,255,1)',
        fill: true, tension: 0.3
      }]
    }
  });

  new Chart(document.getElementById('timeChart'), {
    type: 'bar',
    data: {
      labels: {!! json_encode($bookingsPerTime->map(fn($b) => $b->timeFrame->start . '-' . $b->timeFrame->end)) !!},
      datasets: [{
        label: 'Lượt đặt',
        data: {!! json_encode($bookingsPerTime->pluck('total')) !!},
        backgroundColor: 'rgba(40,167,69,0.6)'
      }]
    }
  });

  new Chart(document.getElementById('typeChart'), {
    type: 'pie',
    data: {
      labels: {!! json_encode($bookingsPerType->keys()) !!},
      datasets: [{
        data: {!! json_encode($bookingsPerType->values()) !!},
        backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545']
      }]
    }
  });

  new Chart(document.getElementById('fillRateChart'), {
    type: 'line',
    data: {
      labels: {!! json_encode($fillRate->keys()) !!},
      datasets: [{
        label: 'Tỷ lệ lấp đầy (%)',
        data: {!! json_encode($fillRate->values()) !!},
        backgroundColor: 'rgba(255,193,7,0.2)',
        borderColor: 'rgba(255,193,7,1)',
        fill: true, tension: 0.3
      }]
    },
    options: { scales: { y: { beginAtZero: true, max: 100 } } }
  });
</script>

@endsection

