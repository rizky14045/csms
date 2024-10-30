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
                    <a href="{{route('admin.praqualification.index')}}" class="tp-link">
                        <i data-feather="check-circle"></i>
                        <span> Prakualifikasi </span>
                    </a>
                </li>
                <li>
                    <a href="#sidebarPemasok" data-bs-toggle="collapse">
                        <i data-feather="briefcase"></i>
                        <span> Pemasok </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarPemasok">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin.evaluation.index')}}" class="tp-link">Penilaian</a>
                            </li>
                            <li>
                                <a href="{{route('admin.supplier.index')}}" class="tp-link">Daftar Pemasok</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarSeleksi" data-bs-toggle="collapse">
                        <i data-feather="clipboard"></i>
                        <span> Seleksi </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSeleksi">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin.list-work.index')}}" class="tp-link">Daftar Pekerjaan</a>
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
                                <a href="{{route('admin.faq.index')}}" class="tp-link">Faq</a>
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