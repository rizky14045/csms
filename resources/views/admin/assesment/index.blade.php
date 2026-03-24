@extends('layout.app')
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
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
         
            <div class="card-body">
                <form action="{{ route('admin.assesment.index') }}">
                    <label class="form-label">Cari Data</label>
                    <div class="d-flex gap-3">
                        <div class="mb-3 col-md-3">
                            <input type="date" 
                                class="form-control" 
                                name="date" 
                                value="{{ request('date', '') }}">
                        </div>
                        <div class="button-search">
                            <button type="submit" class="btn btn-primary">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Unit</th>
                                <th scope="col">NPWP</th>
                                <th scope="col">Nama Perusahaan</th>
                                <th scope="col">Nomor Kontrak</th>
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
                                    <td>{{$assesment->bujp_profile->npwp}}</td>
                                    <td>{{$assesment->vendor->name}}</td>
                                    <td>{{$assesment->contract}}</td>
                                    <td>{{ \Carbon\Carbon::parse($assesment->date)->format('d-m-Y') }}</td>
                                    <td>{{$assesment->triwulan}}</td>
                                    <td>{{ \Carbon\Carbon::parse($assesment->send_date)->format('d-m-Y') }}</td>
                                    <td>
                                        <a href="{{route('admin.assesment.show',['assesment'=>$assesment->id])}}" class="btn btn-info btn-sm">Show</a>
                                        <a href="{{route('admin.assesment.report',['assesment'=>$assesment->id])}}" class="btn btn-success btn-sm">Report</a>
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

