@extends('layouts.admin.app')

@section('title', 'Quản lý loại sân')

@section('mainsection')
<div class="row">
  <div class="col-12">
    <div class="card border shadow-xs mb-4">
      <div class="card-header border-bottom pb-0 d-flex justify-content-between align-items-center">
        <div>
          <h6 class="font-weight-semibold text-lg mb-0">Type List</h6>
        </div>
        <button type="button" data-bs-toggle="modal" data-bs-target="#createTypeModal" class="btn btn-sm btn-dark">
          <i class="fa fa-plus me-2"></i> Add Type
        </button>
      </div>
      <div class="card-body px-0 py-0">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead class="bg-gray-100">
              <tr>
                <th class="text-secondary text-xs font-weight-semibold opacity-7">ID</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7">Môn thể thao</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7">Tên</th>
                <th class="text-secondary text-xs font-weight-semibold opacity-7">Mô tả</th>
                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Tạo lúc</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($types as $type)
              <tr>
                <td>{{ $type->id }}</td>
                <td>{{ $type->sport->name }}</td>
                <td><strong>{{ $type->name }}</strong></td>
                <td>{{ $type->description }}</td>
                <td class="text-center">{{ $type->created_at }}</td>
                <td class="text-end">
                  <span type="button" class="edit-type-btn" @foreach($type->toArray() as $k => $v) @if(is_scalar($v)) data-{{ $k }}="{{ $v }}" @endif @endforeach>
                    <i class="fa fa-pencil"></i>
                  </span>
                  <span type="button" onclick="deleteType({{ $type->id }})">
                    <i class="fa fa-trash"></i>
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

<!-- Modal Tạo Loại -->
<div class="modal fade" id="createTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm loại sân</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formCreateType">
          <div class="mb-3">
            <label>Môn thể thao</label>
           <select name="sport_id" id="sport_id" class="form-control">
            <option value="">--- Chọn môn thể thao ---</option>
            @foreach ($sports as $sport)
              <option value="{{ $sport->id }}">{{ $sport->name }}</option>
            @endforeach
           </select>
          </div>
          <div class="mb-3">
            <label>Tên loại</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-dark" id="createType">Lưu</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sửa Loại -->
<div class="modal fade" id="editTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chỉnh sửa loại sân</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEditType">
          <input type="hidden" name="id" id="edit-id">
          <select name="sport_id" id="edit-sport_id" class="form-control">
            <option value="">--- Chọn môn thể thao ---</option>
            @foreach ($sports as $sport)
              <option value="{{ $sport->id }}">{{ $sport->name }}</option>
            @endforeach
           </select>
          <div class="mb-3">
            <label>Tên loại</label>
            <input type="text" name="name" class="form-control" id="edit-name">
          </div>
          <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" id="edit-description"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="saveType">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $('#createType').click(function () {
    $.post('/api/admin/createType', $('#formCreateType').serialize(), function (res) {
      if (res.status) {
        swal('success', res.message);
        setTimeout(() => location.reload(), 1500);
      } else {
        swal('error', res.message);
      }
    });
  });

  $(document).on('click', '.edit-type-btn', function () {
    const data = $(this).data();
    $('#edit-id').val(data.id);
    $('#edit-sport_id').val(data.sport_id);
    $('#edit-name').val(data.name);
    $('#edit-description').val(data.description);
    $('#editTypeModal').modal('show');
  });

  $('#saveType').click(function () {
    $.post('/api/admin/updateType', $('#formEditType').serialize(), function (res) {
      if (res.status) {
        swal('success', res.message);
        setTimeout(() => location.reload(), 1500);
      } else {
        swal('error', res.message);
      }
    });
  });

  function deleteType(id) {
    Swal.fire({
      title: 'Xác nhận',
      text: 'Bạn có chắc muốn xoá loại sân này?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Xóa',
      cancelButtonText: 'Huỷ'
    }).then(result => {
      if (result.isConfirmed) {
        $.post('/api/admin/deleteType', { id }, function (res) {
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
