  @extends('layouts.admin.app')

  @section('title', 'Quản lý danh sách sân')

  @section('styles')

  @endsection

  @section('mainsection')
  <div class="row">
      <div class="col-12">
        <div class="card border shadow-xs mb-4">
          <div class="card-header border-bottom pb-0">
            <div class="d-sm-flex align-items-center">
              <div>
                <h6 class="font-weight-semibold text-lg mb-0">Field list</h6>
                <p class="text-sm">See information about all fields</p>
              </div>
              <div class="ms-auto d-flex">
                <button type="button" data-bs-toggle="modal" data-bs-target="#createFieldModal" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                  <span class="btn-inner--icon">
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                      <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                    </svg>
                  </span>
                  <span class="btn-inner--text">Thêm mới sân</span>
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
                    <th class="text-secondary text-xs font-weight-semibold opacity-7">Giá tiền</th>
                    <th class="text-secondary text-xs font-weight-semibold opacity-7">Hình ảnh</th>
                    <th class="text-secondary text-xs font-weight-semibold opacity-7">Môn thể thao</th>
                    <th class="text-secondary text-xs font-weight-semibold opacity-7">Loại sân</th>
                    <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tạo lúc</th>
                    <th class="text-secondary opacity-7"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($fields as $field)
                    <tr>
                      <td>{{ $field->id }}</td>
                      <td>{{ $field->price }}</td>
                      <td> <img src="{{ asset( $field->image) }}" width="140px"></td>
                      <td>{{ $field->sport->name }}</td>
                      <td>{{ $field->type->name }}</td>
                      <td class="align-middle text-center">{{ $field->created_at }}</td>
                      <td class="align-middle">
                          <span type="button"
                                data-id="{{ $field->id }}"
                                  data-price="{{ $field->price }}"
                                  data-image="{{ $field->image }}"
                                  data-sport_id="{{ $field->sport_id }}"
                                  data-type_id="{{ $field->type_id }}"
                                class="edit-field-btn"
                                data-bs-toggle="tooltip"
                                data-bs-title="Edit user">
                              <i class="fa fa-pencil" aria-hidden="true"></i>
                            </span>
                            <span type="button" onclick="deleteField({{$field->id}})">
                              <i class="fa fa-trash" aria-hidden="true"></i>
                            </span>
                          </td>
                        </tr>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
  </div>

  <!-- Modal Create -->
  <div class="modal fade" id="createFieldModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Thêm mới sân</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="createFieldForm">
            <div class="mb-3">
              <label>Price</label>
              <input type="text" name="price" id="price" class="form-control" placeholder="Price">
            </div>
            <div class="mb-3">
              <label>Chọn môn thể thao</label>
              <select name="sport_id" id="sport_id" class="form-control">
                <option value="">--- Chọn môn thể thao ---</option>
                @foreach($sports as $sport)
                  <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label>Chọn loại sân</label>
              <select name="type_id" id="type_id" class="form-control">
                @foreach($types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
            <label>Image</label>
              <div class="mb-3">
                  <input type="file" name="image" id="imageField" accept="image/*" class="form-control" onchange="const f=this.files[0];if(f) preview.src=(window.URL||window.webkitURL).createObjectURL(f)" placeholder="Enter Icon Image">
              </div>
              <img src="" alt="" width="140px" id="preview">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-dark" id="createFieldBtn">Lưu lại</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal Create -->

  <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPaymentModalLabel">Sửa thông tin Sân</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editAdminForm">
            <input type="hidden" id="edit-id" name="id">
            <div class="mb-3">
              <label for="edit-price" class="form-label">Giá tiền</label>
              <input type="text" class="form-control" id="edit-price" name="price">
            </div>
            <div class="mb-3">
              <label>Chọn môn thể thao</label>
              <select name="sport_id" id="edit-sport_id" class="form-control">
                <option value="">--- Chọn môn thể thao ---</option>
                @foreach($sports as $sport)
                  <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label>Chọn loại sân</label>
              <select name="type_id" id="edit-type_id" class="form-control">
                @foreach($types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="edit-icon" class="form-label">Hình ảnh</label>
              <input type="file" class="form-control" id="edit-image" accept="image/*" onchange="const f=this.files[0];if(f) editicon.src=(window.URL||window.webkitURL).createObjectURL(f)" name="image">
            </div>
            <img src="" alt="" width="140px" id="editicon">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary" id="editBtn">Lưu thay đổi</button>
        </div>
      </div>
    </div>
  </div>
  @endsection

  @section('scripts')
  <script>
    function loadTypes(sportId, $typeSelect, selectedId = null) {
      $.post('/api/getTypeBySport', { sport_id: sportId }, function (types) {
        $typeSelect.empty();

        if (!types.length) {
          $typeSelect.append('<option value="">— Không có loại sân —</option>');
          return;
        }

        types.forEach(type => {
          const selected = (type.id == selectedId) ? 'selected' : '';
          $typeSelect.append(`<option value="${type.id}" ${selected}>${type.name}</option>`);
        });
      });
    }
    $('#sport_id').on('change', function () {
      loadTypes(this.value, $('#type_id'));
    });
    $('#edit-sport_id').on('change', function () {
      loadTypes(this.value, $('#edit-type_id'));
    });
  // Bắt sự kiện click edit button 
    $(document).on('click', '.edit-field-btn', function() {
      var data = $(this).data();  // Lấy tất cả data-*

      // Đổ dữ liệu vào input trong modal
      $('#edit-id').val(data.id);
      $('#edit-price').val(data.price);
      $('#edit-sport_id').val(data.sport_id);
      $('#edit-type_id').val(data.type_id);
      $('#editicon').attr('src',data.icon);

      loadTypes(data.sport_id, $('#edit-type_id'), data.type_id);
      // Hiển thị modal
      $('#editPaymentModal').modal('show');
    });
    $(document).on('click', '#createFieldBtn', function () {
      var data = new FormData();
      data.append('price', $('#price').val());
      data.append('sport_id', $('#sport_id').val());
      data.append('type_id', $('#type_id').val());
      data.append('image', $('#imageField')[0].files[0]);
      $.ajax({
        type: "POST",
        url: "/api/admin/createField",
        data: data,
        dataType: "JSON",
        contentType: false,
        processData: false,
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

    $(document).on('click', '#editBtn', function () {
      var data = new FormData();
      data.append('id', $('#edit-id').val());
      data.append('price', $('#edit-price').val());
      data.append('sport_id', $('#edit-sport_id').val());
      data.append('type_id', $('#edit-type_id').val());
      if ($('#edit-image')[0].files.length > 0) {
          data.append('image', $('#edit-image')[0].files[0]);  // Chỉ gửi nếu có file mới
      }
      $.ajax({
          type: "POST",
          url: "/api/admin/updateField",  
          data: data,
          dataType: "JSON",
          contentType: false,
          processData: false,
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

    function deleteField(id) {
      Swal.fire({
        title: "Xác nhận",
        text: "Bạn muốn xóa sân này?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy"
      }).then(result => {
        if(result.isConfirmed) {
          $.post('/api/admin/deleteField', { id }, function(res) {
            if(res.status) {
              swal('success', res.message);
              setTimeout(() => location.reload(), 2000);
            } else {
              swal('error', res.message);
            }
          });
        }
      });
    }
  </script>
  @endsection
