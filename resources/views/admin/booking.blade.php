@extends('layouts.admin.app')

@section('title', 'Quản lý danh sách admin')

@section('styles')

@endsection

@section('mainsection')
<div class="row">
        <div class="col-12">
          <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
              <div class="d-sm-flex align-items-center">
                <div>
                  <h6 class="font-weight-semibold text-lg mb-0">Lịch sử đặt sân</h6>
                  <p class="text-sm">Quản lý lịch sử đặt sân</p>
                </div>
                <div class="ms-auto d-flex">
                  <button type="button" data-bs-toggle="modal" data-bs-target="#createAdminModal" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                    <span class="btn-inner--icon">
                      <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                        <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                      </svg>
                    </span>
                    <a href="{{ route('admin.bookings.create') }}" class="btn-inner--text text-white">Thêm mới lịch đặt</a>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body px-0 py-0">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead class="bg-gray-100">
                    <tr>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7">ID</th>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7">Thành viên</th>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Thanh toán</th>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Trạng thái</th>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tổng</th>
                      <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tạo lúc</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bookings as $booking)
                        @php
                            if ($booking->status == 'pending') {
                                $class = 'info';
                            } elseif ($booking->status == 'confirmed') {
                                $class = 'success';
                            } else {
                                $class = 'danger';
                            }
                        @endphp
                      <tr>
                        <td>{{ $booking->id }}</td>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center ms-1">
                              <h6 class="mb-0 text-sm font-weight-semibold">Họ và tên: {{ $booking->customer->name }}</h6>
                              <p class="text-sm text-secondary mb-0">Email: {{ $booking->customer->email }}</p>
                              <p>SĐT: {{ $booking->customer->hotline }}</p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-sm text-dark font-weight-semibold mb-0">{{ $booking->paymentMethod->name }}</p>
                        </td>
                         <td>
                          <span class="badge badge-{{ $class }}">{{ $booking->status }}</span>
                        </td>
                         <td>
                          <p class="text-sm text-dark font-weight-semibold mb-0">{{ number_format($booking->total) }}</p>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-sm font-weight-normal">{{ $booking->created_at }}</span>
                        </td>
                        <td class="align-middle">
                        <a href="/admin/detail/{{ $booking->id }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                          <span type="button"
                              @foreach($booking->toArray() as $key => $value)
                                 @if(is_scalar($value)) data-{{ $key }}="{{ $value }}" @endif
                              @endforeach
                              class="edit-booking-btn"
                              data-bs-toggle="tooltip"
                              data-bs-title="Edit user">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                          </span>
                          <span type="button" onclick="deleteAdmin({{$booking->id}})">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                          </span>
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
<!-- Edit Admin Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAdminModalLabel">Thông tin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editAdminForm">
          <input type="hidden" id="edit-id" name="id">
          <div class="mb-3">
            <label for="edit-status" class="form-label">Trạng thái</label>
            <select name="status" id="edit-status" class="form-control">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancel">Cancel</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="saveAdminChanges">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->
@endsection

@section('scripts')
<script>
  // Bắt sự kiện click edit button 
  $(document).on('click', '.edit-booking-btn', function() {
    var data = $(this).data();  // Lấy tất cả data-*

    // Đổ dữ liệu vào input trong modal
    $('#edit-id').val(data.id);
    $('#edit-status').val(data.status);

    // Hiển thị modal
    $('#editBookingModal').modal('show');
});
  // Tạo admin
  $("#createAdmin").on('click', function () {
    var data = $("#formcreateAdmin").serialize();
    $.ajax({
      type: "POST",
      url: "/api/admin/createAdmin",
      data: data,
      dataType: "JSON",
      success: function (response) {
        if(response.status == true){
          setInterval(() => {
            window.location.reload();
          }, 3000);
          swal('success', response.message);
        }else{
          swal('error', response.message);
        }
      }
    });
  });
  // Sửa admin
  $('#saveAdminChanges').click(function() {
    var formData = $('#editAdminForm').serialize();
    $.ajax({
        type: "POST",
        url: "/api/admin/updateBooking",  
        data: formData,
        dataType: "JSON",
        success: function(response) {
          if(response.status == true){
            swal('success', response.message);
            setTimeout(() => window.location.reload(), 2000);
          }else{
            swal('error', response.message);
          }
        }
    });
});

//Delete admin
function deleteAdmin(adminId) {
        Swal.fire({
        title: "Thông báo",
        text: "Bạn chắc chắn muốn xóa?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Chắc chắn!"
      }).then((result) => {
        if (result.isConfirmed) {
              $.ajax({
            type: "POST",
            url: "/api/admin/deleteAdmin",
            data: {
                id: adminId
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire('Thành công', response.message, 'success');
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    Swal.fire('Lỗi', response.message, 'error');
                }
            }
        });
     }
  });
}
</script>
@endsection

