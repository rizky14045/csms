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
        <h4 class="fs-18 fw-semibold m-0">Prakualifikasi</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Prakualifikasi</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-end pe-3 pt-3">
                <a href="{{route('user.praqualification.create')}}" class="btn btn-success">Tambah Data</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Kode Supplier</th>
                                <th scope="col">Nama Perusahaan</th>
                                <th scope="col">NPWP</th>
                                <th scope="col">Nama Direktur</th>
                                <th scope="col">Telp</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Tanggal Submit Dokumentasi Prakualifikasi</th>
                                <th scope="col">Dokumen</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>V03166</td>
                                <td>DITAMA NASTARI GEMILANG. CV</td>
                                <td>812605434432000</td>
                                <td>Rezza Prawiratama</td>
                                <td></td>
                                <td>PT PLN NP UP MUARA KARANG</td>
                                <td>30-12-2022</td>
                                <td><a href="#" class="btn btn-link">Download</a></td>
                                <td>LOLOS CSMS</td>
                                <td>
                                    <a href="{{route('user.praqualification.edit')}}" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

