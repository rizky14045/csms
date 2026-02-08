@extends('layout.app')

@section('styles')
@stop

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Permission Management</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Permission Management</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            @can('create.permission')
            <div class="d-flex justify-content-end pe-3 pt-3">
                <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                    Tambah Data
                </a>
            </div>
            @endcan

            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                @canany(['edit.permission', 'delete.permission'])
                                <th scope="col" style="width: 180px;">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $permission->name }}</td>
                                    @canany(['edit.permission', 'delete.permission'])
                                    <td class="text-center">

                                        {{-- Edit --}}
                                        @can('edit.permission')
                                        <a
                                            href="{{ route('permissions.edit', $permission->id) }}"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Edit
                                        </a>
                                        @endcan

                                        {{-- Delete --}}
                                        @can('delete.permission')
                                        <form
                                            id="delete-permission-{{ $permission->id }}"
                                            action="{{ route('permissions.destroy', $permission->id) }}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-permission-{{ $permission->id }}',
                                                    'Permission akan dihapus. Pastikan tidak digunakan oleh role mana pun.'
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

                    {{ $permissions->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
