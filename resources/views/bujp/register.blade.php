<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Register CSMS - PLN Nusantara Power</title>
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
                    <div class="col-xl-5 d-none d-xl-block">
                        <div class="account-page-bg p-md-5 p-4">
                            <div class="text-center">
                                <h3 class="text-dark mb-3 pera-title">Quick, Effective, and Productive With Tapeli Admin Dashboard</h3>
                                <div class="auth-image">
                                    <img src="assets/images/authentication.svg" class="mx-auto img-fluid"  alt="images">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bagian kanan yang akan menjadi scrollable -->
                    <div class="col-xl-7 col-sm-12">
                        <div class="scrollable-content" style="max-height: 100vh">
                            <div class="row pt-3">
                                <div class="col-md-10 mx-auto">
                                    <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                                        <div class="mb-4 p-0">
                                            <a href="index.html" class="auth-logo">
                                                <img src="{{asset('logo.png')}}" alt="logo-dark" class="mx-auto" height="42" />
                                            </a>
                                        </div>
                                        <h5 class="text-center pb-1">Registration</h5>
                                        <div class="pt-0">
                                            <form action="index.html" class="my-4">
                                                <!-- Formulir Pendaftaran -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="username" class="form-label">Nomor NPWP Pemasok</label>
                                                            <input class="form-control" name="username" type="text" id="username" required="" placeholder="Masukan NPWP">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="username" class="form-label">Kode Supplier</label>
                                                            <input class="form-control" name="username" type="text" id="username" required="" placeholder="Masukan kode supplier">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="username" class="form-label">Nama Perusahaan</label>
                                                    <input class="form-control" name="username" type="text" id="username" required="" placeholder="Masukan nama perusahaan">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="emailaddress" class="form-label">Nama Direktur</label>
                                                    <input class="form-control" type="email" id="emailaddress" required="" placeholder="Masukan nama direktur">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="emailaddress" class="form-label">Alamat</label>
                                                    <textarea class="form-control" id="example-textarea" rows="5" spellcheck="false"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="username" class="form-label">Kode Pos</label>
                                                            <input class="form-control" name="username" type="text" id="username" required="" placeholder="Masukan Kode Pos">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="username" class="form-label">Nomor Telepon</label>
                                                            <input class="form-control" name="username" type="text" id="username" required="" placeholder="Masukan nomor telepon">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="province-select" class="form-label">Pilih Provinsi</label>
                                                            <select class="form-select" id="province-select">
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="username" class="form-label">Email Perusahaan</label>
                                                            <input class="form-control" name="username" type="email" id="username" required="" placeholder="Masukan email perusahaan">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="province-select" class="form-label">Pilih Unit Pendaftaran CSMS</label>
                                                    <select class="form-select" id="province-select">
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                {{-- <hr>
                                                <div class="form-group mb-3">
                                                    <label for="province-select" class="form-label">
                                                        Apakah perusahaan saudara mempunyai kebijakan LK3 tertulis yang sudah
                                                        ditandatangani oleh pimpinan tertinggi perusahaan dan disosialisasikan?
                                                        </label>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio" name="input1" id="flexRadioDefault1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Ya
                                                        </label>
                                                    </div>  
                                                    <p> <span class="text-danger">* </span>lampirkan kebijakan LK3 yang dapat diterapkan saat ini</p>
                                                    <div class="mb-0 col-md-6">
                                                        <div class="input-group">
                                                            <input type="file" class="form-control" id="inputGroupFile01" accept=".pdf">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group mb-3">
                                                    <label for="province-select" class="form-label">
                                                        Apakah perusahaan saudara mempunyai kebijakan LK3 tertulis yang sudah
                                                        ditandatangani oleh pimpinan tertinggi perusahaan dan disosialisasikan?
                                                        </label>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio" name="input2" id="flexRadioDefault1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Ya
                                                        </label>
                                                    </div>  
                                                    <p> <span class="text-danger">* </span>lampirkan kebijakan LK3 yang dapat diterapkan saat ini</p>
                                                    <div class="mb-0 col-md-6">
                                                        <div class="input-group">
                                                            <input type="file" class="form-control" id="inputGroupFile01" accept=".pdf">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group mb-3">
                                                    <label for="province-select" class="form-label">
                                                        Apakah perusahaan saudara mempunyai kebijakan LK3 tertulis yang sudah
                                                        ditandatangani oleh pimpinan tertinggi perusahaan dan disosialisasikan?
                                                        </label>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio" name="input3" id="flexRadioDefault1">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            Ya
                                                        </label>
                                                    </div>  
                                                    <p> <span class="text-danger">* </span>lampirkan kebijakan LK3 yang dapat diterapkan saat ini</p>
                                                    <div class="mb-0 col-md-6">
                                                        <div class="input-group">
                                                            <input type="file" class="form-control" id="inputGroupFile01" accept=".pdf">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <div class="d-grid">
                                                            <button class="btn btn-primary" type="submit"> Register</button>
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
                    <!-- Akhir bagian kanan yang scrollable -->
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
        
    </body>
</html>