@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Laporan Bulanan</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Bulanan</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="" method="GET" class="d-flex gap-3 mb-3">
            <input type="month" class="form-control" style="max-width:220px;" name="month" value="{{ $request['month'] ?? '' }}">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Unit</th>
                        <th>Kode Unit</th>
                        <th>Bulan</th>
                        <th>Tanggal Buat</th>
                        <th>Status</th>
                        <th>Tanggal Kirim</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($forms as $form)
                    <tr>
                        <td>{{ $forms->firstItem() + $loop->index }}</td>
                        <td>{{ $form->detailUnit->name ?? '' }}</td>
                        <td>{{ $form->detailUnit->unit_code ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($form->report_date)->format('m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($form->created_at)->format('d-m-Y') }}</td>
                        <td>
                            @if($form->send_status)
                                <span class="badge bg-success">Terkirim</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $form->send_status ? \Carbon\Carbon::parse($form->send_date)->format('d-m-Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('mmrk.monthly-audit.show', ['monthlyId' => $form->id]) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('export.monthly.all', ['monthlyId' => $form->id]) }}" class="btn btn-success btn-sm">Export Excel</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-muted">Belum ada laporan bulanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $forms->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection
