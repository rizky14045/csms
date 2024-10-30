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
        <h4 class="fs-18 fw-semibold m-0">Profile</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="text-end">
                    <button type="button" class="btn btn-success btn-sm"> <span class="mdi mdi-printer-alert"></span> Cetak Profil</button>
                </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Kode Supplier</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Nomor NPWP</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Nama Perusahaan</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Nama Direktur</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Email</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Telp</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Text</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Text</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Text</label>
                                <input type="text" id="simpleinput" class="form-control">
                            </div>
                        </div>
                    </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

