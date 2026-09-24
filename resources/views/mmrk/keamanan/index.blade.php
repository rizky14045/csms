@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">KPI Keamanan (MMRK)</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">KPI</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Unit</th>
                        <th>Tahun</th>
                        <th>Triwulan</th>
                        <th>Status</th>
                        <th>Tgl Kirim ke MMRK</th>
                        <th>Tgl Kirim ke Pusat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kpis as $kpi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kpi->unit->name ?? '-' }}</td>
                        <td>{{ $kpi->year }}</td>
                        <td>{{ $kpi->period_label }}</td>
                        <td>
                            @if($kpi->status == 1)
                                <span class="badge bg-warning text-dark">Menunggu dikirim MMRK</span>
                            @elseif($kpi->status == 2)
                                <span class="badge bg-info">Menunggu validasi Pusat</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                        <td>{{ !empty($kpi->mmrk_send_date) ? \Carbon\Carbon::parse($kpi->mmrk_send_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $kpi->send_date ? \Carbon\Carbon::parse($kpi->send_date)->format('d-m-Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('mmrk.keamanan.show', ['kpi' => $kpi->id]) }}" class="btn btn-info btn-sm">👁 Show</a>
                    @include('components.assessment-export-buttons', ['kind' => 'kpi', 'item' => $kpi])
                    @include('components.status-history-button', ['type' => 'kpi', 'id' => $kpi->id])
                            @if($kpi->status == 1)
                                @can('send.kpi.mmrk')
                                <form id="form-send-{{ $kpi->id }}" action="{{ route('mmrk.keamanan.send', ['kpi' => $kpi->id]) }}" method="POST" class="d-inline"
                                      onsubmit="return confirmAction('form-send-{{ $kpi->id }}', 'Kirim ke Pusat?', 'Setelah dikirim, data tidak bisa diedit lagi oleh Unit maupun MMRK.', 'Ya, Kirim')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">📤 Kirim ke Pusat</button>
                                </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-muted">Belum ada data dari Unit.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $kpis->links() }}
        </div>
    </div>
</div>

@endsection
