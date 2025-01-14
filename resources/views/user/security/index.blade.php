@extends('user.layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Satuan Pengamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Satuan Pengamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-end pe-3 pt-3">
                <a href="{{route('user.security.create')}}" class="btn btn-success">Tambah Data</a>
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Unit Kerja</th>
                                <th scope="col">NID</th>
                                <th scope="col">Nomor REG KTA</th>
                                <th scope="col">Expired KTA</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Tempat Lahir</th>
                                <th scope="col">Tanggal Lahir</th>
                                <th scope="col">Kualifikasi</th>
                                <th scope="col">Pendidikan Terakhir</th>
                                <th scope="col">Note</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($securities as $security)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$security->name}}</td>
                                    <td>{{$security->gender}}</td>
                                    <td>{{$security->unit_work}}</td>
                                    <td>{{$security->nid}}</td>
                                    <td>{{$security->registration_number}}</td>
                                    <td>{{$security->expired_card_date}}</td>
                                    <td>{{$security->position}}</td>
                                    <td>{{$security->birth_place}}</td>
                                    <td>{{$security->birth_date}}</td>
                                    <td>{{$security->qualification}}</td>
                                    <td>{{$security->last_education}}</td>
                                    <td>{{$security->note}}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{route('user.security.edit',['id'=>$security->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{route('user.security.destroy',['id'=>$security->id])}}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$securities->links()}}
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

