@extends('admin.layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Daftar Pemasok</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Daftar Pemasok</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>V03166</td>
                                <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                <td>812605434432000</td>
                                <td>Rezza Prawiratama</td>
                                <td></td>
                                <td>PT PLN NP UP MUARA KARANG</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

