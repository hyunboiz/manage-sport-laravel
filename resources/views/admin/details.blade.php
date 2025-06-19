@extends('layouts.admin.app')

@section('title', 'Danh sách chi tiết đặt sân')

@section('mainsection')
<div class="row">
  <div class="col-12">
    <div class="card border shadow-xs mb-4">
      <div class="card-header border-bottom pb-0">
        <div class="d-sm-flex align-items-center">
          <div>
            <h6 class="font-weight-semibold text-lg mb-0">Booking Details</h6>
            <p class="text-sm">Chi tiết các lượt đặt sân</p>
          </div>
        </div>
      </div>
      <div class="card-body px-0 py-0">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead class="bg-gray-100">
              <tr>
                <th class="text-secondary text-xs font-weight-semibold opacity-7">Booking ID</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Field</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Sport</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Type</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Date</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Time</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Price</th>
                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Created At</th>
              </tr>
            </thead>
            <tbody>
              @foreach($details as $detail)
              <tr>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $detail->booking_id }}</p>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $detail->field->id ?? 'N/A' }}</p>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $detail->field->sport->name ?? 'N/A' }}</p>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $detail->field->type->name ?? 'N/A' }}</p>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $detail->date_book }}</p>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $detail->timeFrame->start }}h - {{ $detail->timeFrame->end }}h</p>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ number_format($detail->price) }} đ</p>
                </td>
                <td class="align-middle text-center">
                  <span class="text-secondary text-sm font-weight-normal">{{ $detail->created_at }}</span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
