<!DOCTYPE html>
<html lang="en">

    @include('admin.partials.head')
    @yield('styles')

    <body data-menu-color="dark" data-sidebar="default">

        <!-- Begin page -->
        <div id="app-layout">

            <!-- Topbar Start -->
            @include('admin.partials.header')
            <!-- end Topbar -->

            <!-- Left Sidebar Start -->
            @include('admin.partials.left-sidebar')
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
                @include('admin.partials.footer')
                <!-- end Footer -->
                
            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->
        @include('admin.partials.scripts')
        @yield('scripts')
        @include('sweetalert::alert')

    </body>
</html>