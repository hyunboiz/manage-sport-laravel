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
    .legend-box.status-locked { background-color: #979597; }

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

    .schedule-table td[data-status="pending"] {
        background-color: #ffc107; /* Red */
        cursor: not-allowed; /* Không cho phép click */
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
    .schedule-table td[data-status="locked"] {
        background-color: #979597; s
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
</style>
@endsection

@section('mainsection')

    <div class="app-header d-flex justify-content-between align-items-center px-3 py-2">
        <a href="#" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
        <h4 class="mb-0 text-white">Đặt lịch ngày trực quan</h4>
        <div class="date-picker-container">
            <input type="date" class="form-control date-picker" id="date-selected">
        </div>
    </div>

    <div class="container-fluid py-3">
        <div class="legend-section d-flex align-items-center mb-3">
            <div class="legend-item d-flex align-items-center mr-3">
                <span class="legend-box status-free"></span>
                <span class="ml-1">Trống</span>
            </div>
            <div class="legend-item d-flex align-items-center mr-3">
                <span class="legend-box status-booked"></span>
                <span class="ml-1">Đã đặt</span>
            </div>
            <div class="legend-item d-flex align-items-center mr-3">
                <span class="legend-box status-locked"></span>
                <span class="ml-1">Khóa</span>
            </div>
            <div class="legend-item d-flex align-items-center mr-3">
                <span class="legend-box status-event"></span>
                <span class="ml-1">Sự kiện</span>
            </div>
            <div class="legend-item d-flex align-items-center mr-3">
                <span class="legend-box status-selected"></span>
                <span class="ml-1">Đang chọn</span>
            </div>
            <div class="legend-item d-flex align-items-center mr-3">
                <span class="legend-box status-pending"></span>
                <span class="ml-1">Chờ TT</span>
            </div>
            <div class="legend-item d-flex justify-content-end ml-auto">
                <button class="btn btn-warning btn-sm" id="btnShowPrices" type="button">Xem bảng giá</button>
            </div>
        </div>
        <div class="table-responsive">
            <div id="schedule-container"></div>
        </div>
        <div id="selection-summary-bar" class="fixed-bottom-bar d-flex justify-content-between align-items-center p-3 d-none">
            <div class="total-info d-flex flex-column text-white">
                <span>Tổng giờ: <span id="total-hours"></span></span>
                <span>Tổng tiền: <span id="total-price"></span></span>
            </div>
            <div class="d-flex justify-content-end ml-auto">
                <button id="next-button" class="btn next-button mr-3">Thêm vào lịch đặt</button>
                {{-- <button id="submit-button" class="btn submit-button">Thanh toán</button> --}}
            </div>
        </div>
    </div>
<!-- Modal Bảng giá -->
<div class="modal fade" id="priceModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title text-white">Bảng giá sân</h5>
        <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="priceModalBody">
        <!-- JS sẽ render bảng vào đây -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function getTodayHoChiMinh() {
    const now = new Date();
    const utc = now.getTime() + now.getTimezoneOffset() * 60000;
    const hochiminh = new Date(utc + (7 * 60 * 60000)); // GMT+7

    const yyyy = hochiminh.getFullYear();
    const mm = String(hochiminh.getMonth() + 1).padStart(2, '0');
    const dd = String(hochiminh.getDate()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd}`;
}
// === Config & Variables ===
const sportId = {{ $sportId }};
let selectedTempCells = loadTempSelectionFromCookie() || [];

// === On Page Load ===
$(document).ready(function () {

 const todayYMD = getTodayHoChiMinh();  // Đã chuẩn theo GMT+7
    const $dateInp = $('#date-selected');

    $dateInp.val('');
    $dateInp.val(todayYMD);
    $dateInp.attr('min', todayYMD);

    loadSchedule(todayYMD);
});

// === Load Field Schedule by Date ===
function loadSchedule(date) {
  $.ajax({
    url: '/api/loadFieldList',
    data: { sport_id: sportId, date: date },
    method: "POST",
    dataType: "JSON",
    success: function (res) {
      window.lastTimeframes = res.timeframes;
      window.priceList = res.fields;
      renderScheduleTable(res);
    },
    error: function () {
      swal('error', 'Không thể tải dữ liệu sân');
    }
  });
}

// Nhóm khung giờ theo ex_rate và liền kề
function groupTimeframesByExRate(timeframes) {
  if (!Array.isArray(timeframes) || timeframes.length === 0) return [];
  const sorted = [...timeframes].sort((a, b) => parseInt(a.start) - parseInt(b.start));
  const grouped = [];
  let currentGroup = {
    start: sorted[0].start,
    end: sorted[0].end,
    ex_rate: sorted[0].ex_rate
  };
  for (let i = 1; i < sorted.length; i++) {
    const t = sorted[i];
    const prevEnd = parseInt(currentGroup.end);
    const curStart = parseInt(t.start);
    if (t.ex_rate === currentGroup.ex_rate && curStart === prevEnd) {
      currentGroup.end = t.end;
    } else {
      grouped.push({ ...currentGroup });
      currentGroup = { start: t.start, end: t.end, ex_rate: t.ex_rate };
    }
  }
  grouped.push({ ...currentGroup });
  return grouped;
}

// Hiển thị modal bảng giá
$('#btnShowPrices').on('click', function () {
  const groupedTimes = groupTimeframesByExRate(window.lastTimeframes || []);
  const fields = window.priceList || [];
  if (!groupedTimes.length || !fields.length) {
    $('#priceModalBody').html('<p>Không có dữ liệu bảng giá.</p>');
    return new bootstrap.Modal('#priceModal').show();
  }
  let html = `<div class="table-responsive"><table class="table table-bordered text-center">
                <thead class="table-light"><tr><th>Sân / Khung giờ</th>`;
  groupedTimes.forEach(g => {
    html += `<th>${g.start}h - ${g.end}h</th>`;
  });
  html += `</tr></thead><tbody>`;
  fields.forEach(f => {
    html += `<tr><td>Sân #${f.id}<br><small>${f.type?.name ?? ''}</small></td>`;
    groupedTimes.forEach(g => {
      const finalPrice = Math.round(f.price * (100 + g.ex_rate) / 100);
      html += `<td>${finalPrice.toLocaleString('vi-VN')} đ</td>`;
    });
    html += `</tr>`;
  });
  html += `</tbody></table></div>`;
  $('#priceModalBody').html(html);
  $('#priceModal').modal('show');
});


// === Render Schedule Table ===
function renderScheduleTable(data) {
  let html = `<table class="table table-bordered schedule-table">
                <thead><tr><th class="sticky-header-col">Sân/Giờ</th>`;
  data.timeframes.forEach(t => {
    html += `<th>${t.start}:00 - ${t.end}:00</th>`;
  });
  html += `</tr></thead><tbody>`;

  data.fields.forEach(f => {
    html += `<tr><th class="sticky-header-col">Sân Số ${f.id}</th>`;
    data.timeframes.forEach(t => {
      const key = `${f.id}_${t.id}`;
      const endHour = parseInt(t.end);
      const isLocked = Object.hasOwn(data.locked, key);
      const isToday = data.date === data.today;
      const isOutTime = isToday && endHour <= data.currentHour;

      let status = 'free';
      let tdClass = '';
      if (isLocked) {
        status = 'booked'; // Ưu tiên hiển thị đã đặt
      } else if (isOutTime) {
        status = 'locked'; // Chỉ hiển thị locked nếu không phải đã đặt
      }

      html += `<td data-field="${f.id}" data-name="${f.sport.name}" data-type="${f.type.name}" data-starthour="${t.start}" data-endhour="${t.end}" data-time="${t.id}" data-exrate="${t.ex_rate}" data-price="${f.price}" data-status="${status}"></td>`;
    });
    html += `</tr>`;
  });

  html += `</tbody></table>`;
  $('#schedule-container').html(html);
  updateInformationAndHighlightCells();
}

// === Highlight + Tính tiền và loại bỏ giờ quá hạn ===
function updateInformationAndHighlightCells() {
  let totalPrice = 0;
  let totalHours = 0;
  const currentDate = $('#date-selected').val();
  const today = new Date().toISOString().slice(0, 10);
  const nowHour = new Date().getHours();

  const timeEndMap = {};
  $('.schedule-table thead th').each(function (index) {
    const text = $(this).text();
    const match = text.match(/(\d+):00\s*-\s*(\d+):00/);
    if (match && index > 0) {
      const timeId = index;
      const endHour = parseInt(match[2]);
      timeEndMap[timeId] = endHour;
    }
  });

  selectedTempCells = selectedTempCells.filter(sel => {
    if (sel.date === today) {
      const endHour = timeEndMap[sel.timeId];
      if (endHour !== undefined && endHour <= nowHour) return false;
    }
    return true;
  });
  saveTempSelectionToCookie();

  $('.schedule-table td').removeClass('status-selected');
  selectedTempCells.forEach(sel => {
    if (sel.date === currentDate) {
      const $cell = $(`td[data-field="${sel.fieldId}"][data-time="${sel.timeId}"]`);
      if ($cell.length) {
        $cell.addClass('status-selected');
        const price = parseFloat($cell.data('price'));
        if (!isNaN(price)) {
          totalPrice += price;
          totalHours++;
        }
      }
    }
  });

  if (totalHours > 0) {
    $('#selection-summary-bar').removeClass('d-none');
    $('#total-price').html(formatVND(totalPrice));
    $('#total-hours').html(totalHours + 'h');
  } else {
    $('#selection-summary-bar').addClass('d-none');
  }
}

// === Xử lý click vào ô td ===
$('#schedule-container').on('click', 'td', function () {
  const $cell = $(this);
  if ($cell.data('status') !== 'free') return;

  const selectedDate = $('#date-selected').val();
  const fieldId = $cell.data('field');
  const timeId = $cell.data('time');
  const exrate = $cell.data('exrate');
  const price = $cell.data('price');
  const name = $cell.data('name');
  const type = $cell.data('type');

  const idx = selectedTempCells.findIndex(c =>
    c.fieldId == fieldId && c.timeId == timeId && c.date == selectedDate
  );

  if (idx > -1) selectedTempCells.splice(idx, 1);
  else selectedTempCells.push({ fieldId, timeId, name, type, startHour: parseInt($cell.data('starthour')),endHour: parseInt($cell.data('endhour')),timeExrate: exrate, fieldPrice: price, date: selectedDate });

  saveTempSelectionToCookie();
  updateInformationAndHighlightCells();
});

// === Chuyển ngày => reload schedule ===
$('#date-selected').on('change', function () {
  loadSchedule($(this).val());
});

// === Click Thêm vào giỏ ===
$('#next-button').on('click', function () {
    const currentDate = $('#date-selected').val();
    const currentSelections = selectedTempCells.filter(sel => sel.date === currentDate);

    if (currentSelections.length === 0) {
        swal('error', 'Chọn ít nhất 1 sân');
        return;
    }

    // Load giỏ hàng cũ
    let cart = [];
    try {
        const raw = Cookies.get('bookingCart');
        if (raw) {
            const parsed = JSON.parse(raw);
            if (Array.isArray(parsed.selections)) {
                cart = parsed.selections;
            }
        }
    } catch (e) {
        cart = [];
    }

    // Gộp dữ liệu mới vào cart, tránh trùng
    currentSelections.forEach(sel => {
        const exists = cart.some(c =>
            c.fieldId == sel.fieldId &&
            c.timeId == sel.timeId &&
            c.date === sel.date
        );
        if (!exists) cart.push(sel);
    });

    // Lưu lại
    Cookies.set('bookingCart', JSON.stringify({ selections: cart }), { expires: 7 });

    // Xóa lựa chọn tạm thời
    selectedTempCells = selectedTempCells.filter(sel => sel.date !== currentDate);
    saveTempSelectionToCookie();

    // Giao diện
    currentSelections.forEach(sel => {
        const $cell = $(`td[data-field="${sel.fieldId}"][data-time="${sel.timeId}"]`);
        if ($cell.length) {
            $cell.attr('data-status', 'pending').removeClass('status-selected');
        }
    });

    swal('success', 'Thêm vào lịch đặt thành công');
    setTimeout(() => {
        window.location.href = "{{ route('user.cart') }}";
    }, 2000);
});

// === Cookie Helpers ===
function saveTempSelectionToCookie() {
  Cookies.set('bookingTemp', JSON.stringify({ selections: selectedTempCells }), { expires: 1 });
}

function loadTempSelectionFromCookie() {
  try {
    const data = Cookies.get('bookingTemp');
    if (!data) return [];
    return JSON.parse(data).selections || [];
  } catch (e) {
    return [];
  }
}

function formatVND(amount) {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

</script>
@endsection