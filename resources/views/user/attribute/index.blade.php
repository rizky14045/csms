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
        <h4 class="fs-18 fw-semibold m-0">Atribut</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Atribut</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                <form method="GET" action="{{ route('user.attribute.index') }}" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama atribut..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('user.attribute.index') }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
                @can('create.attribute.unit')
                    <a href="{{ route('user.attribute.create') }}" class="btn btn-success text-nowrap">Tambah Data</a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle"
                        style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 50px;">
                            <col style="width: auto;">
                            <col style="width: 160px;">
                            <col style="width: 100px;">
                            <col style="width: 180px;">
                            <col style="width: 130px;">
                            @canany(['edit.attribute.unit', 'delete.attribute.unit'])
                            <col style="width: 140px;">
                            @endcanany
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status Kepemilikan</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Jml Standar Kontrak</th>
                                <th scope="col">Tipe</th>
                                @canany(['edit.attribute.unit', 'delete.attribute.unit'])
                                <th scope="col">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $attribute)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $attribute->name }}</td>
                                    <td>{{ $attribute->status_ownership }}</td>
                                    <td>{{ $attribute->unit }}</td>
                                    <td>{{ $attribute->standard_contract }}</td>
                                    <td>{{ $attribute->type_attribute }}</td>
                                    @canany(['edit.attribute.unit', 'delete.attribute.unit'])
                                    <td class="text-center">
                                        @can('edit.attribute.unit')
                                            <a href="{{ route('user.attribute.edit', ['attribute' => $attribute->id]) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.attribute.unit')
                                            <form id="delete-attribute-{{ $attribute->id }}"
                                                action="{{ route('user.attribute.destroy', ['attribute' => $attribute->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('delete-attribute-{{ $attribute->id }}', 'Atribut akan dihapus.')">
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
                    {{ $attributes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
