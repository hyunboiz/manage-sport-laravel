<h2>Đơn đặt sân mới</h2>
<p>Khách hàng: {{ $booking->customer->name }}</p>
<p>Email: {{ $booking->customer->email }}</p>
<p>Ngày đặt: {{ $booking->created_at }}</p>

<ul>
@foreach($booking->bookingDetails  as $detail)
  <li>
    Sân: {{ $detail->field->type->name }} -
    Ngày: {{ $detail->date_book }} -
    Giờ: {{ $detail->timeFrame->start }} - {{ $detail->timeFrame->end }}
  </li>
@endforeach
</ul>

<p>Phương thức thanh toán: {{ $booking->paymentMethod->name }}</p>
