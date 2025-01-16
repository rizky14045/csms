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
                <div class="row align-items-center g-0">
                    <div class="col-xl-7">
                        <div class="account-page-bg p-md-5 p-4">
                            <div class="text-center">
                                <h3 class="text-dark mb-3 pera-title">Quick, Effective, and Productive With Tapeli Admin Dashboard</h3>
                                <div class="auth-image">
                                    <img src="assets/images/authentication.svg" class="mx-auto img-fluid"  alt="images">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="row">
                            <div class="col-md-7 mx-auto">
                                <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                                    <div class="mb-4 p-0 text-center">
                                        <img src="{{asset('logo.png')}}" alt="logo-dark" class="mx-auto" height="50" />
                                        <h6 class="mt-2">Sign in to SIDAK</h6>
                                    </div>
    
                                    <div class="pt-0">
                                        <form action="{{route('bujp.getLogin')}}" class="my-4" method="POST">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="emailaddress" class="form-label">Email</label>
                                                <input class="form-control" type="email" id="emailaddress" required="" placeholder="Masukan email" name="email">
                                                @if($errors->has('email'))
                                                    <div class="error text-danger">{{ $errors->first('email') }}</div>
                                                @endif
                                            </div>
                
                                            <div class="form-group mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control" type="password" required="" id="password" placeholder="Masukan password" name="password">
                                                @if($errors->has('password'))
                                                    <div class="error text-danger">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                
                                            <div class="form-group d-flex mb-3">
                                                <div class="col-sm-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" value="1">
                                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mb-0 row">
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary" type="submit"> Log In </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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