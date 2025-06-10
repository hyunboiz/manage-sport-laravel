@extends('layouts.user.app')

@section('title', 'Đăng nhập tài khoản')

@section('styles')

@endsection

@section('mainsection')
    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <img src="https://img.freepik.com/free-vector/computer-login-concept-illustration_114360-7862.jpg?semt=ais_hybrid&w=740" width="356 " alt="">
                </div>
                <div class="col-lg-7">
                    <div class="section-title position-relative mb-4">
                        <h4 class="display-4">Login</h3>
                    </div>
                    <div class="contact-form">
                        <form id="loginForm">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" name="email" class="form-control " placeholder="Enter Email" required="required">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control " placeholder="Enter Password" required="required">
                            </div>
                            <div>
                                <a href="{{ route("auth.register") }} "><h6 class="d-inline-block position-relative text-secondary pb-2">Don't have an account? Sign up now</h6></a>
                                <button class="btn btn-primary py-3 w-100" type="button" id="btnLogin">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection

@section('scripts')

<script>
  $('#btnLogin').click(function() {
    var formData = $('#loginForm').serialize();
    $.ajax({
        type: "POST",
        url: "/customer/login",  
        data: formData,
        dataType: "JSON",
        success: function(response) {
          if(response.status == true){
            swal('success', response.message);
            setTimeout(() => window.location.href ='/', 2000);
          }else{
            swal('error', response.message);
          }
        }
    });
});
</script>

@endsection

