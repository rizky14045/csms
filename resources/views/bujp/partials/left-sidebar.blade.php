<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="{{route('bujp.home.index')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('logo.png')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('logo.png')}}" alt="" height="45">
                    </span>
                </a>
                <a href="{{route('bujp.home.index')}}" class="logo logo-dark">
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
                    <a href="{{route('bujp.home.index')}}" class="tp-link">
                        <i data-feather="home"></i>
                        <span> Home </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('bujp.assesment.index')}}" class="tp-link">
                        <i data-feather="user"></i>
                        <span> Assesment </span>
                    </a>
                </li>
                
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>