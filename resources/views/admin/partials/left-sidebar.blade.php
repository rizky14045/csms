<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="{{route('admin.home.index')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('logo.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('logo.png')}}" alt="" height="45">
                    </span>
                </a>
                <a href="{{route('admin.home.index')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('logo.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('logo.png')}}" alt="" height="45">
                    </span>
                </a>
            </div>

            <ul id="side-menu">
                <li>
                    <a href="{{route('admin.home.index')}}" class="tp-link">
                        <i data-feather="home"></i>
                        <span> Home </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.monthly-audit.index')}}" class="tp-link">
                        <i data-feather="user"></i>
                        <span> Audit Bulanan </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.assesment.index')}}" class="tp-link">
                        <i data-feather="user"></i>
                        <span> Assesment </span>
                    </a>
                </li>
                <li>
                    <a href="#sidebarBulanan" data-bs-toggle="collapse">
                        <i data-feather="briefcase"></i>
                        <span> Sistem Keamanan </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarBulanan">

                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin.marturity.index')}}" class="tp-link">Marturity Level</a>
                            </li>
                            <li>
                                <a href="{{route('admin.keamanan.index')}}" class="tp-link">Kemanan KPI</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarMasterData" data-bs-toggle="collapse">
                        <i data-feather="database"></i>
                        <span> Master Data </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMasterData">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin.unit.index')}}" class="tp-link">Unit</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>