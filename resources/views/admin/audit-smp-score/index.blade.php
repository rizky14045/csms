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
        <h4 class="fs-18 fw-semibold m-0">Data Audit SMP</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Audit SMP</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                <form method="GET" action="{{ route('admin.audit-smp-score.index') }}" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control"
                            placeholder="Cari nama unit..." value="{{ request('q') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </div>
                    @if(request('q'))
                        <a href="{{ route('admin.audit-smp-score.index') }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
                @can('create.audit.smp.score.unit')
                <a href="{{ route('user.audit-smp-score.create') }}" class="btn btn-success text-nowrap">Tambah Data</a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle"
                        style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 50px;">
                            <col style="width: 140px;">
                            <col style="width: 140px;">
                            <col style="width: 200px;">
                            <col style="width: 110px;">
                            <col style="width: 110px;">
                            <col style="width: 80px;">
                            <col style="width: 110px;">
                            @canany(['view.audit.smp.score.admin', 'edit.audit.smp.score.admin', 'delete.audit.smp.score.admin'])
                            <col style="width: 220px;">
                            @endcanany
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Unit</th>
                                <th>Ketua Auditor</th>
                                <th>Anggota Auditor</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Total</th>
                                <th>Kategori</th>
                                @canany(['view.audit.smp.score.admin', 'edit.audit.smp.score.admin', 'delete.audit.smp.score.admin'])
                                <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($audits as $audit)

                            @php
                                $auditTotal = 0;
                                if ($audit->status >= 2) {
                                    foreach ($audit->children_header ?? [] as $header) {
                                        $kriteriaLangsung = $header->kriteria ?? [];
                                        $kriteriaPernyataan = [];
                                        foreach ($header->pernyataan ?? [] as $p) {
                                            foreach ($p->kriteria ?? [] as $k) {
                                                $kriteriaPernyataan[] = $k;
                                            }
                                        }
                                        $totalPembagi = (count($kriteriaLangsung) + count($kriteriaPernyataan)) * 2;
                                        $pembagi = $totalPembagi ?: 1;
                                        $subTotal = 0;
                                        foreach ($kriteriaLangsung as $k) {
                                            $subTotal += ((int)($k->pencapaian_nilai_kriteria ?? 0) * (float)$header->bobot) / $pembagi;
                                        }
                                        foreach ($kriteriaPernyataan as $k) {
                                            $subTotal += ((int)($k->pencapaian_nilai_kriteria ?? 0) * (float)$header->bobot) / $pembagi;
                                        }
                                        $auditTotal += $subTotal;
                                    }
                                }

                                $kategori = $auditTotal < 55
                                    ? 'Kurang'
                                    : ($auditTotal <= 70
                                        ? 'Cukup'
                                        : ($auditTotal <= 85 ? 'Baik' : 'Baik Sekali'));

                                $kategoriColor = $auditTotal < 55
                                    ? '#dc3545'
                                    : ($auditTotal <= 70
                                        ? '#ffc107'
                                        : ($auditTotal <= 85 ? '#28a745' : '#198754'));
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $audit->unit->name ?? '-' }}</td>
                                <td>{{ $audit->lead_auditor->name ?? '-' }}</td>
                                <td class="text-start">
                                    @if(count($audit->auditors ?? []) > 0)
                                        @foreach($audit->auditors as $auditor)
                                            <span class="badge bg-secondary me-1 mb-1">{{ $auditor->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($audit->start_audit)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($audit->end_audit)->format('d-m-Y') }}</td>
                                <td>
                                    @if($audit->status >= 2)
                                        {{ number_format($auditTotal, 2) }}%
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($audit->status >= 2)
                                        <span class="badge" style="background:{{ $kategoriColor }};">{{ $kategori }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                @canany(['view.audit.smp.score.admin', 'edit.audit.smp.score.admin', 'delete.audit.smp.score.admin'])
                                <td>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">

                                        {{-- STATUS 0 --}}
                                        @if($audit->status == 0)

                                            <a href="{{ route('user.audit-smp-score.show', ['audit' => $audit->id]) }}"
                                               class="btn btn-info btn-sm">👁 Show</a>

                                            @can('send.audit.smp.score.unit')
                                                @if($audit->get_invalid_items_evidence_by_unit == 0)
                                                    <form action="{{ route('user.audit-smp-score.send', ['audit' => $audit->id]) }}"
                                                          method="post" id="send-audit-{{ $audit->id }}"
                                                          onsubmit="confirmSave('send-audit-{{ $audit->id }}', 'Kirim Audit?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm">📤 Kirim</button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled style="opacity:.6;">📤 Kirim</button>
                                                @endif
                                            @endcan

                                        {{-- STATUS 1 --}}
                                        @elseif($audit->status == 1)

                                            @can('view.audit.smp.score.admin')
                                            <a href="{{ route('admin.audit-smp-score.show', ['audit' => $audit->id]) }}"
                                               class="btn btn-info btn-sm">👁 Show</a>
                                            @endcan

                                            @can('edit.audit.smp.score.admin')
                                            <a href="{{ route('admin.audit-smp-score.edit', ['audit' => $audit->id]) }}"
                                               class="btn btn-warning btn-sm">✏ Edit</a>
                                            @endcan

                                        {{-- STATUS >= 2 --}}
                                        @elseif($audit->status >= 2)

                                            @php
                                                $isLead    = $audit->auditor_lead_id == auth()->id();
                                                $isAuditor = collect($audit->auditors)->pluck('id')->contains(auth()->id());
                                            @endphp

                                            @if($isLead || $isAuditor)

                                                <a href="{{ route('auditor.audit-smp-score.show', ['audit' => $audit->id]) }}"
                                                   class="btn btn-info btn-sm">👁 Show</a>

                                                @if($isLead && $audit->status == 2)
                                                    @can('edit.audit.smp.score.admin')
                                                    <a href="{{ route('admin.audit-smp-score.edit', ['audit' => $audit->id]) }}"
                                                       class="btn btn-warning btn-sm">✏ Edit</a>
                                                    @endcan

                                                    @if($audit->get_invalid_items_evidence_by_auditor == 0)
                                                        <form action="{{ route('auditor.audit-smp-score.send', ['audit' => $audit->id]) }}"
                                                              method="post" id="send-audit-{{ $audit->id }}"
                                                              onsubmit="confirmSave('send-audit-{{ $audit->id }}', 'Kirim Audit?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm">📤 Kirim</button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-secondary btn-sm" disabled style="opacity:.6;">📤 Kirim</button>
                                                    @endif
                                                @endif

                                            @else

                                                <a href="{{ route('admin.audit-smp-score.show', ['audit' => $audit->id]) }}"
                                                   class="btn btn-info btn-sm">👁 Show</a>

                                                @if($audit->status == 2)
                                                @can('edit.audit.smp.score.admin')
                                                <a href="{{ route('admin.audit-smp-score.edit', ['audit' => $audit->id]) }}"
                                                   class="btn btn-warning btn-sm">✏ Edit</a>
                                                @endcan
                                                @endif

                                            @endif

                                        @endif

                                    </div>
                                </td>
                                @endcanany
                            </tr>

                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada data audit.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $audits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
