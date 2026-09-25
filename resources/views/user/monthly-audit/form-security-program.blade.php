@extends('layout.app')
@section('styles')
<style>
    .tl-wrap { overflow-x: auto; }
    .tl-table { border-collapse: separate; border-spacing: 0; width: max-content; min-width: 100%; }
    .tl-table th, .tl-table td { border: 1px solid #dee2e6; padding: 0; text-align: center; }
    .tl-table thead th { background: #f0f4f7; font-size: 12px; padding: 4px 2px; }
    .tl-sticky-1 { position: sticky; left: 0; z-index: 3; background: #fff; }
    .tl-sticky-2 { position: sticky; left: 42px; z-index: 3; background: #fff; }
    thead .tl-sticky-1, thead .tl-sticky-2 { background: #f0f4f7; z-index: 4; }
    .tl-no { width: 42px; min-width: 42px; }
    .tl-name { width: 200px; min-width: 200px; }
    .tl-type { min-width: 80px; font-size: 12px; padding: 2px 6px !important; white-space: nowrap; }
    .tl-cell { width: 22px; min-width: 22px; height: 30px; user-select: none; }
    .tl-cell.wk1 { border-left: 2px solid #adb5bd !important; }
    .tl-cell.plan { background: #dc3545; }
    .tl-cell.act { cursor: pointer; }
    .tl-cell.act:hover { background: #cfe2ff; }
    .tl-cell.act.on { background: #0d6efd; }
    .tl-action { min-width: 160px; padding: 4px !important; }
    .tl-actual.dirty td.tl-type { background: #fff3cd; }
</style>
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Laporan Bulanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Detail Laporan Bulanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-3">Detail Laporan Bulanan</h5>
                <a href="{{route('user.monthly-audit.index')}}" class="btn btn-danger"> Back</a>
                @include('user.monthly-audit.partials.sync-button', ['monthlyId' => $monthlyId, 'section' => 'program'])
            </div><!-- end card header -->

            <div class="card-body">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-formulir.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Form Formulir</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.worker-sum.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Jumlah Pekerja</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.security-form.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-account-outline"></i></span>
                            <span class="d-none d-sm-block">Satuan Pengamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.aght.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Data AGHT</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="{{route('user.monthly-audit.form-attribute.index',['monthlyId' => $monthlyId])}}" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Atribut Peralatan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-foreign-worker.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Tenaga Kerja Asing</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Program Keamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-internal.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Internal</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-external.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Eksternal</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Penyerapan Anggaran</span>    
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="navtabs2-atribut" role="tabpanel">
                        <div class="pb-2">
                            <span class="title">Program Keamanan</span>
                        </div>

                        @forelse ($programs as $program)
                            <div class="d-flex align-items-center gap-2 mt-3 mb-2">
                                <h6 class="m-0 text-dark">{{ $program->securityProgram->program_name ?? '-' }} ({{ $program->securityProgram->year ?? '-' }})</h6>
                                <a href="{{ route('user.monthly-audit.security-program.program.edit', ['monthlyId' => $monthlyId, 'programId' => $program->id]) }}" class="btn btn-sm btn-warning">Edit Program</a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmRowDelete('{{ route('user.monthly-audit.security-program.program.destroy', ['monthlyId' => $monthlyId, 'programId' => $program->id]) }}')">Hapus Program</button>
                            </div>
                            <div class="tl-wrap">
                                <table class="tl-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="tl-sticky-1 tl-no align-middle">No</th>
                                            <th rowspan="2" class="tl-sticky-2 tl-name align-middle">Program</th>
                                            <th rowspan="2" class="tl-type align-middle">Jenis</th>
                                            @foreach($months as $month)
                                                <th colspan="{{ $weeks }}">{{ $month }}</th>
                                            @endforeach
                                            <th rowspan="2" class="tl-action align-middle">Action</th>
                                        </tr>
                                        <tr>
                                            @foreach($months as $month)
                                                @for($w = 1; $w <= $weeks; $w++)
                                                    <th class="tl-cell wk{{ $w }}" style="cursor:default;height:auto;">{{ $w }}</th>
                                                @endfor
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($program->programs as $index => $main)
                                            @php
                                                $planSet = collect($main->planCells())->mapWithKeys(fn($c) => [$c[0] . '-' . $c[1] => true]);
                                                $actualSet = collect($main->actualCells())->map(fn($c) => $c[0] . '-' . $c[1])->all();
                                            @endphp
                                            <tr class="tl-plan">
                                                <td class="tl-sticky-1 tl-no align-middle" rowspan="2">{{ $index + 1 }}</td>
                                                <td class="tl-sticky-2 tl-name align-middle text-start px-2" rowspan="2">{{ $main->mainProgram->program_name ?? '-' }}</td>
                                                <td class="tl-type">Rencana</td>
                                                @foreach(range(1, 12) as $m)
                                                    @for($w = 1; $w <= $weeks; $w++)
                                                        <td class="tl-cell wk{{ $w }} readonly {{ $planSet->has($m . '-' . $w) ? 'plan' : '' }}"></td>
                                                    @endfor
                                                @endforeach
                                                <td class="tl-action align-middle" rowspan="2">
                                                    <input type="text" class="form-control form-control-sm mb-1 act-note" placeholder="Keterangan" value="{{ $main->note }}" data-for="{{ $main->id }}">
                                                    <button type="button" class="btn btn-sm btn-success act-save" data-for="{{ $main->id }}">Simpan</button>
                                                    <div class="mt-1 d-flex gap-1 justify-content-center">
                                                        <a href="{{ route('user.monthly-audit.security-program.detail.edit', ['monthlyId' => $monthlyId, 'rowId' => $main->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmRowDelete('{{ route('user.monthly-audit.security-program.detail.destroy', ['monthlyId' => $monthlyId, 'rowId' => $main->id]) }}')">Hapus</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="tl-actual" data-id="{{ $main->id }}"
                                                data-url="{{ route('user.monthly-audit.realization-program.update', ['monthlyId' => $monthlyId, 'programId' => $program->id, 'mainId' => $main->id]) }}"
                                                data-cells='@json($actualSet)'>
                                                <td class="tl-type">Realisasi</td>
                                                @foreach(range(1, 12) as $m)
                                                    @for($w = 1; $w <= $weeks; $w++)
                                                        <td class="tl-cell wk{{ $w }} act" data-m="{{ $m }}" data-w="{{ $w }}" title="{{ $months[$m - 1] }} minggu {{ $w }}"></td>
                                                    @endfor
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Belum ada program keamanan untuk tahun laporan ini. Tambahkan di menu Program Keamanan lalu klik Sinkron.</div>
                        @endforelse
                    </div><!-- end tab pane -->
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection


@section('scripts')
<script>
(function () {
    const CSRF = '{{ csrf_token() }}';
    let painting = null;

    function key(m, w) { return m + '-' + w; }

    document.querySelectorAll('tr.tl-actual').forEach(function (tr) {
        tr._cells = new Set(JSON.parse(tr.dataset.cells || '[]'));
        tr._dirty = false;
        tr.querySelectorAll('.tl-cell.act').forEach(function (td) {
            td.classList.toggle('on', tr._cells.has(key(td.dataset.m, td.dataset.w)));
        });
    });

    function actualRow(id) { return document.querySelector('tr.tl-actual[data-id="' + id + '"]'); }
    function markDirty(tr) {
        tr._dirty = true;
        tr.classList.add('dirty');
        const btn = document.querySelector('.act-save[data-for="' + tr.dataset.id + '"]');
        if (btn) { btn.classList.remove('btn-success'); btn.classList.add('btn-warning'); btn.textContent = 'Simpan *'; }
    }
    function setState(tr, td, state) {
        const k = key(td.dataset.m, td.dataset.w);
        if (state) tr._cells.add(k); else tr._cells.delete(k);
        td.classList.toggle('on', state);
        markDirty(tr);
    }

    document.addEventListener('mousedown', function (e) {
        const td = e.target.closest('.tl-cell.act');
        if (!td) return;
        e.preventDefault();
        const tr = td.parentElement;
        const state = !tr._cells.has(key(td.dataset.m, td.dataset.w));
        painting = { row: tr, state: state };
        setState(tr, td, state);
    });
    document.addEventListener('mouseover', function (e) {
        if (!painting) return;
        const td = e.target.closest('.tl-cell.act');
        if (td && td.parentElement === painting.row) setState(painting.row, td, painting.state);
    });
    document.addEventListener('mouseup', function () { painting = null; });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('act-note')) { const tr = actualRow(e.target.dataset.for); if (tr) markDirty(tr); }
    });

    function toast(icon, title) {
        Swal.fire({ toast: true, position: 'top-end', icon: icon, title: title, showConfirmButton: false, timer: 2200 });
    }

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.act-save');
        if (!btn) return;
        const tr = actualRow(btn.dataset.for);
        const note = document.querySelector('.act-note[data-for="' + btn.dataset.for + '"]').value;
        const cells = Array.from(tr._cells).map(k => k.split('-').map(Number));
        btn.disabled = true;
        try {
            const res = await fetch(tr.dataset.url, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ cells: cells, note: note }),
            });
            const r = await res.json();
            if (!res.ok || !r.success) { Swal.fire('Gagal', r.message || 'Gagal menyimpan realisasi.', 'error'); return; }
            tr._dirty = false;
            tr.classList.remove('dirty');
            btn.classList.remove('btn-warning'); btn.classList.add('btn-success'); btn.textContent = 'Simpan';
            toast('success', 'Tersimpan');
        } catch (err) {
            Swal.fire('Error', 'Server error saat menyimpan.', 'error');
        } finally {
            btn.disabled = false;
        }
    });
})();
</script>
@endsection
