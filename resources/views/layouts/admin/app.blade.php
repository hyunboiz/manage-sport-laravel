@include('layouts.admin.header')
<body class="g-sidenav-show  bg-gray-100">
    @yield('styles')
    
    @include('layouts.admin.sidebar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container-fluid py-4 px-5">
    @yield('mainsection')

    @yield('scripts')

    @include('layouts.admin.footer')
    </div>
  </main>

</body>
</html>
