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
            <h4 class="fs-18 fw-semibold m-0">Fasilitas Umum</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Fasilitas Umum</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-scrollable">
                <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                    <form method="GET" action="{{ route('user.fasum.index') }}" class="d-flex gap-2">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari nama fasilitas..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">Cari</button>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('user.fasum.index') }}" class="btn btn-outline-danger">Reset</a>
                        @endif
                    </form>
                    @can('create.fasum.user')
                        <a href="{{ route('user.fasum.create') }}" class="btn btn-success text-nowrap">Tambah Data</a>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle"
                            style="table-layout: fixed; width: 100%;">
                            <colgroup>
                                <col style="width: 55px;">
                                <col style="width: auto;">
                                <col style="width: 130px;">
                                <col style="width: 250px;">
                                <col style="width: 180px;">
                                @canany(['edit.fasum.user', 'delete.fasum.user'])
                                <col style="width: 140px;">
                                @endcanany
                            </colgroup>
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Fasilitas</th>
                                    <th scope="col">Tipe</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Kontak</th>
                                    @canany(['edit.fasum.user', 'delete.fasum.user'])
                                    <th scope="col">Action</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fasums as $fasum)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $fasum->name }}</td>
                                        <td>{{ $fasum->type->name ?? '-' }}</td>
                                        <td class="text-start" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $fasum->address }}
                                        </td>
                                        <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $fasum->contact ?? '-' }}
                                        </td>
                                        @canany(['edit.fasum.user', 'delete.fasum.user'])
                                        <td class="text-center">
                                            @can('edit.fasum.user')
                                                <a href="{{ route('user.fasum.edit', ['fasum' => $fasum->id]) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                            @endcan
                                            @can('delete.fasum.user')
                                                <form id="delete-fasum-{{ $fasum->id }}"
                                                    action="{{ route('user.fasum.destroy', ['fasum' => $fasum->id]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete('delete-fasum-{{ $fasum->id }}', 'Fasilitas umum akan dihapus.')">
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
                        {{ $fasums->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
