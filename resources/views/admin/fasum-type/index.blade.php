@extends('layout.app')
@section('styles')

@stop
@section('content')


    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Tipe Fasilitas Umum</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tipe Fasilitas Umum</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                @can('create.fasumtype.admin')
                    <div class="d-flex justify-content-end pe-3 pt-3">
                        <a href="{{ route('admin.fasum-type.create') }}" class="btn btn-success">Tambah Data</a>
                    </div>
                @endcan
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kode Warna</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($types as $type)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $type->name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <span
                                                    style="
                display: inline-block;
                width: 25px;
                height: 25px;
                background-color: {{ $type->color_code }};
                border: 1px solid #ccc;
                border-radius: 4px;
            ">
                                                </span>
                                                <span>{{ $type->color_code }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @can('edit.fasumtype.admin')
                                                <a href="{{ route('admin.fasum-type.edit', ['fasumType' => $type->id]) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                            @endcan
                                            @can('delete.fasumtype.admin')
                                                <form id="delete-fasum-type-{{ $type->id }}"
                                                    action="{{ route('admin.fasum-type.destroy', ['fasumType' => $type->id]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete(
                                                    'delete-fasum-type-{{ $type->id }}',
                                                    'Tipe fasilitas umum akan dihapus.'
                                                )">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $types->links() }}
                    </div>

                </div> <!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row -->
@endsection
