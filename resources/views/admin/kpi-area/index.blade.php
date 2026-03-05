@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
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
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end pe-3">
                    @can('view.kpi.area')
                        <a href="{{route('admin.kpi-area.create')}}" class="btn btn-sm btn-primary mb-3">Tambah Area</a>
                    @endcan
                </div>
                <!-- Komitmen Management -->
                <div class="accordion" id="formAccordion">

                <!-- Section A: Komitmen Management -->
                    <div class="accordion-item">
                        @foreach ($areas as $area)
                            
                        <h2 class="accordion-header bg-light" id="heading{{$area->id}}">
                            <div
                                class="d-flex justify-content-between align-items-center w-100"
                                style="padding: 10px 15px; border: none;"
                            >
                                <!-- Area interaktif untuk accordion -->
                                <div
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#area_{{$area->id}}"
                                    aria-expanded="false"
                                    aria-controls="collapseA"
                                    style="flex: 1; border: none; background-color: transparent;"
                                >
                                    <span class="fw-bold" style="white-space: normal; word-break: break-word;">{{$area->name}}</span>
                                </div>
                                    <div style="display: flex;align-items:center;gap:10px">
                                        @can('edit.kpi.area')
                                        <a href="{{route('admin.kpi-area.edit',['area' => $area->id])}}" class="btn btn-warning btn-sm mt-1">Edit Kategori</a>
                                        @endcan
                                        @can('delete.kpi.area')
                                        <form
                                            id="delete-kpi-area-{{ $area->id }}"
                                            action="{{route('admin.kpi-area.destroy',['area'=>$area->id])}}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-kpi-area-{{ $area->id }}',
                                                    'Area akan dihapus.'
                                                )"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                            </div>
                        </h2>
                        @can('view.kpi.subarea')
                        <div id="area_{{$area->id}}" class="accordion-collapse collapse" aria-labelledby="category{{$area->id}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                @can('create.kpi.subarea')
                                <a href="{{route('admin.kpi-sub-area.create',['area'=>$area->id])}}" class="btn btn-primary btn-sm mb-3">Tambah Sub Area</a>
                                @endcan
                                <table class="table table-bordered text-center">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Sub Area</th>
                                        <th scope="col">Uraian</th>
                                        <th scope="col">Referensi</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($area->sub_areas as $subArea)
                                            
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-start">{{$subArea->name}}</td>
                                                <td class="text-start">{{$subArea->description}}</td>
                                                <td class="text-start">{{$subArea->reference}}</td>
                                                <td class="">
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @can('create.kpi.level')
                                                        <a href="{{route('admin.kpi-level.create',['sub_area'=> $subArea->id])}}" class="btn btn-success btn-sm">Tambah Level</a>
                                                        @endcan
                                                        @can('edit.kpi.subarea')
                                                        <a href="{{route('admin.kpi-sub-area.edit',['sub_area'=>$subArea->id,'area'=> $area->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                        @endcan
                                                        @can('delete.kpi.subarea')
                                                        <form
                                                            id="delete-kpi-subarea-{{ $subArea->id }}"
                                                            action="{{ route('admin.kpi-sub-area.destroy',['sub_area'=>$subArea->id,'area'=> $area->id]) }}"
                                                            method="POST"
                                                            class="d-inline"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete(
                                                                    'delete-kpi-subarea-{{ $subArea->id }}',
                                                                    'Sub Area akan dihapus.'
                                                                )"
                                                            >
                                                                Hapus
                                                            </button>
                                                        </form>
                                                        @endcan
                                                        @can('view.kpi.level')
                                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#accordionRow{{$subArea->id}}" aria-expanded="false" aria-controls="accordionRow{{$subArea->id}}">
                                                            Lihat Detail Level
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            @can('view.kpi.level')
                                            <tr id="accordionRow{{$subArea->id}}" class="collapse accordion-content">
                                                <td colspan="6">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center align-middle">No</th>
                                                                <th class="text-center align-middle">Level</th>
                                                                <th class="text-center align-middle">Deskripsi</th>
                                                                <th class="text-center align-middle">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (isset($subArea->levels))
                                                                
                                                                @foreach ($subArea->levels as $level)   
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>Level {{$level->level}}</td>
                                                                        <td class="text-start">{{$level->description}}</td>
                                                                        <td class="">
                                                                            <div class="d-flex flex-wrap gap-2">
                                                                                @can('create.kpi.note')
                                                                                <a href="{{route('admin.kpi-note.create',['level'=> $level->id])}}" class="btn btn-success btn-sm">Tambah Note</a>
                                                                                @endcan
                                                                                @can('edit.kpi.level')
                                                                                <a href="{{route('admin.kpi-level.edit',['level'=> $level->id,'sub_area' => $subArea->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                @endcan
                                                                                @can('delete.kpi.level')
                                                                                <form
                                                                                    id="delete-kpi-level-{{ $level->id }}"
                                                                                    action="{{ route('admin.kpi-level.destroy',['level'=> $level->id,'sub_area' => $subArea->id]) }}"
                                                                                    method="POST"
                                                                                    class="d-inline"
                                                                                >
                                                                                    @csrf
                                                                                    @method('DELETE')

                                                                                    <button
                                                                                        type="button"
                                                                                        class="btn btn-danger btn-sm"
                                                                                        onclick="confirmDelete(
                                                                                            'delete-kpi-level-{{ $level->id }}',
                                                                                            'Level akan dihapus.'
                                                                                        )"
                                                                                    >
                                                                                        Hapus
                                                                                    </button>
                                                                                </form>
                                                                                @endcan
                                                                                @can('view.kpi.note')
                                                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#noteRow{{$level->id}}" aria-expanded="false" aria-controls="noteRow{{$level->id}}">
                                                                                    Lihat Detail Note
                                                                                </button>
                                                                                @endcan
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @can('view.kpi.note')
                                                                    <tr id="noteRow{{$level->id ?? ''}}" class="collapse accordion-content">
                                                                        <td colspan="6">
                                                                            <table class="table table-bordered text-center">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th class="text-center align-middle">No</th>
                                                                                        <th class="text-center align-middle">Note</th>
                                                                                        <th class="text-center align-middle">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($level->notes as $note)   
                                                                                        <tr>
                                                                                            <td>{{$loop->iteration}}</td>
                                                                                            <td>{{$note->note}}</td>
                                                                                            <td class="">
                                                                                                <div class="d-flex justify-content-end gap-2">
                                                                                                    @can('edit.kpi.note')
                                                                                                    <a href="{{route('admin.kpi-note.edit',['note' => $note->id,'level'=> $level->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                                    @endcan
                                                                                                    @can('delete.kpi.note')
                                                                                                    <form
                                                                                                        id="delete-kpi-note-{{ $note->id }}"
                                                                                                        action="{{ route('admin.kpi-note.destroy',['note' => $note->id,'level'=> $level->id]) }}"
                                                                                                        method="POST"
                                                                                                        class="d-inline"
                                                                                                    >
                                                                                                        @csrf
                                                                                                        @method('DELETE')

                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-danger btn-sm"
                                                                                                            onclick="confirmDelete(
                                                                                                                'delete-kpi-note-{{ $note->id }}',
                                                                                                                'Catatan akan dihapus.'
                                                                                                            )"
                                                                                                        >
                                                                                                            Hapus
                                                                                                        </button>
                                                                                                    </form>
                                                                                                    @endcan
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>  
                                                                        </td>
                                                                    </tr>
                                                                    @endcan
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>  
                                                </td>
                                            </tr>
                                            @endcan
                                        @endforeach

                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endcan
                        @endforeach
                    </div>
                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

