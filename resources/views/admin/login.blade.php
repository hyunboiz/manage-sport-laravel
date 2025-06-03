@include('layouts.admin.header')
<body class="">
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-black text-dark display-6">Welcome back</h3>
                </div>
                <div class="card-body">
                  <form role="form">
                    <label>Email Address</label>
                    <div class="mb-3">
                      <input type="email" class="form-control" placeholder="Enter your email address" aria-label="Email" aria-describedby="email-addon">
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                      <input type="email" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                    </div>
                    <div class="text-center">
                      <button type="button" class="btn btn-dark w-100 mt-4 mb-3">Sign in</button>
                    </div>
                  </form>
                </div>

              </div>
            </div>
            <div class="col-md-6">
              <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8" style="background-image:url('https://img.freepik.com/free-vector/mobile-login-concept-illustration_114360-83.jpg?semt=ais_hybrid&w=740')">
                  <div class="blur mt-12 p-4 text-center border border-white border-radius-md position-absolute fixed-bottom m-4">
                    <h2 class="mt-3 text-dark font-weight-bold">Đăng nhập quản trị.</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@include('layouts.admin.footer')
</body>
</html>
