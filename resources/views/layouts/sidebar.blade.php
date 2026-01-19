<div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="{{ route('dashboard') }}" class="logo logo-dark">
        <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
        </span>
    </a>
    <!-- Light Logo-->
    <a href="{{ route('dashboard') }}" class="logo logo-light">
        <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="17">
        </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
        <i class="ri-record-circle-line"></i>
    </button>
</div>

<div id="scrollbar">
    <div class="container-fluid">
        <div id="two-column-menu"></div>
        <ul class="navbar-nav" id="navbar-nav">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('dashboard') }}">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboard">Dashboard</span>
                </a>
            </li>

            <!-- Define Section -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarDefine" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDefine">
                    <i class="ri-settings-4-line"></i> <span data-key="t-define">Define</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarDefine">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.services.base') }}" class="nav-link">Base Services</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.services.addon') }}" class="nav-link">Add-on Services</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.measurement-attributes.index') }}" class="nav-link">Measurement Attributes</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.design-catalog.index') }}" class="nav-link">Design Catalog</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Customers -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('Dashboard.customers.index') }}">
                    <i class="ri-user-3-line"></i> <span data-key="t-customers">Customers</span>
                </a>
            </li>

            <!-- Measurements -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('Dashboard.measurements.index') }}">
                    <i class="ri-ruler-line"></i> <span data-key="t-measurements">Measurements</span>
                </a>
            </li>

            <!-- Bookings -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarBookings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarBookings">
                    <i class="ri-calendar-check-line"></i> <span data-key="t-bookings">Bookings</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarBookings">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.bookings.create') }}" class="nav-link">New Booking</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.bookings.index') }}" class="nav-link">All Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.bookings.today') }}" class="nav-link">Today's Bookings</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Payments -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarPayments" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPayments">
                    <i class="ri-money-dollar-circle-line"></i> <span data-key="t-payments">Payments</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarPayments">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.payments.create') }}" class="nav-link">Add Payment</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.payments.index') }}" class="nav-link">All Payments</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.payments.today') }}" class="nav-link">Today's Payments</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('Dashboard.payments.pending') }}" class="nav-link">Pending Payments</a>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</div>