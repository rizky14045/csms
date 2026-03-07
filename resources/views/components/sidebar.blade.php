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
                                <a href="{{route('admin.marturity.index')}}" class="tp-link">Maturity Level</a>
                            </li>
                            <li>
                                <a href="{{route('admin.keamanan.index')}}" class="tp-link">Keamanan KPI</a>
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
                            {{-- <li>
                                <a href="{{route('admin.admin.index')}}" class="tp-link">Admin</a>
                            </li> --}}
                            {{-- <li>
                                <a href="{{route('admin.unit.index')}}" class="tp-link">Unit</a>
                            </li> --}}
                            @can('view.vulnerability')
                            <li>
                                <a href="{{route('admin.vulnerability.index')}}" class="tp-link">Kerawanan</a>
                            </li>
                            @endcan
                            @can('view.attribute')
                            <li>
                                <a href="{{route('admin.attribute.index')}}" class="tp-link">Attribute</a>
                            </li>
                            @endcan
                            @can('view.category.assesment')
                            <li>
                                <a href="{{route('admin.category-assesment.index')}}" class="tp-link">Assesment</a>
                            </li>
                            @endcan
                            @can('view.marturity.area')
                            <li>
                                <a href="{{route('admin.marturity-area.index')}}" class="tp-link">Marturity</a>
                            </li>
                            @endcan
                            @can('view.kpi.area')
                            <li>
                                <a href="{{route('admin.kpi-area.index')}}" class="tp-link">KPI</a>
                            </li>
                            @endcan
                            @can('view.attribute.unit')
                            <li>
                                <a href="{{route('user.attribute.index')}}" class="tp-link">Attribute</a>
                            </li>
                            @endcan
                            @can('view.security.unit')
                            <li>
                                <a href="{{route('user.security.index')}}" class="tp-link">Satuan Pengaman</a>
                            </li>
                            @endcan
                            <li>
                                <a href="{{route('admin.audit-smp.index')}}" class="tp-link">Audit SMP</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @canany(['view.user', 'view.role', 'view.permission'])
                <li>
                    <a href="#sidebarSetting" data-bs-toggle="collapse">
                        <i data-feather="settings"></i>
                        <span> Setting </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSetting">
                        <ul class="nav-second-level">
                            @can('view.user')
                            <li>
                                <a href="{{route('users.index')}}" class="tp-link">User Management</a>
                            </li>
                            @endcan
                            @can('view.role')
                            <li>
                                <a href="{{route('roles.index')}}" class="tp-link">Role Management</a>
                            </li>
                            @endcan
                            @can('view.permission')
                            <li>
                                <a href="{{route('permissions.index')}}" class="tp-link">Permission Management</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>