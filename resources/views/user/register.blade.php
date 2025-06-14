@extends('layouts.user.app')

@section('title', 'Đăng ký tài khoản')

@section('styles')

@endsection

@section('mainsection')
    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <img src="https://img.freepik.com/free-vector/enter-otp-concept-illustration_114360-7867.jpg?semt=ais_hybrid&w=740" width="356 " alt="">
                </div>
                <div class="col-lg-7">
                    <div class="section-title position-relative mb-4">
                        <h4 class="display-4">Register</h3>
                    </div>
                    <div class="contact-form">
                        <form id="registerForm">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control " placeholder="Enter Email" required="required">
                            </div>
                            <div class="form-group">
                                <label for="">Username</label>
                                <input type="text" name="username" class="form-control " placeholder="Enter Username" required="required">
                            </div>
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control " placeholder="Enter Name" required="required">
                            </div>
                            <div class="form-group">
                                <label for="">Hotline</label>
                                <input type="text" name="hotline" class="form-control " placeholder="Enter Hotline" required="required">
                            </div>
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="">Password</label>
                                    <input type="password" name="password" class="form-control " placeholder="Enter Password" required="required">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control " placeholder="Enter Confirm Password" required="required">
                                </div>
                            </div>
                            <div>
                                <a href="{{ route("auth.login") }} "><h6 class="d-inline-block position-relative text-secondary pb-2">Already have an account? Sign in now</h6></a>
                                <button class="btn btn-primary py-3 w-100" type="button" id="btnSignup">Sign Up</button>
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
$('#btnSignup').click(function() {

    var formData = $('#registerForm').serialize();
    $.ajax({
        type: "POST",
        url: "/customer/register",  
        data: formData,
        dataType: "JSON",
        success: function(response) {
          if(response.status == true){
            swal('success', response.message);
            setTimeout(() => window.location.href ='/auth/login', 2000);
          }else{
            swal('error', response.message);
          }
        }
    });
});
</script>

@endsection

