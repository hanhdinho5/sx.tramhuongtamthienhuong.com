<style>
    .top-header {
        height: 40px;
        z-index: 100;
    }
</style>
<header
    class="top-header w-100 d-flex position-fixed top-0 bg-primary text-white justify-content-between align-items-center px-2">
    <div>Người dùng: {{ Auth::user()->full_name }} | {{ $setting ? $setting->company_name : '' }}</div>
    <a href="tel:{{ $setting ? $setting->phone : '' }}" class="text-white"><span>Hỗ trợ: {{ $setting ? $setting->phone : '' }}</span></a>
</header>
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('admin.home') }}" class="logo d-flex align-items-center">
            <img src="{{ $setting ? $setting->logo : '' }}" alt="">
            <span class="d-none d-lg-block">{{ $setting ? $setting->home_name : '' }}</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->avatar }}" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->full_name }}</span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ Auth::user()->full_name }}</h6>
                        <span>{{ Auth::user()->username }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile.index') }}">
                            <i class="bi bi-person"></i>
                            <span>Trang cá nhân</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('auth.logout') }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header>
