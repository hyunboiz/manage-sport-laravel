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
                  <h6 class="font-weight-semibold text-lg mb-0">Members list</h6>
                  <p class="text-sm">See information about all members</p>
                </div>
                <div class="ms-auto d-flex">
                  <button type="button" data-bs-toggle="modal" data-bs-target="#createAdminModal" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                    <span class="btn-inner--icon">
                      <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                        <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                      </svg>
                    </span>
                    <span class="btn-inner--text">Add member</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body px-0 py-0">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead class="bg-gray-100">
                    <tr>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7">Member</th>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Username</th>
                      <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Create At</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($admins as $admin)
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center ms-1">
                              <h6 class="mb-0 text-sm font-weight-semibold">{{ $admin->name }}</h6>
                              <p class="text-sm text-secondary mb-0">{{ $admin->email }}</p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-sm text-dark font-weight-semibold mb-0">{{ $admin->username }}</p>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-sm font-weight-normal">{{ $admin->created_at }}</span>
                        </td>
                        <td class="align-middle">
                          <span type="button"
                              @foreach($admin->toArray() as $key => $value)
                                  data-{{ $key }}="{{ $value }}"
                              @endforeach
                              class="edit-admin-btn"
                              data-bs-toggle="tooltip"
                              data-bs-title="Edit user">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                          </span>
                          <span type="button" onclick="deleteAdmin({{$admin->id}})">
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

<!-- Create Admin Modal --> 
 <div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Thêm mới admin</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formcreateAdmin">
            <label>Email Address</label>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Enter Email Address">
            </div>
            <label>Username</label>
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Enter Username">
            </div>
             <label>Password</label>
            <div class="mb-3">
                <input type="text" name="password" class="form-control" placeholder="Enter Password">
            </div>
            <label>Fullname</label>
            <div class="mb-3">
                <input type="text" name="fullname" class="form-control" placeholder="Enter fullname">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="createAdmin" class="btn btn-sm btn-dark">Lưu lại</button>
      </div>
    </div>
  </div>
</div>
<!-- End --> 

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAdminModalLabel">Sửa thông tin Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editAdminForm">
          <input type="hidden" id="edit-id" name="id">
          <div class="mb-3">
            <label for="edit-name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="edit-name" name="name">
          </div>
          <div class="mb-3">
            <label for="edit-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="edit-email" name="email">
          </div>
          <div class="mb-3">
            <label for="edit-username" class="form-label">Username</label>
            <input type="text" class="form-control" id="edit-username" name="username">
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
  $(document).on('click', '.edit-admin-btn', function() {
    var data = $(this).data();  // Lấy tất cả data-*

    // Đổ dữ liệu vào input trong modal
    $('#edit-id').val(data.id);
    $('#edit-name').val(data.name);
    $('#edit-email').val(data.email);
    $('#edit-username').val(data.username);

    // Hiển thị modal
    $('#editAdminModal').modal('show');
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
        url: "/api/admin/updateAdmin",  
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

