@extends('layouts.admin.app')

@section('title', 'Tạo đơn đặt sân')

@section('mainsection')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">Tạo đơn đặt sân</h5>
    </div>
    <div class="card-body">
      <form id="bookingForm">
        @csrf
        {{-- ==== Khách hàng ==== --}}
        <div class="mb-3">
          <label for="customerOption" class="form-label">Chọn khách hàng</label>
          <select class="form-select" id="customerOption" name="customer_option">
            <option value="old" selected>Khách hàng cũ</option>
            <option value="new">Tạo khách mới</option>
          </select>
        </div>

        {{-- Khách cũ --}}
        <div id="oldCustomerFields" class="mb-3">
          <label for="customer_id" class="form-label">Khách hàng</label>
          <select class="form-select" name="customer_id" id="customer_id">
            <option value="">-- Chọn khách hàng --</option>
            @foreach($customers as $cus)
              <option value="{{ $cus->id }}">{{ $cus->name }} ({{ $cus->email }})</option>
            @endforeach
          </select>
        </div>

        {{-- Khách mới --}}
        <div id="newCustomerFields" class="row" style="display:none;">
          <div class="col-md-6 mb-3">
            <label>Họ tên</label>
            <input type="text" name="name" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label>Hotline</label>
            <input type="text" name="hotline" class="form-control">
          </div>
        </div>

        <hr>

        {{-- ==== Các sân đặt ==== --}}
        <div id="bookingItems">
          <div class="booking-item border p-3 rounded mb-3">
            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label">Môn thể thao</label>
                <select class="form-select sport-select" name="selections[0][sport_id]">
                  <option value="">-- Chọn môn --</option>
                  @foreach($sports as $sport)
                    <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Sân</label>
                <select class="form-select field-select" name="selections[0][field_id]" disabled>
                  <option value="">-- Chọn sân --</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label">Ngày</label>
                <input type="date" class="form-control date-select" name="selections[0][date]" disabled>
              </div>
              <div class="col-md-2">
                <label class="form-label">Khung giờ</label>
                <select class="form-select time-select" name="selections[0][time_id]" disabled>
                  <option value="">-- Chọn giờ --</option>
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label">Giá (đ)</label>
                <input type="text" class="form-control price-input" name="selections[0][price]" readonly>
              </div>
            </div>
          </div>
        </div>

        <button type="button" id="addBookingItem" class="btn btn-outline-secondary mb-3">+ Thêm sân</button>
        <button type="submit" class="btn btn-dark">Tạo đơn</button>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
  let idx = 1;

  /* ==== Toggle khách cũ / mới ==== */
  $('#customerOption').on('change', function () {
    const isNew = $(this).val() === 'new';
    $('#newCustomerFields').toggle(isNew);
    $('#oldCustomerFields').toggle(!isNew);
  });

  /* ==== Thêm block sân ==== */
  $('#addBookingItem').click(function () {
    const first = $('#bookingItems .booking-item:first').clone();
    first.find('select, input').each(function () {
      const newName = $(this).attr('name').replace(/\[\d+\]/, '[' + idx + ']');
      $(this).attr('name', newName).val('').prop('disabled', $(this).hasClass('field-select') || $(this).hasClass('date-select') || $(this).hasClass('time-select'));
    });
    first.find('.price-input').val('');
    $('#bookingItems').append(first);
    idx++;
  });

  /* ==== Khi chọn môn → load sân ==== */
  $(document).on('change', '.sport-select', function () {
    const sportId = $(this).val();
    const item    = $(this).closest('.booking-item');
    const fieldSl = item.find('.field-select');
    const dateIn  = item.find('.date-select');
    const timeSl  = item.find('.time-select');
    const priceIn = item.find('.price-input');

    priceIn.val('');
    timeSl.prop('disabled', true).html('<option value=\"\">-- Chọn giờ --</option>');
    dateIn.prop('disabled', true).val('');

    if (!sportId) {
      fieldSl.html('<option value=\"\">-- Chọn sân --</option>').prop('disabled', true);
      return;
    }

    $.post('/api/get-fields-by-sport', { sport_id: sportId }, res => {
      let html = '<option value=\"\">-- Chọn sân --</option>';
      res.fields.forEach(f => {
        const price = Number(f.price) || 0;
        const formatted = price.toLocaleString('vi-VN');
        const typeName = f.type?.name || 'Sân';
        html += `<option value="${f.id}" data-price="${price}">${typeName} #${f.id} - ${formatted}đ</option>`;

      });
      fieldSl.html(html).prop('disabled', false);
    });
  });

  /* ==== Khi chọn sân → bật date ==== */
  $(document).on('change', '.field-select', function () {
    const item   = $(this).closest('.booking-item');
    item.find('.date-select').prop('disabled', !$(this).val()).val('');
    item.find('.time-select').prop('disabled', true).html('<option value=\"\">-- Chọn giờ --</option>');
    item.find('.price-input').val('');
  });

  /* ==== Khi chọn ngày → load giờ ==== */
  $(document).on('change', '.date-select', function () {
    const item    = $(this).closest('.booking-item');
    const fieldId = item.find('.field-select').val();
    const date    = $(this).val();
    const timeSl  = item.find('.time-select');

    if (!fieldId || !date) return;

    $.post('/api/get-available-times', { field_id: fieldId, date }, res => {
      let html = '<option value=\"\">-- Chọn giờ --</option>';
      res.times.forEach(t => {
        const disabled = t.locked ? 'disabled' : '';
        html += `<option value="${t.id}" data-exrate="${t.ex_rate}" ${disabled}>${t.start} - ${t.end}${t.locked ? ' (Đã đặt)' : ''}</option>`;
    });
      timeSl.html(html).prop('disabled', false);
    });
  });

  /* ==== Khi chọn giờ → tính giá ==== */
  $(document).on('change', '.time-select', function () {
  const parent = $(this).closest('.booking-item');
  const fieldSelect = parent.find('.field-select option:selected');
  const timeSelect = $(this).find('option:selected');

  const basePrice = parseFloat(fieldSelect.data('price') || 0);
  const exRate = parseFloat(timeSelect.data('exrate') || 0);
  const finalPrice = Math.round(basePrice * (100 + exRate) / 100);

  parent.find('input[name$="[price]"]').val(finalPrice.toLocaleString());
});


  /* ==== Submit form ==== */
$('#bookingForm').on('submit', function (e) {
  e.preventDefault();

  // Normalize all price fields
  $('input[name$="[price]"]').each(function () {
    const raw = $(this).val();
    const cleaned = raw.replace(/[.,]/g, ''); // bỏ . hoặc ,
    $(this).val(cleaned);
  });

  $.post('/api/admin/bookings/create', $(this).serialize(), function (res) {
    if (res.status) {
      Swal.fire('Thành công', res.message, 'success').then(() => location.reload());
    } else {
      Swal.fire('Lỗi', res.message || 'Đã có lỗi xảy ra', 'error');
    }
  }).fail(function(xhr) {
    let msg = xhr.responseJSON?.errors || 'Lỗi không xác định';
    Swal.fire('Lỗi', JSON.stringify(msg), 'error');
  });
});

});
</script>
@endsection
