@extends('user.layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Atribut</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Atribut</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-end pe-3 pt-3">
                <a href="{{route('user.attribute.create')}}" class="btn btn-success">Tambah Data</a>
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status Kepemilikan</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Jumlah Standar Kontrak</th>
                                <th scope="col">Tipe</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $attribute)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$attribute->name}}</td>
                                    <td>{{$attribute->status_ownership}}</td>
                                    <td>{{$attribute->unit}}</td>
                                    <td>{{$attribute->standard_contract}}</td>
                                    <td>{{$attribute->type_attribute}}</td>
                                    <td class="text-center">
                                        <a href="{{route('user.attribute.edit',['id'=>$attribute->id])}}" class="btn btn-success btn-sm">Edit</a>
                                        <form action="{{route('user.attribute.destroy',['id'=>$attribute->id])}}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$attributes->links()}}
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

