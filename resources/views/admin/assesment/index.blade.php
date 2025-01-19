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
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
         
            <div class="card-body">
                <label for="" class="form-label">Cari Data</label>
                <div class="d-flex gap-3">
                    <div class="mb-3 col-md-3">
                        <input type="date" class="form-control d-inline" id="exampleFormControlInput1">
                    </div>
                    <div class="button-search">
                        <button type="button" class="btn btn-primary d-inline">Cari</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Unit</th>
                                <th scope="col">NPWP</th>
                                <th scope="col">Nama Perusahaan</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Triwulan</th>
                               <th scope="col">Tanggal Kirim</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assesments as $assesment)
                                
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$assesment->unit->name}}</td>
                                    <td>{{$assesment->vendor->npwp}}</td>
                                    <td>{{$assesment->vendor->name}}</td>
                                    <td>{{$assesment->date}}</td>
                                    <td>{{$assesment->triwulan}}</td>
                                    <td>{{$assesment->send_date}}</td>
                                    <td>
                                        <a href="{{route('admin.assesment.show',['assesmentId'=>$assesment->id])}}" class="btn btn-info btn-sm">Show</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

