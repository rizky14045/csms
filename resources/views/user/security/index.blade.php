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
    <div class="flex-grow-1 d-flex align-items-center gap-2 flex-wrap">
        <h4 class="fs-18 fw-semibold m-0">Satuan Pengamanan</h4>

        @if($expiryStats['expiring_soon'] > 0)
            <span class="badge" style="background:#ffc107;color:#000;" title="Jumlah KTA yang akan expired dalam 3 bulan">
                {{ $expiryStats['expiring_soon'] }} KTA akan expired
            </span>
        @endif

        @if($expiryStats['expired'] > 0)
            <span class="badge" style="background:#dc3545;" title="Jumlah KTA yang sudah expired">
                {{ $expiryStats['expired'] }} KTA sudah expired
            </span>
        @endif
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
                    <div class="d-flex gap-2 text-nowrap">
                        <a href="{{ route('user.security.import-template') }}" class="btn btn-outline-secondary">
                            Download Format
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                            Import Excel
                        </button>
                        <a href="{{ route('user.security.create') }}" class="btn btn-success">Tambah Data</a>
                    </div>
                @endcan
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mx-3 mt-3">
                    <strong>Import gagal pada beberapa baris:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                                @php
                                    $ktaStyle = '';
                                    if ($security->expired_card_date) {
                                        $exp = \Carbon\Carbon::parse($security->expired_card_date)->startOfDay();
                                        if ($exp->lt(now()->startOfDay())) {
                                            $ktaStyle = 'background:#dc3545;color:#fff;font-weight:600;';
                                        } elseif ($exp->lte(now()->addMonths(3)->startOfDay())) {
                                            $ktaStyle = 'background:#ffc107;color:#000;font-weight:600;';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->name }}</td>
                                    <td>{{ $security->gender }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->unit_work }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->nid }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $security->registration_number }}</td>
                                    <td style="{{ $ktaStyle }}">@if($security->expired_card_date) {{ \Carbon\Carbon::parse($security->expired_card_date)->format('d-m-Y') }} @else - @endif</td>
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

@can('create.security.unit')
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.security.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Data dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        Unduh format excel terlebih dahulu melalui tombol
                        <strong>Download Format</strong>, isi data sesuai kolom yang tersedia,
                        lalu unggah kembali file tersebut di sini.
                        File KTA tidak dapat diimpor melalui excel dan harus diunggah manual
                        melalui menu Edit setelah data berhasil diimpor.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">File Excel (.xlsx / .xls)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection
