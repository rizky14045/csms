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
        <h4 class="fs-18 fw-semibold m-0">Jumlah Pekerja</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Jumlah Pekerja</li>
        </ol>
    </div>
</div>

{{-- Table 1: Penanggung Jawab Keamanan --}}
<div class="row mb-3">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2 flex-wrap">
                <span class="fw-bold">Data Penanggung Jawab Keamanan</span>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <form method="GET" action="{{ route('user.worker-sum.index') }}" class="d-flex gap-2">
                        <input type="hidden" name="q_security"  value="{{ request('q_security') }}">
                        <input type="hidden" name="q_agreement" value="{{ request('q_agreement') }}">
                        <div class="input-group">
                            <input type="text" name="q_person" class="form-control"
                                placeholder="Cari nama, jabatan, unit..." value="{{ request('q_person') }}">
                            <button class="btn btn-outline-primary" type="submit">Cari</button>
                        </div>
                        @if(request('q_person'))
                            <a href="{{ route('user.worker-sum.index', ['q_security' => request('q_security'), 'q_agreement' => request('q_agreement')]) }}"
                               class="btn btn-outline-danger">Reset</a>
                        @endif
                    </form>
                    @can('create.responsible.person.unit')
                    <a href="{{ route('user.responsible-person.create') }}" class="btn btn-success text-nowrap">Tambah Data</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="3" style="width:50px;">No</th>
                                <th rowspan="3" style="min-width:140px;">Nama</th>
                                <th rowspan="3" style="min-width:120px;">Jabatan</th>
                                <th rowspan="3" style="min-width:120px;">Unit Kerja</th>
                                <th colspan="7">Pelatihan Unit Pengamanan</th>
                                <th rowspan="3" style="min-width:120px;">Keterangan</th>
                                <th rowspan="3" style="width:130px;">Action</th>
                            </tr>
                            <tr>
                                <th colspan="7">Kualifikasi</th>
                            </tr>
                            <tr>
                                <th style="min-width:120px;">Pelatihan SMP</th>
                                <th style="min-width:110px;">Auditor SMP</th>
                                <th style="min-width:80px;">Utama</th>
                                <th style="min-width:100px;">Investigasi</th>
                                <th style="min-width:100px;">Mansrisk</th>
                                <th style="min-width:170px;">Stackholder Management</th>
                                <th style="min-width:150px;">Pendidikan Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persons as $person)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $person->name }}</td>
                                <td>{{ $person->position }}</td>
                                <td>{{ $person->work_unit }}</td>
                                <td>{{ $person->training_smp }}</td>
                                <td>{{ $person->auditor_smp }}</td>
                                <td>{{ $person->main }}</td>
                                <td>{{ $person->investigation }}</td>
                                <td>{{ $person->mansrisk }}</td>
                                <td>{{ $person->stackholder_management }}</td>
                                <td>{{ $person->last_education }}</td>
                                <td>{{ $person->note }}</td>
                                <td>
                                    @can('edit.responsible.person.unit')
                                    <a href="{{ route('user.responsible-person.edit', ['person' => $person->id]) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    @endcan
                                    @can('delete.responsible.person.unit')
                                    <form action="{{ route('user.responsible-person.destroy', ['person' => $person->id]) }}" method="post" class="d-inline" id="delete-person-{{ $person->id }}" onsubmit="confirmSave('delete-person-{{ $person->id }}', 'Data akan dihapus')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" class="text-center text-muted py-4">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Table 2: Personil Keamanan Eksternal --}}
<div class="row mb-3">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2 flex-wrap">
                <span class="fw-bold">Data Personil Keamanan Eksternal</span>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <form method="GET" action="{{ route('user.worker-sum.index') }}" class="d-flex gap-2">
                        <input type="hidden" name="q_person"    value="{{ request('q_person') }}">
                        <input type="hidden" name="q_agreement" value="{{ request('q_agreement') }}">
                        <div class="input-group">
                            <input type="text" name="q_security" class="form-control"
                                placeholder="Cari nama, instansi..." value="{{ request('q_security') }}">
                            <button class="btn btn-outline-primary" type="submit">Cari</button>
                        </div>
                        @if(request('q_security'))
                            <a href="{{ route('user.worker-sum.index', ['q_person' => request('q_person'), 'q_agreement' => request('q_agreement')]) }}"
                               class="btn btn-outline-danger">Reset</a>
                        @endif
                    </form>
                    @can('create.security.external.unit')
                    <a href="{{ route('user.security-external.create') }}" class="btn btn-success text-nowrap">Tambah Data</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle"
                        style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 50px;">
                            <col>
                            <col style="width: 150px;">
                            <col style="width: 150px;">
                            <col style="width: 170px;">
                            <col style="width: 120px;">
                            <col style="width: 130px;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Instansi</th>
                                <th>Satuan Wilayah</th>
                                <th>Nomor Surat Perintah</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($securities as $security)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $security->name }}</td>
                                <td>{{ $security->instansi }}</td>
                                <td>{{ $security->regional_unit }}</td>
                                <td>{{ $security->warrant_number }}</td>
                                <td>{{ $security->note }}</td>
                                <td>
                                    @can('edit.security.external.unit')
                                    <a href="{{ route('user.security-external.edit', ['security' => $security->id]) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    @endcan
                                    @can('delete.security.external.unit')
                                    <form action="{{ route('user.security-external.destroy', ['security' => $security->id]) }}" method="post" class="d-inline" id="delete-security-external-{{ $security->id }}" onsubmit="confirmSave('delete-security-external-{{ $security->id }}', 'Data akan dihapus')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Table 3: Perjanjian Kerjasama Eksternal --}}
<div class="row mb-3">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2 flex-wrap">
                <span class="fw-bold">Data Perjanjian Kerjasama Eksternal</span>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <form method="GET" action="{{ route('user.worker-sum.index') }}" class="d-flex gap-2">
                        <input type="hidden" name="q_person"   value="{{ request('q_person') }}">
                        <input type="hidden" name="q_security" value="{{ request('q_security') }}">
                        <div class="input-group">
                            <input type="text" name="q_agreement" class="form-control"
                                placeholder="Cari nama, instansi, judul PKT..." value="{{ request('q_agreement') }}">
                            <button class="btn btn-outline-primary" type="submit">Cari</button>
                        </div>
                        @if(request('q_agreement'))
                            <a href="{{ route('user.worker-sum.index', ['q_person' => request('q_person'), 'q_security' => request('q_security')]) }}"
                               class="btn btn-outline-danger">Reset</a>
                        @endif
                    </form>
                    @can('create.agreement.external.unit')
                    <a href="{{ route('user.agreement-external.create') }}" class="btn btn-success text-nowrap">Tambah Data</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle"
                        style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 50px;">
                            <col style="width: 140px;">
                            <col>
                            <col style="width: 140px;">
                            <col style="width: 120px;">
                            <col style="width: 180px;">
                            <col style="width: 120px;">
                            <col style="width: 120px;">
                            <col style="width: 130px;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Instansi</th>
                                <th>Nama</th>
                                <th>Satuan Wilayah</th>
                                <th>Nomor PKT</th>
                                <th>Judul PKT</th>
                                <th>Masa Berlaku</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($agreements as $agreement)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $agreement->instansi }}</td>
                                <td class="text-start">{{ $agreement->name }}</td>
                                <td>{{ $agreement->regional_unit }}</td>
                                <td>{{ $agreement->pkt_number }}</td>
                                <td class="text-start">{{ $agreement->pkt_title }}</td>
                                <td>{{ \Carbon\Carbon::parse($agreement->expired_date)->format('d/m/Y') }}</td>
                                <td>{{ $agreement->note }}</td>
                                <td>
                                    @can('edit.agreement.external.unit')
                                    <a href="{{ route('user.agreement-external.edit', ['agreement' => $agreement->id]) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                    @endcan
                                    @can('delete.agreement.external.unit')
                                    <form action="{{ route('user.agreement-external.destroy', ['agreement' => $agreement->id]) }}" method="post" class="d-inline" id="delete-agreement-external-{{ $agreement->id }}" onsubmit="confirmSave('delete-agreement-external-{{ $agreement->id }}', 'Data akan dihapus')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
