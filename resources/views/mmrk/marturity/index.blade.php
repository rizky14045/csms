@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Maturity (MMRK)</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Maturity</li>
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
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Tgl Kirim ke MMRK</th>
                        <th>Tgl Kirim ke Pusat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marturities as $marturity)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $marturity->unit->name ?? '-' }}</td>
                        <td>{{ $marturity->year }}</td>
                        <td>{{ $marturity->semester }}</td>
                        <td>
                            @if($marturity->status == 1)
                                <span class="badge bg-warning text-dark">Menunggu dikirim MMRK</span>
                            @elseif($marturity->status == 2)
                                <span class="badge bg-info">Menunggu validasi Pusat</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                        <td>{{ !empty($marturity->mmrk_send_date) ? \Carbon\Carbon::parse($marturity->mmrk_send_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $marturity->send_date ? \Carbon\Carbon::parse($marturity->send_date)->format('d-m-Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('mmrk.marturity.show', ['marturity' => $marturity->id]) }}" class="btn btn-info btn-sm">👁 Show</a>
                            @if($marturity->status == 1)
                                @can('send.marturity.mmrk')
                                <form id="form-send-{{ $marturity->id }}" action="{{ route('mmrk.marturity.send', ['marturity' => $marturity->id]) }}" method="POST" class="d-inline"
                                      onsubmit="return confirmAction('form-send-{{ $marturity->id }}', 'Kirim ke Pusat?', 'Setelah dikirim, data tidak bisa diedit lagi oleh Unit maupun MMRK.', 'Ya, Kirim')">
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
            {{ $marturities->links() }}
        </div>
    </div>
</div>

@endsection
