@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Fasilitas Umum</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Fasilitas Umum</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            {{-- @can('create.unit.admin') --}}
            <div class="d-flex justify-content-end pe-3 pt-3">
                <a href="{{route('user.fasum.create')}}" class="btn btn-success">Tambah Data</a>
            </div>
            {{-- @endcan --}}
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Fasilitas</th>
                                <th scope="col">Tipe</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fasums as $fasum)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$fasum->name}}</td>
                                    <td>{{$fasum->type}}</td>
                                    <td>{{$fasum->address}}</td>
                                    <td class="text-center">
                                        {{-- @can('edit.fasum.admin') --}}
                                        <a href="{{route('user.fasum.edit',['fasum'=>$fasum->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        {{-- @can('delete.fasum.unit') --}}
                                        <form
                                            id="delete-fasum-{{ $fasum->id }}"
                                            action="{{ route('user.fasum.destroy',['fasum'=>$fasum->id]) }}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-fasum-{{ $fasum->id }}',
                                                    'Fasilitas umum akan dihapus.'
                                                )"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                        {{-- @endcan --}}
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$fasums->links()}}
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

