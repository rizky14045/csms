@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }

    .area-item {
        border: 1px solid #eef2f7;
        border-left: 5px solid #4e73df;
        border-radius: 10px !important;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .subarea-item {
        border: 1px solid #e3f9ef;
        border-left: 4px solid #1cc88a;
        border-radius: 8px !important;
        margin-bottom: 0.6rem;
        overflow: hidden;
    }
    .level-item {
        border: 1px solid #fff3cd;
        border-left: 4px solid #f6c23e;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
        overflow: hidden;
    }
    .accordion-button { padding: 0.75rem 1rem; font-weight: 600; color: #343a40; }
    .accordion-button:not(.collapsed) { background-color: #f8faff; box-shadow: none; }

    .btn-xs { padding: 3px 10px; font-size: 11px; border-radius: 5px; font-weight: 600; white-space: nowrap; }
    .btn-action-group { display: flex; gap: 6px; align-items: center; flex-wrap: nowrap; }

    .btn-soft-primary { background:#e8eeff; color:#4e73df; border:1px solid #d1dfff; }
    .btn-soft-primary:hover { background:#4e73df; color:#fff; }
    .btn-soft-success { background:#e3f9ef; color:#1cc88a; border:1px solid #c9f2de; }
    .btn-soft-success:hover { background:#1cc88a; color:#fff; }
    .btn-soft-warning { background:#fff9e6; color:#d4a000; border:1px solid #ffecb3; }
    .btn-soft-warning:hover { background:#f6c23e; color:#fff; }
    .btn-soft-danger  { background:#ffeef0; color:#e74a3b; border:1px solid #ffdadd; }
    .btn-soft-danger:hover  { background:#e74a3b; color:#fff; }

    .note-item {
        border: 1px dashed #d1d3e2; border-radius: 7px;
        padding: 10px 14px; margin-bottom: 6px;
        background: #fafbff; transition: border-color .2s;
    }
    .note-item:hover { border-color: #4e73df; }

    .label-section {
        font-size: 10px; font-weight: 800; color: #a0a4c0;
        text-transform: uppercase; letter-spacing: .6px;
        display: block; margin-bottom: 8px;
    }
    .subarea-body { background: #f9fbff; padding: 12px 14px; }
    .level-body   { background: #fffdf5; padding: 10px 14px; }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">KPI</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data KPI</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="m-0 fw-semibold text-dark">Struktur KPI Area</h5>
                    @can('create.kpi.area')
                    <a href="{{route('admin.kpi-area.create')}}" class="btn btn-sm btn-primary">
                        <i data-feather="plus" style="width:14px;height:14px;margin-right:4px;"></i>Tambah Area
                    </a>
                    @endcan
                </div>

                <div class="accordion" id="kpiAccordion">
                    @foreach ($areas as $area)
                    <div class="accordion-item area-item">

                        <h2 class="accordion-header">
                            <div class="d-flex align-items-center w-100 pe-3">
                                @can('view.kpi.subarea')
                                <button class="accordion-button collapsed flex-grow-1" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#areaBody{{ $area->id }}">
                                @else
                                <div class="accordion-button collapsed flex-grow-1 pe-none" style="cursor:default;">
                                @endcan
                                    <span class="fw-bold">{{ $area->name }}</span>
                                @can('view.kpi.subarea')
                                </button>
                                @else
                                </div>
                                @endcan

                                <div class="btn-action-group ms-3">
                                    @can('edit.kpi.area')
                                    <a href="{{route('admin.kpi-area.edit',['area'=>$area->id])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                    @endcan
                                    @can('delete.kpi.area')
                                    <form id="del-area-{{ $area->id }}" action="{{route('admin.kpi-area.destroy',['area'=>$area->id])}}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-soft-danger btn-xs"
                                            onclick="confirmDelete('del-area-{{ $area->id }}','Area akan dihapus.')">Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </h2>

                        @can('view.kpi.subarea')
                        <div id="areaBody{{ $area->id }}" class="accordion-collapse collapse">
                            <div class="accordion-body bg-white pb-3">

                                @can('create.kpi.subarea')
                                <div class="mb-3">
                                    <a href="{{route('admin.kpi-sub-area.create',['area'=>$area->id])}}" class="btn btn-soft-primary btn-xs px-3 py-2">
                                        <i data-feather="plus-circle" style="width:12px;height:12px;margin-right:4px;"></i>Tambah Sub Area
                                    </a>
                                </div>
                                @endcan

                                @if(!empty($area->sub_areas))
                                <span class="label-section">Sub Area</span>
                                @foreach ($area->sub_areas as $subArea)
                                <div class="accordion-item subarea-item">

                                    <h2 class="accordion-header">
                                        <div class="d-flex align-items-center w-100 pe-3">
                                            @can('view.kpi.level')
                                            <button class="accordion-button collapsed flex-grow-1 py-2 fs-13" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#subareaBody{{ $subArea->id }}">
                                            @else
                                            <div class="flex-grow-1 py-2 fs-13 px-3 fw-semibold">
                                            @endcan
                                                <div>
                                                    <div class="fw-semibold">{{ $subArea->name }}</div>
                                                    @if($subArea->description)
                                                    <div class="text-muted" style="font-size:11px;">{{ Str::limit($subArea->description, 80) }}</div>
                                                    @endif
                                                </div>
                                            @can('view.kpi.level')
                                            </button>
                                            @else
                                            </div>
                                            @endcan

                                            <div class="btn-action-group ms-3">
                                                @can('create.kpi.level')
                                                <a href="{{route('admin.kpi-level.create',['sub_area'=>$subArea->id])}}" class="btn btn-soft-success btn-xs">+ Level</a>
                                                @endcan
                                                @can('edit.kpi.subarea')
                                                <a href="{{route('admin.kpi-sub-area.edit',['sub_area'=>$subArea->id,'area'=>$area->id])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                @endcan
                                                @can('delete.kpi.subarea')
                                                <form id="del-subarea-{{ $subArea->id }}" action="{{route('admin.kpi-sub-area.destroy',['sub_area'=>$subArea->id,'area'=>$area->id])}}" method="POST" class="m-0">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-soft-danger btn-xs"
                                                        onclick="confirmDelete('del-subarea-{{ $subArea->id }}','Sub Area akan dihapus.')">Hapus</button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </h2>

                                    @can('view.kpi.level')
                                    <div id="subareaBody{{ $subArea->id }}" class="accordion-collapse collapse">
                                        <div class="subarea-body">
                                            @if(!empty($subArea->levels))
                                            <span class="label-section">Level</span>
                                            @foreach ($subArea->levels as $level)
                                            <div class="accordion-item level-item">

                                                <h2 class="accordion-header">
                                                    <div class="d-flex align-items-center w-100 pe-3">
                                                        @can('view.kpi.note')
                                                        <button class="accordion-button collapsed flex-grow-1 py-2 fs-13 text-muted" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#levelBody{{ $level->id }}">
                                                        @else
                                                        <div class="flex-grow-1 py-2 fs-13 px-3 text-muted">
                                                        @endcan
                                                            <span class="badge bg-warning text-dark me-2" style="font-size:10px;">Level {{ $level->level }}</span>
                                                            {{ $level->description }}
                                                        @can('view.kpi.note')
                                                        </button>
                                                        @else
                                                        </div>
                                                        @endcan

                                                        <div class="btn-action-group ms-3">
                                                            @can('create.kpi.note')
                                                            <a href="{{route('admin.kpi-note.create',['level'=>$level->id])}}" class="btn btn-soft-success btn-xs">+ Note</a>
                                                            @endcan
                                                            @can('edit.kpi.level')
                                                            <a href="{{route('admin.kpi-level.edit',['level'=>$level->id,'sub_area'=>$subArea->id])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                            @endcan
                                                            @can('delete.kpi.level')
                                                            <form id="del-level-{{ $level->id }}" action="{{route('admin.kpi-level.destroy',['level'=>$level->id,'sub_area'=>$subArea->id])}}" method="POST" class="m-0">
                                                                @csrf @method('DELETE')
                                                                <button type="button" class="btn btn-soft-danger btn-xs"
                                                                    onclick="confirmDelete('del-level-{{ $level->id }}','Level akan dihapus.')">Hapus</button>
                                                            </form>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </h2>

                                                @can('view.kpi.note')
                                                <div id="levelBody{{ $level->id }}" class="accordion-collapse collapse">
                                                    <div class="level-body">
                                                        @if(!empty($level->notes))
                                                        <span class="label-section">Note</span>
                                                        @foreach ($level->notes as $note)
                                                        <div class="note-item d-flex justify-content-between align-items-center">
                                                            <div class="text-dark fs-13">
                                                                <span class="text-primary fw-bold me-2">{{ $loop->iteration }}.</span>{{ $note->note }}
                                                            </div>
                                                            <div class="btn-action-group">
                                                                @can('edit.kpi.note')
                                                                <a href="{{route('admin.kpi-note.edit',['note'=>$note->id,'level'=>$level->id])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                                @endcan
                                                                @can('delete.kpi.note')
                                                                <form id="del-note-{{ $note->id }}" action="{{route('admin.kpi-note.destroy',['note'=>$note->id,'level'=>$level->id])}}" method="POST" class="m-0">
                                                                    @csrf @method('DELETE')
                                                                    <button type="button" class="btn btn-soft-danger btn-xs"
                                                                        onclick="confirmDelete('del-note-{{ $note->id }}','Catatan akan dihapus.')">Hapus</button>
                                                                </form>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        @else
                                                        <p class="text-muted small text-center m-0">Belum ada note.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endcan

                                            </div>{{-- end level-item --}}
                                            @endforeach
                                            @else
                                            <p class="text-muted small text-center m-0">Belum ada level.</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endcan

                                </div>{{-- end subarea-item --}}
                                @endforeach
                                @else
                                <p class="text-muted small text-center">Belum ada sub area.</p>
                                @endif

                            </div>
                        </div>
                        @endcan

                    </div>{{-- end area-item --}}
                    @endforeach
                </div>

            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
</div>
@endsection
