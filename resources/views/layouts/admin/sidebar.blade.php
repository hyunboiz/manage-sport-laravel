  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand d-flex align-items-center m-0" href=" https://demos.creative-tim.com/corporate-ui-dashboard/pages/dashboard.html " target="_blank">
        <span class="font-weight-bold text-lg">ADMIN DASHBOARD</span>
      </a>
    </div>
    <div class="collapse navbar-collapse px-4  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " href={{ route("admin.dashboard") }}>
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
              <i class="fa fa-bar-chart" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="{{ route("admin.adminstrator") }}">
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
             <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Admin</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="{{ route("admin.customer") }}">
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
             <i class="fa fa-user" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Customer</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="{{ route("admin.sport") }}">
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
             <i class="fa fa-globe" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Sport</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="{{ route("admin.type") }}">
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
             <i class="fa fa-wrench" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Type</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="{{ route("admin.paymentmethod") }}">
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
             <i class="fa fa-credit-card" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Payment</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="{{ route("admin.timeframe") }}">
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
             <i class="fa fa-clock-o" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Time Frame</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="{{ route("admin.field") }}">
            <div class="icon icon-shape icon-sm px-0 text-center d-flex align-items-center justify-content-center">
             <i class="fa fa-cube" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Manage Field</span>
          </a>
        </li>
        <li class="nav-item mt-2">
          <div class="d-flex align-items-center nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="ms-2" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
              <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
            </svg>
            <span class="font-weight-normal text-md ms-2">Manage Booking</span>
          </div>
        </li>
        <li class="nav-item border-start my-0 pt-2">
          <a class="nav-link position-relative ms-0 ps-2 py-2 " href="{{ route('admin.bookings.create') }}">
            <span class="nav-link-text ms-1">Create Booking</span>
          </a>
        </li>
        <li class="nav-item border-start my-0 pt-2">
          <a class="nav-link position-relative ms-0 ps-2 py-2 " href="{{ route('admin.booking') }}">
            <span class="nav-link-text ms-1">History Booking</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>