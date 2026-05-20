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
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                <form method="GET" action="{{route('admin.securepedia.index')}}" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control"
                            placeholder="Cari judul..." value="{{ request('q') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </div>
                    @if(request('q'))
                        <a href="{{route('admin.securepedia.index')}}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
                @can('create.securepedia.admin')
                <a href="{{route('admin.securepedia.create')}}" class="btn btn-success text-nowrap">Tambah Data</a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle"
                        style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 50px;">
                            <col>
                            <col style="width: 130px;">
                            <col style="width: 140px;">
                            @canany(['edit.securepedia.admin', 'delete.securepedia.admin'])
                            <col style="width: 150px;">
                            @endcanany
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Status Kepemilikan</th>
                                <th>File Pendukung</th>
                                @canany(['edit.securepedia.admin', 'delete.securepedia.admin'])
                                <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($securepedias as $securepedia)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $securepedia->title }}</td>
                                <td>{{ $securepedia->type }}</td>
                                <td>
                                    @if($securepedia->file)
                                        <a href="{{ asset('uploads/securepedia/' . $securepedia->file) }}"
                                            target="_blank" class="btn btn-primary btn-sm">Lihat File</a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                @canany(['edit.securepedia.admin', 'delete.securepedia.admin'])
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('edit.securepedia.admin')
                                        <a href="{{route('admin.securepedia.edit',['securepedia'=>$securepedia->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.securepedia.admin')
                                        <form id="del-sp-{{ $securepedia->id }}" action="{{route('admin.securepedia.destroy',['securepedia'=>$securepedia->id])}}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete('del-sp-{{ $securepedia->id }}','Securepedia akan dihapus.')">Hapus</button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                                @endcanany
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $securepedias->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
