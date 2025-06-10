@include('layouts.user.header')
<body>
    @yield('styles')
    
    @include('layouts.user.navbar')

    @yield('mainsection')

    @yield('scripts')

    @include('layouts.user.footer')
</body>
</html>
