@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Securepedia</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Securepedia</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-end pe-3 pt-3">
                @can('create.securepedia.admin')
                <a href="{{route('admin.securepedia.create')}}" class="btn btn-primary">Tambah Data</a>
                @endcan
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Tipe</th>
                                <th scope="col">File Pendukung</th>
                                @canany(['edit.securepedia.admin', 'delete.securepedia.admin'])
                                <th scope="col">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($securepedias as $securepedia)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $securepedia->title }}</td>
                                    <td>{{ $securepedia->type }}</td>
                                    <td>
                                        @if ($securepedia->file)
                                            <a href="{{ asset('uploads/securepedia/' . $securepedia->file) }}" target="_blank" class="btn btn-primary">Lihat File</a>
                                        @else
                                            Tidak ada file
                                        @endif
                                    </td>
                                    @canany(['edit.securepedia.admin', 'delete.securepedia.admin'])
                                    <td class="text-center">
                                        @can('edit.securepedia.admin')
                                        <a href="{{route('admin.securepedia.edit',['securepedia'=>$securepedia->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.securepedia.admin')
                                        <form
                                            id="delete-securepedia-{{ $securepedia->id }}"
                                            action="{{route('admin.securepedia.destroy',['securepedia'=>$securepedia->id])}}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-securepedia-{{ $securepedia->id }}',
                                                    'Securepedia akan dihapus.'
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
                    {{$securepedias->links()}}
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

