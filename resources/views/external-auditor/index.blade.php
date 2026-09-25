@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Auditor External</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Auditor External</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ auth()->user()->can('view.audit.smp.score.unit') ? route('user.audit-smp-score.index') : route('admin.audit-smp-score.index') }}" class="btn btn-danger">Kembali</a>
                    @can('create.external.auditor')
                        <a href="{{ route('external-auditor.create') }}" class="btn btn-success">Tambah Auditor External</a>
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email (Username)</th>
                                <th>Tanggal Expired</th>
                                <th>Status</th>
                                <th>Data Audit yang Diakses</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                @php $expired = $item->isExpired(); @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $item->user->name ?? '-' }}</td>
                                    <td class="text-start">{{ $item->user->email ?? '-' }}</td>
                                    <td>{{ $item->expired_at->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($expired)
                                            <span class="badge bg-danger">Expired</span>
                                        @else
                                            <span class="badge bg-success">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        @forelse (($auditsByUser[$item->user_id] ?? []) as $a)
                                            <div>Audit SMP {{ $a->start_audit ? \Carbon\Carbon::parse($a->start_audit)->format('d-m-Y') : '-' }} s/d {{ $a->end_audit ? \Carbon\Carbon::parse($a->end_audit)->format('d-m-Y') : '-' }}</div>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            @can('edit.external.auditor')
                                                <a href="{{ route('external-auditor.edit', ['item' => $item->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('external-auditor.resend', ['item' => $item->id]) }}" method="post" id="resend-{{ $item->id }}"
                                                      onsubmit="return confirmAction('resend-{{ $item->id }}', 'Kirim ulang akses?', 'Password baru dibuat dan dikirim ke email auditor. Password lama tidak berlaku.', 'Ya, Kirim')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm">Kirim Ulang Akses</button>
                                                </form>
                                            @endcan
                                            @can('delete.external.auditor')
                                                <form action="{{ route('external-auditor.destroy', ['item' => $item->id]) }}" method="post" id="del-ext-{{ $item->id }}"
                                                      onsubmit="return confirmAction('del-ext-{{ $item->id }}', 'Hapus auditor external?', 'Auditor tidak akan bisa login lagi.', 'Ya, Hapus')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-muted">Belum ada auditor external.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
