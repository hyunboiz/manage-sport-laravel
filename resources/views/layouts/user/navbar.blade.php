    <!-- Topbar Start -->
    <div class="container-fluid bg-dark">
        <div class="row py-2 px-lg-5">
            <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center text-white">
                    <small><i class="fa fa-phone-alt mr-2"></i>+012 345 6789</small>
                    <small class="px-3">|</small>
                    <small><i class="fa fa-envelope mr-2"></i>info@example.com</small>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-white px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-white px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-white pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
            <a href="/" class="navbar-brand ml-lg-3">
                <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-heartbeat" aria-hidden="true"></i>LoveSport</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                <div class="navbar-nav mx-auto py-0">
                    {{-- <a href="/" class="nav-item nav-link active">Trang chủ</a> --}}
                    </div>
                </div>
                @auth('web')
                <a href="{{ route('user.cart') }}"
                    class="btn btn-outline-primary py-2 px-3 position-relative d-none d-lg-block mr-2"
                    id="cart-btn">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="badge badge-danger position-absolute"
                            id="cart-count"
                            style="top: -6px; right: -6px;">0</span>
                    </a>
                    <a href="{{ route('user.profile') }}" class="btn btn-primary py-2 px-4 d-none d-lg-block"><i class="fa fa-user" aria-hidden="true"></i> {{ optional(Auth::guard('web')->user())->name }}</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary py-2 px-4 d-none d-lg-block"><i class="fa fa-user" aria-hidden="true"></i> Đăng nhập</a>
                @endguest
            </div>
        </nav>
    </div>
    <!-- Navbar End -->