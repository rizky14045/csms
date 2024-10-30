@extends('user.layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Daftar Pekerjaan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Daftar Pekerjaan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="index.html" class="my-4">
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Judul Pekerjaan</label>
                            <input class="form-control" name="username" type="text" id="username" required="" placeholder="Masukan judul pekerjaan">
                        </div>
                        <div class="form-group mb-3">
                            <label for="province-select" class="form-label">Pilih Unit</label>
                            <select class="form-select" id="province-select">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="province-select" class="form-label">Status Pekerjaan</label>
                            <select class="form-select" id="province-select">
                                <option>Persiapan Implemntasi</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Lama Pekerjaan</label>
                            <input class="form-control" name="username" type="text" id="username" required="" placeholder="Masukan lama pekerjaan">
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label for="emailaddress" class="form-label">Attachment</label>
                            <input type="file" class="form-control" id="inputGroupFile01" accept=".pdf">
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('user.list-work.index')}}" class="btn btn-success"> Back</a>
                                    <button class="btn btn-primary" type="submit"> Tambah</button>
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

