@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">User Management</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item active">User Management</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                <form method="GET" action="{{ route('users.index') }}" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama pengguna..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
                @can('create.user')
                <a href="{{ route('users.create') }}" class="btn btn-primary text-nowrap">Tambah Data</a>
                @endcan
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle" style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 60px;">
                            <col style="width: auto;">
                            <col style="width: 160px;">
                            @canany(['edit.user', 'delete.user'])
                            <col style="width: 160px;">
                            @endcanany
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Role</th>
                                @canany(['edit.user', 'delete.user'])
                                <th scope="col">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-info">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    @canany(['edit.user', 'delete.user'])
                                    <td class="text-center">
                                        @can('edit.user')
                                        <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.user')
                                        <form
                                            id="delete-user-{{ $user->id }}"
                                            action="{{ route('users.destroy', $user->id) }}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-user-{{ $user->id }}',
                                                    'User akan dihapus. Pastikan tidak digunakan oleh user mana pun.'
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
                    {{$users->links()}}
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

