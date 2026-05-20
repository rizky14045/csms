@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }

    .cat-item {
        border: 1px solid #eef2f7;
        border-left: 5px solid #4e73df;
        border-radius: 10px !important;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .question-item {
        border: 1px solid #e3f9ef;
        border-left: 4px solid #1cc88a;
        border-radius: 8px !important;
        margin-bottom: 0.6rem;
        overflow: hidden;
    }
    .level-item {
        border: 1px solid #fff3cd;
        border-left: 4px solid #f6c23e;
        border-radius: 7px;
        padding: 10px 14px;
        margin-bottom: 6px;
        background: #fffdf5;
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

    .label-section {
        font-size: 10px; font-weight: 800; color: #a0a4c0;
        text-transform: uppercase; letter-spacing: .6px;
        display: block; margin-bottom: 8px;
    }
    .question-body { background: #f9fbff; padding: 12px 14px; }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Assesment</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="m-0 fw-semibold text-dark">Struktur Kategori Assesment</h5>
                    @can('create.category.assesment')
                    <a href="{{route('admin.category-assesment.create')}}" class="btn btn-sm btn-primary">
                        <i data-feather="plus" style="width:14px;height:14px;margin-right:4px;"></i>Tambah Kategori
                    </a>
                    @endcan
                </div>

                <div class="accordion" id="catAccordion">
                    @foreach ($categories as $category)
                    <div class="accordion-item cat-item">

                        <h2 class="accordion-header">
                            <div class="d-flex align-items-center w-100 pe-3">
                                @can('view.question.assesment')
                                <button class="accordion-button collapsed flex-grow-1" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#catBody{{ $category->id }}">
                                @else
                                <div class="accordion-button collapsed flex-grow-1 pe-none" style="cursor:default;">
                                @endcan
                                    <span class="fw-bold">{{ $category->name }}</span>
                                @can('view.question.assesment')
                                </button>
                                @else
                                </div>
                                @endcan

                                <div class="btn-action-group ms-3">
                                    @can('edit.category.assesment')
                                    <a href="{{route('admin.category-assesment.edit',['category_assesment'=>$category->id])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                    @endcan
                                    @can('delete.category.assesment')
                                    <form id="del-cat-{{ $category->id }}" action="{{route('admin.category-assesment.destroy',['category_assesment'=>$category->id])}}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-soft-danger btn-xs"
                                            onclick="confirmDelete('del-cat-{{ $category->id }}','Kategori akan dihapus.')">Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </h2>

                        @can('view.question.assesment')
                        <div id="catBody{{ $category->id }}" class="accordion-collapse collapse">
                            <div class="accordion-body bg-white pb-3">

                                @can('create.question.assesment')
                                <div class="mb-3">
                                    <a href="{{route('admin.question-assesment.create',['category_assesment'=>$category->id])}}" class="btn btn-soft-primary btn-xs px-3 py-2">
                                        <i data-feather="plus-circle" style="width:12px;height:12px;margin-right:4px;"></i>Tambah Indikator
                                    </a>
                                </div>
                                @endcan

                                @if(!empty($category->questions))
                                <span class="label-section">Indikator / Pertanyaan</span>
                                @foreach ($category->questions as $question)
                                <div class="accordion-item question-item">

                                    <h2 class="accordion-header">
                                        <div class="d-flex align-items-center w-100 pe-3">
                                            @can('view.level.assesment')
                                            <button class="accordion-button collapsed flex-grow-1 py-2 fs-13" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#questionBody{{ $question->id }}">
                                            @else
                                            <div class="flex-grow-1 py-2 fs-13 px-3">
                                            @endcan
                                                {{ $question->indicator }}
                                            @can('view.level.assesment')
                                            </button>
                                            @else
                                            </div>
                                            @endcan

                                            <div class="btn-action-group ms-3">
                                                @can('create.level.assesment')
                                                <a href="{{route('admin.level-assesment.create',['question_assesment'=>$question->id])}}" class="btn btn-soft-success btn-xs">+ Level</a>
                                                @endcan
                                                @can('edit.question.assesment')
                                                <a href="{{route('admin.question-assesment.edit',['question_assesment'=>$question->id,'category_assesment'=>$category->id])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                @endcan
                                                @can('delete.question.assesment')
                                                <form id="del-question-{{ $question->id }}" action="{{route('admin.question-assesment.destroy',['question_assesment'=>$question->id,'category_assesment'=>$category->id])}}" method="POST" class="m-0">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-soft-danger btn-xs"
                                                        onclick="confirmDelete('del-question-{{ $question->id }}','Indikator akan dihapus.')">Hapus</button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </h2>

                                    @can('view.level.assesment')
                                    <div id="questionBody{{ $question->id }}" class="accordion-collapse collapse">
                                        <div class="question-body">
                                            @if(!empty($question->levels))
                                            <span class="label-section">Level</span>
                                            @foreach ($question->levels as $level)
                                            <div class="level-item d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1 fs-13">
                                                    <span class="badge bg-warning text-dark me-2" style="font-size:10px;">Level {{ $level->level }}</span>
                                                    <span class="text-dark">{{ $level->level_description }}</span>
                                                </div>
                                                <div class="btn-action-group ms-3 mt-1">
                                                    @can('edit.level.assesment')
                                                    <a href="{{route('admin.level-assesment.edit',['level_assesment'=>$level->id,'question_assesment'=>$question->id])}}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                    @endcan
                                                    @can('delete.level.assesment')
                                                    <form id="del-level-{{ $level->id }}" action="{{route('admin.level-assesment.destroy',['level_assesment'=>$level->id,'question_assesment'=>$question->id])}}" method="POST" class="m-0">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-soft-danger btn-xs"
                                                            onclick="confirmDelete('del-level-{{ $level->id }}','Level akan dihapus.')">Hapus</button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                            <p class="text-muted small text-center m-0">Belum ada level.</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endcan

                                </div>{{-- end question-item --}}
                                @endforeach
                                @else
                                <p class="text-muted small text-center">Belum ada indikator.</p>
                                @endif

                            </div>
                        </div>
                        @endcan

                    </div>{{-- end cat-item --}}
                    @endforeach
                </div>

            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
</div>
@endsection
