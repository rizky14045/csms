@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">BUJP / Vendor</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">BUJP / Vendor</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            @can('create.user.vendor')
            <div class="d-flex justify-content-end pe-3 pt-3">
                <a href="{{route('user.vendor.create')}}" class="btn btn-success">Tambah Data</a>
            </div>
            @endcan
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">NPWP</th>
                                <th scope="col">Email</th>
                                <th scope="col">No Kontrak</th>
                                <th scope="col">Mulai Kontrak</th>
                                <th scope="col">Akhir Kontrak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $vendor)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$vendor->name}}</td>
                                    <td>{{$vendor->bujp_profile->npwp ?? ''}}</td>
                                    <td>{{$vendor->email}}</td>
                                    <td>{{$vendor->vendor->contract_number ?? ''}}</td>
                                    <td>{{$vendor->vendor->start_date ? \Carbon\Carbon::parse($vendor->vendor->start_date)->format('d-m-Y') : ''}}</td>
                                    <td>{{$vendor->vendor->end_date ? \Carbon\Carbon::parse($vendor->vendor->end_date)->format('d-m-Y') : ''}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$vendors->links()}}
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

