@extends('layouts.user.app')

@section('title', 'Booking')

@section('styles')
<style>
.image-radio img {
    border: 2px solid transparent;
    cursor: pointer;
    opacity: 0.7;
    transition: 0.3s;
}

.image-radio input[type="radio"]:checked + img {
    border-color: #007bff;
    opacity: 1;
}
</style>
@endsection

@section('mainsection')
<div class="container my-5">
  <h2 class="text-center mb-4">🗓️ Booking Cart</h2>

  <div class="table-responsive">
    <table class="table table-bordered text-center">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>Field ID</th>
          <th>Name</th>
          <th>Time ID</th>
          <th>Date</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="cart-body">
        <!-- Rows injected by JavaScript -->
      </tbody>
    </table>
  </div>

  <div class="row justify-content-end">
    <div class="col-md-4">
      <div class="card border-info">
        <div class="card-body">
          <h5 class="card-title">🧾 Total Summary</h5>
          <p class="card-text d-flex justify-content-between">
            <span>Total:</span>
            <strong id="total-price">0₫</strong>
          </p>
          <label>Phương thức thanh toán</label>
          <div class="row">
            @foreach ($payments as $payment)
            <label class="col-md-6 image-radio">
                <input type="radio" name="payment" value="{{ $payment->id }}" class="d-none">
                <img src="{{ $payment->image }}" class="img-thumbnail">
                <p><small>{{ $payment->name }}</small></p>
            </label>
            @endforeach
          </div>
          <button type="button" id="submit-button" class="btn btn-success btn-block">Checkout</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>

$('#submit-button').on('click', function () {
    const raw = Cookies.get('bookingCart');
    if (!raw) {
        swal('error', 'Lịch đặt trống');
        return;
    }

    try {
        const data = JSON.parse(raw);
        if (!data.selections || data.selections.length === 0) {
            swal('error', 'Chưa có lựa chọn nào trong giỏ');
            return;
        }

        const paymentId = $('input[name="payment"]').val(); // hoặc gán cố định
        if (!paymentId) {
            swal('error', 'Vui lòng chọn phương thức thanh toán');
            return;
        }

        $.ajax({
            url: '/api/checkout',
            method: 'POST',
            data: {
                selections: data.selections,
                payment_id: paymentId,
            },
            success: function (res) {
                swal('success', res.message || 'Đặt sân thành công');
                Cookies.remove('bookingCart'); // Xoá giỏ
                setTimeout(() => location.href = '/lich-su-dat-san', 1500);
            },
            error: function (xhr) {
                let msg = 'Có lỗi xảy ra';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    msg = xhr.responseJSON.error;
                }
                swal('error', msg);
            }
        });

    } catch (e) {
        swal('error', 'Dữ liệu lịch đặt không hợp lệ');
    }
});

  function loadBookingCartFromCookie() {
    const bookingCart = Cookies.get('bookingCart');
    if (bookingCart) {
      try {
        const cartData = JSON.parse(bookingCart);
        return cartData.selections || [];
      } catch (e) {
        console.error('Lỗi khi parse cookie:', e);
        return [];
      }
    }
    return [];
  }

  function formatCurrency(amount) {
    return Number(amount).toLocaleString('vi-VN') + '₫';
  }

  function renderCartTable() {
    const selections = loadBookingCartFromCookie();
    const tbody = document.getElementById('cart-body');
    const totalEl = document.getElementById('total-price');
    tbody.innerHTML = '';
    let total = 0;

    selections.forEach((item, index) => {
      const row = document.createElement('tr');

      const price = parseFloat(item.fieldPrice) * (1 + (parseFloat(item.timeExrate) || 0)/100);
      total += price;

      row.innerHTML = `
        <td>${index + 1}</td>
        <td>${item.fieldId}</td>
        <td>Sân ${item.name} <br> <small>${item.type}</small></td>
        <td>${item.startHour}:00 - ${item.endHour}:00</td>
        <td>${item.date}</td>
        <td>${formatCurrency(price)}</td>
        <td><button class="btn btn-sm btn-danger" onclick="removeCartItem(${index})">Remove</button></td>
      `;

      tbody.appendChild(row);
    });

    totalEl.textContent = formatCurrency(total);
  }

  function removeCartItem(index) {
    const bookingCart = Cookies.get('bookingCart');
    if (!bookingCart) return;

    const cartData = JSON.parse(bookingCart);
    if (!Array.isArray(cartData.selections)) return;

    cartData.selections.splice(index, 1);
    Cookies.set('bookingCart', JSON.stringify(cartData), { expires: 7 });
    renderCartTable();
  }

  document.addEventListener('DOMContentLoaded', renderCartTable);
</script>
@endsection