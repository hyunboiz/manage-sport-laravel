@include('layouts.user.header')
<body>
    @yield('styles')
    
    @include('layouts.user.navbar')

    @yield('mainsection')

    @yield('script')

    @include('layouts.user.footer')
</body>
</html>
