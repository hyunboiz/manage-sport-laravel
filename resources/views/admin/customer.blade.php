@extends('layouts.admin.app')

@section('title', 'Quản lý danh sách khách hàng')

@section('mainsection')
<div class="row">
  <div class="col-12">
    <div class="card border shadow-xs mb-4">
      <div class="card-header border-bottom pb-0">
        <div class="d-sm-flex align-items-center">
          <div>
            <h6 class="font-weight-semibold text-lg mb-0">Danh sách khách hàng</h6>
            <p class="text-sm">Thông tin khách hàng</p>
          </div>
          <div class="ms-auto d-flex">
            <button type="button" data-bs-toggle="modal" data-bs-target="#createCustomerModal" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
              <i class="fa fa-plus me-2"></i> Thêm khách hàng
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
                <th class="text-secondary text-xs font-weight-semibold opacity-7">Thông tin</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tên tài khoản</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Số điện thoại</th>
                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tạo lúc</th>
                <th class="text-secondary opacity-7"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($customers as $customer)
              <tr>
                <td>{{ $customer->id }}</td>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center ms-1">
                      <h6 class="mb-0 text-sm font-weight-semibold">{{ $customer->name }}</h6>
                      <p class="text-sm text-secondary mb-0">{{ $customer->email }}</p>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $customer->username }}</p>
                </td>
                <td>
                  <p class="text-sm text-dark font-weight-semibold mb-0">{{ $customer->hotline }}</p>
                </td>
                <td class="align-middle text-center">
                  <span class="text-secondary text-sm font-weight-normal">{{ $customer->created_at }}</span>
                </td>
                <td class="align-middle">
                  <span
                    type="button"
                    @foreach($customer->toArray() as $key => $value)
                      data-{{ $key }}="{{ $value }}"
                    @endforeach
                    class="edit-customer-btn"
                    data-bs-toggle="tooltip"
                    data-bs-title="Edit customer"
                  >
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                  </span>
                  <span type="button" onclick="deleteCustomer({{ $customer->id }})">
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

{{-- Modal Thêm Customer --}}
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Thêm mới khách hàng</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formCreateCustomer">
          <label>Email</label>
          <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter Email">
          </div>
          <label>Username</label>
          <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Enter Username">
          </div>
          <label>Password</label>
          <div class="mb-3">
            <input type="text" name="password" class="form-control" placeholder="Enter Password">
          </div>
          <label>Full Name</label>
          <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Enter Full Name">
          </div>
          <label>Hotline</label>
          <div class="mb-3">
            <input type="text" name="hotline" class="form-control" placeholder="Enter Hotline">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" id="createCustomer" class="btn btn-dark btn-sm">Lưu lại</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Sửa --}}
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sửa khách hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editCustomerForm">
          <input type="hidden" name="id" id="edit-id">
          <label>Họ tên</label>
          <input type="text" name="name" class="form-control mb-2" id="edit-name">
          <label>Email</label>
          <input type="email" name="email" class="form-control mb-2" id="edit-email">
          <label>Username</label>
          <input type="text" name="username" class="form-control mb-2" id="edit-username">
          <label>Hotline</label>
          <input type="text" name="hotline" class="form-control" id="edit-hotline">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="saveCustomerChanges">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).on('click', '.edit-customer-btn', function() {
  var data = $(this).data();
  $('#edit-id').val(data.id);
  $('#edit-name').val(data.name);
  $('#edit-email').val(data.email);
  $('#edit-username').val(data.username);
  $('#edit-hotline').val(data.hotline);
  $('#editCustomerModal').modal('show');
});

$('#createCustomer').click(function() {
  var data = $('#formCreateCustomer').serialize();
  $.post('/admin/customer/store', data, function(res) {
    if (res.status) {
      swal('success', res.message);
      setTimeout(() => location.reload(), 1500);
    } else {
      swal('error', res.message);
    }
  });
});

$('#saveCustomerChanges').click(function() {
  var data = $('#editCustomerForm').serialize();
  $.post('/admin/customer/update', data, function(res) {
    if (res.status) {
      swal('success', res.message);
      setTimeout(() => location.reload(), 1500);
    } else {
      swal('error', res.message);
    }
  });
});

function deleteCustomer(id) {
  Swal.fire({
    title: "Xác nhận",
    text: "Bạn có chắc muốn xoá khách hàng này?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Xoá",
    cancelButtonText: "Huỷ"
  }).then((result) => {
    if (result.isConfirmed) {
      $.post('/admin/customer/delete', { id }, function(res) {
        if (res.status) {
          swal('success', res.message);
          setTimeout(() => location.reload(), 1500);
        } else {
          swal('error', res.message);
        }
      });
    }
  });
}
</script>
@endsection
