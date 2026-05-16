@extends('layout.app')
@section('styles')
<style>
    .card-scrollable {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 180px);
    }
    .card-scrollable .card-body {
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Role Management</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">Role Management</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                <form method="GET" action="{{ route('roles.index') }}" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama role..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
                @can('create.role')
                <a href="{{ route('roles.create') }}" class="btn btn-primary text-nowrap">Tambah Data</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle" style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 60px;">
                            <col style="width: auto;">
                            @canany(['edit.role', 'delete.role'])
                            <col style="width: 160px;">
                            @endcanany
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                @canany(['edit.role', 'delete.role'])
                                <th scope="col">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$role->name}}</td>
                                    @canany(['edit.role', 'delete.role'])
                                    <td class="text-center">
                                        @can('edit.role')
                                        <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.role')
                                        <form
                                            id="delete-role-{{ $role->id }}"
                                            action="{{ route('roles.destroy', $role->id) }}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-role-{{ $role->id }}',
                                                    'Role akan dihapus. Pastikan tidak digunakan oleh user mana pun.'
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
                    {{$roles->links()}}
                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection
