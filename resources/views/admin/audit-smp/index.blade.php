@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }

    /* Level Accents */
    .audit-item {
        border: 1px solid #eef2f7;
        border-left: 5px solid #4e73df;
        border-radius: 10px !important;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .pernyataan-item {
        border: 1px solid #e3f9ef;
        border-left: 4px solid #1cc88a;
        border-radius: 8px !important;
        margin-bottom: 0.6rem;
        overflow: hidden;
    }
    .kriteria-item {
        border: 1px solid #fff3cd;
        border-left: 4px solid #f6c23e;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
        overflow: hidden;
    }

    .accordion-button {
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #343a40;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f8faff;
        box-shadow: none;
    }

    /* Small action buttons */
    .btn-xs {
        padding: 3px 10px;
        font-size: 11px;
        border-radius: 5px;
        font-weight: 600;
        white-space: nowrap;
    }
    .btn-action-group {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: nowrap;
    }

    /* Soft button variants */
    .btn-soft-primary { background:#e8eeff; color:#4e73df; border:1px solid #d1dfff; }
    .btn-soft-primary:hover { background:#4e73df; color:#fff; }
    .btn-soft-success { background:#e3f9ef; color:#1cc88a; border:1px solid #c9f2de; }
    .btn-soft-success:hover { background:#1cc88a; color:#fff; }
    .btn-soft-warning { background:#fff9e6; color:#d4a000; border:1px solid #ffecb3; }
    .btn-soft-warning:hover { background:#f6c23e; color:#fff; }
    .btn-soft-danger  { background:#ffeef0; color:#e74a3b; border:1px solid #ffdadd; }
    .btn-soft-danger:hover  { background:#e74a3b; color:#fff; }

    /* Evidence cards */
    .evident-item {
        border: 1px dashed #d1d3e2;
        border-radius: 7px;
        padding: 10px 14px;
        margin-bottom: 6px;
        background: #fafbff;
        transition: border-color .2s;
    }
    .evident-item:hover { border-color: #4e73df; }

    /* Bobot badge */
    .badge-bobot {
        background: #4e73df;
        color: #fff;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 10px;
        white-space: nowrap;
    }

    /* Section labels */
    .label-section {
        font-size: 10px;
        font-weight: 800;
        color: #a0a4c0;
        text-transform: uppercase;
        letter-spacing: .6px;
        display: block;
        margin-bottom: 8px;
        margin-top: 4px;
    }

    /* Indented accordion bodies */
    .pernyataan-body { background: #f9fbff; padding: 12px 14px; }
    .kriteria-body   { background: #fffdf5; padding: 10px 14px; }
    .evident-body    { background: #fafbff; padding: 10px 14px; }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Audit SMP</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Audit SMP</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <!-- Header: total bobot + tambah button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    @php
                        $totalBobot = collect($audits)->sum('bobot');
                        $remaining  = 100 - $totalBobot;
                    @endphp
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold small text-muted">Total Bobot:</span>
                        <span class="badge fs-6 {{ $totalBobot > 100 ? 'bg-danger' : ($totalBobot == 100 ? 'bg-success' : 'bg-warning text-dark') }}">
                            {{ $totalBobot }}%
                        </span>
                        @if($remaining > 0)
                            <span class="text-muted small">(sisa {{ $remaining }}%)</span>
                        @elseif($totalBobot == 100)
                            <span class="text-success small fw-semibold">Sudah penuh</span>
                        @else
                            <span class="text-danger small fw-semibold">Melebihi 100%!</span>
                        @endif
                    </div>
                    @can('create.audit.smp.admin')
                        <a href="{{route('admin.audit-smp.create')}}" class="btn btn-sm btn-primary">
                            <i data-feather="plus" style="width:14px;height:14px;margin-right:4px;"></i>Tambah Audit
                        </a>
                    @endcan
                </div>

                <!-- Audit accordion -->
                <div class="accordion" id="auditAccordion">
                    @foreach ($audits as $audit)
                    <div class="accordion-item audit-item">

                        <!-- Audit header -->
                        <h2 class="accordion-header" id="auditHeading{{ $audit['id'] }}">
                            <div class="d-flex align-items-center w-100 pe-3">
                                @can('view.element.audit.smp.admin')
                                <button
                                    class="accordion-button collapsed flex-grow-1"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#auditBody{{ $audit['id'] }}"
                                    aria-expanded="false"
                                >
                                @else
                                <div class="accordion-button collapsed flex-grow-1 pe-none" style="cursor:default;">
                                @endcan
                                    <span class="fw-bold">{{ $audit['name'] }}</span>
                                    <span class="badge-bobot">Bobot: {{ $audit['bobot'] }}%</span>
                                @can('view.element.audit.smp.admin')
                                </button>
                                @else
                                </div>
                                @endcan

                                <div class="btn-action-group ms-3">
                                    @can('edit.audit.smp.admin')
                                    <a href="{{route('admin.audit-smp.edit',['audit'=>$audit['id']])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                    @endcan
                                    @can('delete.audit.smp.admin')
                                    <form id="del-audit-{{ $audit['id'] }}" action="{{route('admin.audit-smp.destroy',['audit'=>$audit['id']])}}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-soft-danger btn-xs"
                                            onclick="confirmDelete('del-audit-{{ $audit['id'] }}','Audit akan dihapus.')">Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </h2>

                        @can('view.element.audit.smp.admin')
                        <div id="auditBody{{ $audit['id'] }}" class="accordion-collapse collapse">
                            <div class="accordion-body bg-white pb-3">

                                @can('create.element.audit.smp.admin')
                                <div class="mb-3">
                                    <a href="{{route('admin.element.audit-smp.create',['audit'=>$audit['id']])}}" class="btn btn-soft-primary btn-xs px-3 py-2">
                                        <i data-feather="plus-circle" style="width:12px;height:12px;margin-right:4px;"></i>Tambah Element
                                    </a>
                                </div>
                                @endcan

                                {{-- ── Pernyataan ── --}}
                                @if(isset($audit['pernyataan']) && !empty($audit['pernyataan']))
                                <span class="label-section">Pernyataan</span>
                                <div class="mb-4">
                                    @foreach ($audit['pernyataan'] as $pernyataan)
                                    <div class="accordion-item pernyataan-item">

                                        <!-- Pernyataan header -->
                                        <h2 class="accordion-header">
                                            <div class="d-flex align-items-center w-100 pe-3">
                                                @can('view.criteria.audit.smp.admin')
                                                <button
                                                    class="accordion-button collapsed flex-grow-1 py-2 fs-13"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#pernyataanBody{{ $pernyataan['id'] }}"
                                                    aria-expanded="false"
                                                >
                                                @else
                                                <div class="flex-grow-1 py-2 fs-13 px-3 fw-semibold">
                                                @endcan
                                                    {{ $pernyataan['name'] }}
                                                @can('view.criteria.audit.smp.admin')
                                                </button>
                                                @else
                                                </div>
                                                @endcan

                                                <div class="btn-action-group ms-3">
                                                    @can('create.criteria.audit.smp.admin')
                                                    <a href="{{route('admin.criteria.audit-smp.create',['audit'=>$pernyataan['id']])}}" class="btn btn-soft-success btn-xs">+ Kriteria</a>
                                                    @endcan
                                                    @can('edit.element.audit.smp.admin')
                                                    <a href="{{route('admin.element.audit-smp.edit',['audit'=>$audit['id'],'element'=>$pernyataan['id']])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                    @endcan
                                                    @can('delete.element.audit.smp.admin')
                                                    <form id="del-pernyataan-{{ $pernyataan['id'] }}" action="{{route('admin.element.audit-smp.delete',['audit'=>$audit['id'],'element'=>$pernyataan['id']])}}" method="POST" class="m-0">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-soft-danger btn-xs"
                                                            onclick="confirmDelete('del-pernyataan-{{ $pernyataan['id'] }}','Pernyataan akan dihapus.')">Hapus</button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </h2>

                                        @can('view.criteria.audit.smp.admin')
                                        <div id="pernyataanBody{{ $pernyataan['id'] }}" class="accordion-collapse collapse">
                                            <div class="pernyataan-body">

                                                @if(isset($pernyataan['kriteria']) && !empty($pernyataan['kriteria']))
                                                <span class="label-section">Kriteria</span>
                                                @foreach ($pernyataan['kriteria'] as $kriteria)
                                                <div class="accordion-item kriteria-item">

                                                    <!-- Kriteria header -->
                                                    <h2 class="accordion-header">
                                                        <div class="d-flex align-items-center w-100 pe-3">
                                                            @can('view.evidence.audit.smp.admin')
                                                            <button
                                                                class="accordion-button collapsed flex-grow-1 py-2 fs-13 text-muted"
                                                                type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#kriteriaBody{{ $kriteria['id'] }}"
                                                                aria-expanded="false"
                                                            >
                                                            @else
                                                            <div class="flex-grow-1 py-2 fs-13 px-3 text-muted">
                                                            @endcan
                                                                {{ $kriteria['name'] }}
                                                            @can('view.evidence.audit.smp.admin')
                                                            </button>
                                                            @else
                                                            </div>
                                                            @endcan

                                                            <div class="btn-action-group ms-3">
                                                                @can('create.evidence.audit.smp.admin')
                                                                <a href="{{route('admin.evidence.audit-smp.create',['audit'=>$kriteria['id']])}}" class="btn btn-soft-success btn-xs">+ Evidence</a>
                                                                @endcan
                                                                @can('edit.criteria.audit.smp.admin')
                                                                <a href="{{route('admin.criteria.audit-smp.edit',['kriteria'=>$kriteria['id'],'audit'=>$pernyataan['id']])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                                @endcan
                                                                @can('delete.criteria.audit.smp.admin')
                                                                <form id="del-kriteria-{{ $kriteria['id'] }}" action="{{route('admin.criteria.audit-smp.delete',['kriteria'=>$kriteria['id'],'audit'=>$pernyataan['id']])}}" method="POST" class="m-0">
                                                                    @csrf @method('DELETE')
                                                                    <button type="button" class="btn btn-soft-danger btn-xs"
                                                                        onclick="confirmDelete('del-kriteria-{{ $kriteria['id'] }}','Kriteria akan dihapus.')">Hapus</button>
                                                                </form>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </h2>

                                                    @can('view.evidence.audit.smp.admin')
                                                    <div id="kriteriaBody{{ $kriteria['id'] }}" class="accordion-collapse collapse">
                                                        <div class="kriteria-body">
                                                            @if(isset($kriteria['evidence']) && !empty($kriteria['evidence']))
                                                            <span class="label-section">Evidence</span>
                                                            @foreach ($kriteria['evidence'] as $evidence)
                                                            <div class="evident-item d-flex justify-content-between align-items-center">
                                                                <div class="text-dark fs-13">
                                                                    <span class="text-primary fw-bold me-2">{{ $loop->iteration }}.</span>{{ $evidence['name'] }}
                                                                </div>
                                                                <div class="btn-action-group">
                                                                    @can('edit.evidence.audit.smp.admin')
                                                                    <a href="{{route('admin.evidence.audit-smp.edit',['audit'=>$kriteria['id'],'evidence'=>$evidence['id']])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                                    @endcan
                                                                    @can('delete.evidence.audit.smp.admin')
                                                                    <form id="del-evidence-{{ $evidence['id'] }}" action="{{route('admin.evidence.audit-smp.delete',['audit'=>$kriteria['id'],'evidence'=>$evidence['id']])}}" method="POST" class="m-0">
                                                                        @csrf @method('DELETE')
                                                                        <button type="button" class="btn btn-soft-danger btn-xs"
                                                                            onclick="confirmDelete('del-evidence-{{ $evidence['id'] }}','Evidence akan dihapus.')">Hapus</button>
                                                                    </form>
                                                                    @endcan
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @else
                                                            <p class="text-muted small text-center m-0">Belum ada evidence.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endcan

                                                </div>{{-- end kriteria-item --}}
                                                @endforeach
                                                @else
                                                <p class="text-muted small text-center m-0">Belum ada kriteria.</p>
                                                @endif

                                            </div>
                                        </div>
                                        @endcan

                                    </div>{{-- end pernyataan-item --}}
                                    @endforeach
                                </div>
                                @endif

                                {{-- ── Kriteria langsung di bawah Audit ── --}}
                                @if(isset($audit['kriteria']) && !empty($audit['kriteria']))
                                <span class="label-section mt-2">Kriteria</span>
                                <div>
                                    @foreach ($audit['kriteria'] as $kriteria)
                                    <div class="accordion-item kriteria-item">

                                        <h2 class="accordion-header">
                                            <div class="d-flex align-items-center w-100 pe-3">
                                                @can('view.evidence.audit.smp.admin')
                                                <button
                                                    class="accordion-button collapsed flex-grow-1 py-2 fs-13 text-muted"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#kriteriaDirectBody{{ $kriteria['id'] }}"
                                                    aria-expanded="false"
                                                >
                                                @else
                                                <div class="flex-grow-1 py-2 fs-13 px-3 text-muted">
                                                @endcan
                                                    {{ $kriteria['name'] }}
                                                @can('view.evidence.audit.smp.admin')
                                                </button>
                                                @else
                                                </div>
                                                @endcan

                                                <div class="btn-action-group ms-3">
                                                    @can('create.evidence.audit.smp.admin')
                                                    <a href="{{route('admin.evidence.audit-smp.create',['audit'=>$kriteria['id']])}}" class="btn btn-soft-success btn-xs">+ Evidence</a>
                                                    @endcan
                                                    @can('edit.criteria.audit.smp.admin')
                                                    <a href="{{route('admin.criteria.audit-smp.edit',['kriteria'=>$kriteria['id'],'audit'=>$audit['id']])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                    @endcan
                                                    @can('delete.criteria.audit.smp.admin')
                                                    <form id="del-kriteria-direct-{{ $kriteria['id'] }}" action="{{route('admin.criteria.audit-smp.delete',['kriteria'=>$kriteria['id'],'audit'=>$audit['id']])}}" method="POST" class="m-0">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-soft-danger btn-xs"
                                                            onclick="confirmDelete('del-kriteria-direct-{{ $kriteria['id'] }}','Kriteria akan dihapus.')">Hapus</button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </h2>

                                        @can('view.evidence.audit.smp.admin')
                                        <div id="kriteriaDirectBody{{ $kriteria['id'] }}" class="accordion-collapse collapse">
                                            <div class="kriteria-body">
                                                @if(isset($kriteria['evidence']) && !empty($kriteria['evidence']))
                                                <span class="label-section">Evidence</span>
                                                @foreach ($kriteria['evidence'] as $evidence)
                                                <div class="evident-item d-flex justify-content-between align-items-center">
                                                    <div class="text-dark fs-13">
                                                        <span class="text-primary fw-bold me-2">{{ $loop->iteration }}.</span>{{ $evidence['name'] }}
                                                    </div>
                                                    <div class="btn-action-group">
                                                        @can('edit.evidence.audit.smp.admin')
                                                        <a href="{{route('admin.evidence.audit-smp.edit',['audit'=>$kriteria['id'],'evidence'=>$evidence['id']])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                        @endcan
                                                        @can('delete.evidence.audit.smp.admin')
                                                        <form id="del-evidence-direct-{{ $evidence['id'] }}" action="{{route('admin.evidence.audit-smp.delete',['audit'=>$kriteria['id'],'evidence'=>$evidence['id']])}}" method="POST" class="m-0">
                                                            @csrf @method('DELETE')
                                                            <button type="button" class="btn btn-soft-danger btn-xs"
                                                                onclick="confirmDelete('del-evidence-direct-{{ $evidence['id'] }}','Evidence akan dihapus.')">Hapus</button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                                @endforeach
                                                @else
                                                <p class="text-muted small text-center m-0">Belum ada evidence.</p>
                                                @endif
                                            </div>
                                        </div>
                                        @endcan

                                    </div>{{-- end kriteria-item --}}
                                    @endforeach
                                </div>
                                @endif

                            </div>
                        </div>
                        @endcan

                    </div>{{-- end audit-item --}}
                    @endforeach
                </div>{{-- end auditAccordion --}}

            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
@endsection
