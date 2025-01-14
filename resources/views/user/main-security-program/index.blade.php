@extends('user.layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Program Keamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Program Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-between pe-3 pt-3 ps-3">
                <a href="{{route('user.security-program.index')}}" class="btn btn-danger">Back</a>
                <div class="right">

                    <a href="{{route('user.main-security-program.visual',['programId'=>$programId])}}" class="btn btn-info">Timeline</a>
                    <a href="{{route('user.main-security-program.create',['programId'=>$programId])}}" class="btn btn-success">Tambah Data</a>
                </div>
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Program</th>
                                <th scope="col">Planning Program</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mains as $main)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$main->program_name}}</td>
                                    <td> 
                                        <span>Mulai  :Bulan {{$main->start_month}} - Minggu : {{$main->start_week}} </span>
                                        <br>
                                        <span>Selesai  :Bulan {{$main->end_month}} - Minggu : {{$main->end_week}} </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('user.main-security-program.edit',['programId'=>$programId,'id'=>$main->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{route('user.main-security-program.destroy',['programId'=>$programId,'id'=>$main->id])}}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$mains->links()}}
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

