@extends('layouts.user.app')

@section('title', 'Đặt sân')

@section('styles')
<style>
    /* General Header Styles */
    .app-header {
        background-color: #004d40; /* Màu xanh đậm như hình */
        color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .app-header .back-arrow {
        color: white;
        font-size: 1.5rem;
    }

    .app-header .date-picker-container {
        position: relative;
        width: 150px; /* Adjust as needed */
    }

    .app-header .date-picker {
        color: rgb(0, 0, 0);
        border: none;
        text-align: center;
        cursor: pointer;
    }

    .app-header .date-picker:focus {
        box-shadow: none;
    }

    .app-header .calendar-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        pointer-events: none; /* Allows click through to input */
    }

    /* Legend Section */
    .legend-section {
        background-color: #e0f2f1; /* Nền xanh nhạt */
        padding: 10px 15px;
        border-radius: .25rem;
        font-size: 0.9rem;
        display: flex;
        flex-wrap: wrap;
    }

    .legend-box {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 3px;
        vertical-align: middle;
        margin-right: 5px;
    }

    .legend-box.status-free {
        background-color: #fff;
        border: 1px solid #ced4da;
    }
    .legend-box.status-booked { background-color: #dc3545; } /* Red */
    .legend-box.status-selected { background-color: #007bff; } /* Blue */
    .legend-box.status-pending { background-color: #ffc107; } /* Yellow */
    .legend-box.status-event { background-color: #ff07ea; }
    .legend-box.status-locked { background-color: #c4c0c3; }

    /* Table Specific Styles */
    .schedule-table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse; /* Đảm bảo các border không bị nhân đôi */
    }

    .schedule-table th,
    .schedule-table td {
        padding: 0.75rem; /* Padding mặc định */
        vertical-align: middle;
        border: 1px solid #dee2e6;
        text-align: center;
        height: 40px; /* Chiều cao cố định cho ô */
        box-sizing: border-box; /* Tính cả padding và border vào kích thước */
    }

    .schedule-table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        background-color: #e9ecef; /* Màu nền cho header */
    }

    .schedule-table tbody tr:last-child td {
        border-bottom: 1px solid #dee2e6; /* Đảm bảo border cuối cùng của table */
    }

    /* Make table responsive by wrapping in .table-responsive */
    .table-responsive {
        overflow-x: auto;
    }

    /* Sticky header column (for "Sân A, B, C...") */
    .sticky-header-col {
        position: sticky;
        left: 0;
        background-color: #e9ecef; /* Giữ màu nền header */
        z-index: 10; /* Đảm bảo nó nằm trên khi cuộn ngang */
        border-right: 1px solid #dee2e6;
    }

    /* Styling for selectable cells */
    .schedule-table td {
        background-color: #f8f9fa; /* Màu nền mặc định cho ô trống */
        cursor: pointer; /* Biểu tượng con trỏ để chỉ ra có thể click */
        transition: background-color 0.2s ease-in-out; /* Hiệu ứng chuyển màu mượt mà */
    }

    /* Status: Booked (Đã đặt) */
    .schedule-table td[data-status="booked"] {
        background-color: #dc3545; /* Red */
        cursor: not-allowed; /* Không cho phép click */
    }

    /* Status: Pending (Chờ TT) */
    .schedule-table td[data-status="booked-pending"] {
        background-color: #ffc107; /* Yellow */
        cursor: not-allowed; /* Có thể vẫn không cho chọn hoặc cho phép */
    }

    /* Status: Selected (Đang chọn) */
    .schedule-table td.status-selected {
        background-color: #007bff; /* Primary Blue */
        color: white; /* Đổi màu chữ cho dễ nhìn */
    }

    /* Hover effect for selectable cells (empty cells) */
    .schedule-table td[data-status="free"]:hover {
        background-color: #e2e6ea; /* Light grey on hover */
    }
    /* Previous CSS styles go here */

    /* Fixed Bottom Bar */
    .fixed-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #004d40; /* Màu xanh đậm như header */
        color: white;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.2);
        z-index: 1000; /* Đảm bảo nó nằm trên cùng */
        padding: 10px 15px; /* Padding cho thanh */
        /* .d-none class của Bootstrap sẽ ẩn nó mặc định, JS sẽ remove class này */
    }

    .fixed-bottom-bar .total-info span {
        font-size: 1.1rem;
        font-weight: bold;
    }

    .fixed-bottom-bar .next-button {
        background-color: #f7b200; /* Màu vàng như hình */
        color: white;
        font-weight: bold;
        padding: 10px 30px;
        border: none;
        border-radius: 5px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .fixed-bottom-bar .next-button:hover {
        background-color: #e0a800; /* Màu vàng đậm hơn khi hover */
    }
    .fixed-bottom-bar .submit-button {
        background-color: #f70800; /* Màu vàng như hình */
        color: white;
        font-weight: bold;
        padding: 10px 30px;
        border: none;
        border-radius: 5px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .fixed-bottom-bar .submit-button:hover {
        background-color: #ff0000; /* Màu vàng đậm hơn khi hover */
    }
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
        swal('error', 'Giỏ hàng trống');
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
        swal('error', 'Dữ liệu giỏ hàng không hợp lệ');
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