@extends('layouts.user.app')

@section('title', 'Trang cá nhân')

@section('styles')
 <style>
    body {
      background-color: #ffffff;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar-custom {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    }

    .navbar-custom .nav-link {
      color: #333;
      font-weight: 500;
      margin-right: 1rem;
    }

    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link.active {
      color: #007BFF;
      border-bottom: 2px solid #007BFF;
    }

    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #e0e0e0;
      margin-bottom: 1rem;
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .form-control:focus {
      border-color: #00bcd4;
      box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.25);
    }

    .profile-section {
      max-width: 800px;
      margin: 0 auto;
      padding-top: 3rem;
    }
  </style>
@endsection

@section('mainsection')
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-primary" href="#">Trang cá nhân</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                <a class="nav-link" href="{{ route('user.profile') }}"><i class="fa fa-user" aria-hidden="true"></i> Thông tin</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('user.history') }}">Lịch sử</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" href="{{ route('user.password') }}">Đổi mật khẩu</a>
                </li>
                <li class="nav-item">
                 <a class="nav-link" href="/logout">Đăng xuất</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

<!-- Profile Section -->
<div class="container profile-section">
  <div class="text-center">
    <img src="{{ asset('theme_user/img/user.png') }}" alt="Avatar" class="profile-img">
    <h4 class="mb-0">{{ optional(Auth::guard('web')->user())->name }}</h4>
    <p class="text-muted">{{ optional(Auth::guard('web')->user())->email }}</p>
  </div>

  <div class="card p-4 mt-4">
    <h5 class="text-info mb-3">Đổi mật khẩu</h5>
    <form id="profileForm">
      <div class="form-row">
        <div class="form-group col-md-12">
            <input type="hidden" name="id" value="{{ optional(Auth::guard('web')->user())->id }}">
          <label for="fullName">Mật khẩu cũ</label>
          <input type="passworđ" class="form-control" name="oldpassword" id="oldpassword" value="">
        </div>
        <div class="form-group col-md-12">
          <label for="fullName">Mật khẩu mới</label>
          <input type="passworđ" class="form-control" name="newpassword" id="newpassword" value="" >
        </div>
        <div class="form-group col-md-12">
          <label for="fullName">Mật khẩu xác nhận</label>
          <input type="passworđ" class="form-control" name="confirm_password" id="confirm_password" value="" >
        </div>
        
        </div>
      <button type="button" id="btnUpdate" class="btn btn-info btn-block">Thay đổi</button>
    </form>
  </div>
</div>
@endsection

@section('scripts')

<script>
  $('#btnUpdate').click(function() {
    var formData = $('#profileForm').serialize();
     swal('success', 'Đổi mật khẩu thành công');
    // $.ajax({
    //     type: "POST",
    //     url: "/api/customer/updateInformation",  
    //     data: formData,
    //     dataType: "JSON",
    //     success: function(response) {
    //       if(response.status == true){
    //         swal('success', response.message);
    //         setTimeout(() => window.location.href ='/', 2000);
    //       }else{
    //         swal('error', response.message);
    //       }
    //     }
    // });
});
</script>

@endsection

