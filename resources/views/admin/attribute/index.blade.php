@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Atribut Administrasi</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Atribut Administrasi</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-end pe-3 pt-3">
                @can('create.attribute')
                <a href="{{route('admin.attribute.create')}}" class="btn btn-primary">Tambah Data</a>
                @endcan
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
                                @canany(['edit.attribute', 'delete.attribute'])
                                <th scope="col">Action</th>
                                @endcanany
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
                                    @canany(['edit.attribute', 'delete.attribute'])                                        
                                    <td class="text-center">
                                        @can('edit.attribute')
                                        <a href="{{route('admin.attribute.edit',['attribute'=>$attribute->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.attribute')
                                        <form
                                            id="delete-attribute-{{ $attribute->id }}"
                                            action="{{route('admin.attribute.destroy',['attribute'=>$attribute->id])}}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-attribute-{{ $attribute->id }}',
                                                    'Atribut akan dihapus.'
                                                )"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                    @endcanany
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

