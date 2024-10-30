@extends('admin.layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Prakualifikasi</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Data Prakualifikasi</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="index.html" class="my-4">
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">

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
                        <div class="form-group mb-3 col-md-6">
                            <label for="emailaddress" class="form-label">File Prakualifikasi</label>
                            <input type="file" class="form-control" id="inputGroupFile01" accept=".pdf">
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('admin.praqualification.index')}}" class="btn btn-success"> Back</a>
                                    <button class="btn btn-primary" type="submit">Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

