@extends('admin.layout.app')
@section('styles')
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Daftar Pekerjaan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Daftar Pekerjaan</li>
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
                                <th scope="col">Pemasok</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Status Pekerjaan</th>
                                <th scope="col">Durasi</th>
                                <th scope="col">Status SWA</th>
                                <th scope="col">Resiko</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>1</td>
                                <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                <td>Jasa Perbaikan Mesin Gate</td>
                                <td>UPMK</td>
                                <td>Persiapan Implementasi</td>
                                <td>7</td>
                                <td></td>
                                <td>Resiko Rendah</td>
                                <td>
                                    <a href="{{route('admin.list-work.edit')}}" class="btn btn-primary btn-sm">Upload She Plan</a>
                                    <a href="#" class="btn btn-success btn-sm">She Plan</a>
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

