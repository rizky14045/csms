<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Login CSMS - PLN Nusantara Power</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('logo.ico')}}">
    
        <!-- App css -->
        <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
    
        <!-- Icons -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    
    </head>
   
    <body class="bg-white">
        <!-- Begin page -->
        <div class="account-page">
            <div class="container-fluid p-0">
                <div style="background: url({{asset('bg.jpg')}}); background-size: cover;background-repeat: no-repeat;">
                    <div class="row d-flex align-items-center justify-content-center vh-100 w-100">
                        <div class="col-md-4 col-xl-4 shadow-sm">
                            <div class="card p-4">
                                <div class="card-body">
                                    <div class="text-center">
                                        <img src="{{asset('logo.png')}}" alt="logo-dark" class="mx-auto pb-1" height="50" />
                                        <h5 class="">Sign In</h5>
                                    </div>
                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            {!! \Session::get('success') !!}
                                        </div>
                                    @endif
            
                                    @if (\Session::has('danger'))
                                        <div class="alert alert-danger">
                                            {!! \Session::get('danger') !!}
                                        </div>
                                    @endif
                                    <form action="{{route('admin.getLogin')}}" method="POST">
                                        @csrf
                                        <div class="form-group pb-3">
                                            <label class="form-label" for="email-id">Email address</label>
                                            <input type="email" class="form-control mb-0" id="email-id" placeholder="Enter email" name="email">
                                        </div>
                                        <div class="form-group pb-3">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" class="form-control mb-0" id="password" placeholder="Enter password" name="password">
                                        </div>
                                        <div class="text-center pb-3">
                                            <button type="submit" class="btn btn-info">Login</button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- END wrapper -->

        <!-- Vendor -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('assets/libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('assets/libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>

        <!-- App js-->
        <script src="{{asset('assets/js/app.js')}}"></script>
        @include('sweetalert::alert')
        
    </body>
</html>