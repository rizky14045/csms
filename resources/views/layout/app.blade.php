<!DOCTYPE html>
<html lang="en">

    @include('layout.head')
    @yield('styles')

    <body data-menu-color="dark" data-sidebar="default">

        <!-- Begin page -->
        <div id="app-layout">

            <!-- Topbar Start -->
            @include('layout.header')
            <!-- end Topbar -->

            <!-- Left Sidebar Start -->
            @include('components.sidebar')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-xxxl">
                        @yield('content')
                    </div> <!-- container-fluid -->
                </div> <!-- content -->

                <!-- Footer Start -->
                @include('layout.footer')
                <!-- end Footer -->
                
            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->
        @include('sweetalert::alert')
        @include('layout.script')
        @yield('scripts')
    </body>
</html>