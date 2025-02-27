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
                        <span> Laporan Bulanan </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.assesment.index')}}" class="tp-link">
                        <i data-feather="user"></i>
                        <span> Assesment BUJP </span>
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
                            <li>
                                <a href="{{route('admin.vulnerability.index')}}" class="tp-link">Kerawanan</a>
                            </li>
                            <li>
                                <a href="{{route('admin.attribute.index')}}" class="tp-link">Attribute</a>
                            </li>
                            <li>
                                <a href="{{route('admin.category-assesment.index')}}" class="tp-link">Assesment</a>
                            </li>
                            <li>
                                <a href="{{route('admin.marturity-area.index')}}" class="tp-link">Marturity</a>
                            </li>
                            <li>
                                <a href="{{route('admin.kpi-area.index')}}" class="tp-link">KPI</a>
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