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
                  <h6 class="font-weight-semibold text-lg mb-0">TimeFrame list</h6>
                </div>
                <div class="ms-auto d-flex">
                  <button type="button" data-bs-toggle="modal" data-bs-target="#createTimeFrameModal" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2">
                    <span class="btn-inner--icon">
                      <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                        <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                      </svg>
                    </span>
                    <span class="btn-inner--text">Add Time Frame</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body px-0 py-0">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead class="bg-gray-100">
                    <tr>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7">Start</th>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">End</th>
                      <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Exchange Rate</th>
                      <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Create At</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($timeframes as $timeframe)
                      <tr>
                        <td>
                         <p class="text-sm text-dark font-weight-semibold mb-0">{{ $timeframe->start }}:00</p>
                        </td>
                        <td>
                          <p class="text-sm text-dark font-weight-semibold mb-0">{{ $timeframe->end }}:00</p>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-sm font-weight-normal">{{ $timeframe->ex_rate }}%</span>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-sm font-weight-normal">{{ $timeframe->created_at }}</span>
                        </td>
                        <td class="align-middle">
                          <span type="button"
                              @foreach($timeframe->toArray() as $key => $value)
                                  data-{{ $key }}="{{ $value }}"
                              @endforeach
                              class="edit-timeFrame-btn"
                              data-bs-toggle="tooltip"
                              data-bs-title="Edit user">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                          </span>
                          <span type="button" onclick="deleteTimeFrame({{$timeframe->id}})">
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
 <div class="modal fade" id="createTimeFrameModal" tabindex="-1" aria-labelledby="createTimeFrameModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Thêm mới Time Frame</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formCreateTimeFrame">
            <label>Start Time</label>
            <div class="mb-3">
                <select name="start" id="startTime" class="form-control">
                    <option value="0">--- Select Start Time ---</option>
                </select>
            </div>
            <label>End Time</label>
            <div class="mb-3">
                <select name="end" id="endTime" class="form-control">
                    <option value="0">--- Select End Time ---</option>
                </select>
            </div>
             <label>Exchange Rate (%)</label>
            <div class="mb-3">
                <input type="number" name="ex_rate" class="form-control" placeholder="Enter Exchange Rate">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="createTimeFrame" class="btn btn-sm btn-dark">Lưu lại</button>
      </div>
    </div>
  </div>
</div>
<!-- End --> 

<!-- Edit Admin Modal -->
<div class="modal fade" id="editTimeFrameModal" tabindex="-1" aria-labelledby="editTimeFrameModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTimeFrameModalLabel">Sửa thông tin Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editTimeFrameForm">
          <input type="hidden" id="edit-id" name="id">
          <label>Start Time</label>
            <div class="mb-3">
                <select name="start" id="edit-start" class="form-control">
                    <option value="0">--- Select Start Time ---</option>
                </select>
            </div>
            <label>End Time</label>
            <div class="mb-3">
                <select name="end" id="edit-end" class="form-control">
                    <option value="0">--- Select End Time ---</option>
                </select>
            </div>
             <label>Exchange Rate (%)</label>
            <div class="mb-3">
                <input type="number" id="edit-ex_rate" name="ex_rate" class="form-control" placeholder="Enter Exchange Rate">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="saveTimeFrame">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
<!-- End -->
@endsection

@section('scripts')
<script>
  const $start = $('#startTime');
  const $end = $('#endTime');

  // Tạo options cho cả hai select
  for (let i = 5; i <= 23; i++) {
    const label = `${i.toString().padStart(2, '0')}:00`;
    $start.append(`<option value="${i}">${label}</option>`);
    $end.append(`<option value="${i}">${label}</option>`);
  }

  // Xử lý khi chọn giờ bắt đầu
  $start.on('change', function () {
    const selected = $(this).val();
    $end.find('option').prop('disabled', false); // reset tất cả
    $end.find(`option[value="${selected}"]`).prop('disabled', true);

    // Nếu đang chọn giờ bị disable thì reset
    if ($end.val() === selected) {
      $end.val('');
    }
  });


  // Bắt sự kiện click edit button 
  $(document).on('click', '.edit-timeFrame-btn', function() {
    const $start = $('#edit-start');
    const $end = $('#edit-end');

    // Tạo options cho cả hai select
    for (let i = 5; i <= 23; i++) {
        const label = `${i.toString().padStart(2, '0')}:00`;
        $start.append(`<option value="${i}">${label}</option>`);
        $end.append(`<option value="${i}">${label}</option>`);
    }

    // Xử lý khi chọn giờ bắt đầu
    $start.on('change', function () {
        const selected = $(this).val();
        $end.find('option').prop('disabled', false); // reset tất cả
        $end.find(`option[value="${selected}"]`).prop('disabled', true);

        // Nếu đang chọn giờ bị disable thì reset
        if ($end.val() === selected) {
        $end.val('');
        }
    });
    
    var data = $(this).data();  // Lấy tất cả data-*

    // Đổ dữ liệu vào input trong modal
    $('#edit-id').val(data.id);
    $('#edit-start').val(data.start);
    $('#edit-end').val(data.end);
    $('#edit-ex_rate').val(data.ex_rate);

    // Hiển thị modal
    $('#editTimeFrameModal').modal('show');
});
  // Tạo admin
  $("#createTimeFrame").on('click', function () {
    var data = $("#formCreateTimeFrame").serialize();
    $.ajax({
      type: "POST",
      url: "/api/admin/createTimeFrame",
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
  $('#saveTimeFrame').click(function() {
    var formData = $('#editTimeFrameForm').serialize();
    $.ajax({
        type: "POST",
        url: "/api/admin/updateTimeFrame",  
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

//Delete 
function deleteTimeFrame(timeFrameId) {
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
            url: "/api/admin/deleteTimeFrame",
            data: {
                id: timeFrameId
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

