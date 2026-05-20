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
        <h4 class="fs-18 fw-semibold m-0">Satuan Pengamanan</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Satuan Pengamanan</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                <form method="GET" action="{{ route('user.security.index') }}" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('user.security.index') }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
                @can('create.security.unit')
                    <a href="{{ route('user.security.create') }}" class="btn btn-success text-nowrap">Tambah Data</a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle"
                        style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 45px;">
                            <col style="width: 160px;">
                            <col style="width: 80px;">
                            <col style="width: 130px;">
                            <col style="width: 110px;">
                            <col style="width: 130px;">
                            <col style="width: 110px;">
                            <col style="width: 100px;">
                            <col style="width: 110px;">
                            <col style="width: 110px;">
                            <col style="width: 100px;">
                            <col style="width: 120px;">
                            <col style="width: 90px;">
                            <col style="width: 90px;">
                            @canany(['edit.security.unit', 'delete.security.unit'])
                            <col style="width: 160px;">
                            @endcanany
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">JK</th>
                                <th scope="col">Unit Kerja</th>
                                <th scope="col">NID</th>
                                <th scope="col">Nomor REG KTA</th>
                                <th scope="col">Expired KTA</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Tempat Lahir</th>
                                <th scope="col">Tanggal Lahir</th>
                                <th scope="col">Kualifikasi</th>
                                <th scope="col">Pendidikan</th>
                                <th scope="col">Note</th>
                                <th scope="col">KTA</th>
                                @canany(['edit.security.unit', 'delete.security.unit'])
                                <th scope="col">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($securities as $security)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->name }}</td>
                                    <td>{{ $security->gender }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->unit_work }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->nid }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->registration_number }}</td>
                                    <td>@if($security->expired_card_date) {{ \Carbon\Carbon::parse($security->expired_card_date)->format('d-m-Y') }} @else - @endif</td>
                                    <td>{{ $security->position }}</td>
                                    <td>{{ $security->birth_place }}</td>
                                    <td>@if($security->birth_date) {{ \Carbon\Carbon::parse($security->birth_date)->format('d-m-Y') }} @else - @endif</td>
                                    <td>{{ $security->qualification }}</td>
                                    <td>{{ $security->last_education }}</td>
                                    <td>{{ $security->note ?? '-' }}</td>
                                    <td>
                                        @if ($security->kta_file)
                                            <a href="{{ asset('uploads/kta_files/' . $security->kta_file) }}"
                                                target="_blank" class="btn btn-info btn-sm">View</a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    @canany(['edit.security.unit', 'delete.security.unit'])
                                    <td class="text-center text-nowrap">
                                        @can('edit.security.unit')
                                            <a href="{{ route('user.security.edit', ['security' => $security->id]) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.security.unit')
                                            <form id="delete-security-{{ $security->id }}"
                                                action="{{ route('user.security.destroy', ['security' => $security->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('delete-security-{{ $security->id }}', 'Satuan pengaman akan dihapus.')">
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
                    {{ $securities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
