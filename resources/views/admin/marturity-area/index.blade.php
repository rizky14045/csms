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
        <h4 class="fs-18 fw-semibold m-0">Marturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Marturity</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end pe-3">
                    @can('create.marturity.area')
                        <a href="{{route('admin.marturity-area.create')}}" class="btn btn-sm btn-primary mb-3">Tambah Area</a>
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
                                    <span class="fw-bold">{{$area->name}}</span>
                                </div>
                        
                                <!-- Tombol yang bisa diklik -->
                                <div style="display: flex; gap: 10px;align-items: center;">
                                    @can('edit.marturity.area')
                                    <a href="{{route('admin.marturity-area.edit',['area' => $area->id])}}" class="btn btn-warning btn-sm mt-1">Edit Area</a>
                                    @endcan
                                    @can('delete.marturity.area')
                                    <form
                                        id="delete-maturity-area-{{ $area->id }}"
                                        action="{{route('admin.marturity-area.destroy',['area'=>$area->id])}}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmDelete(
                                                'delete-maturity-area-{{ $area->id }}',
                                                'Area akan dihapus.'
                                            )"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </h2>
                        @can('view.marturity.subarea')
                        <div id="area_{{$area->id}}" class="accordion-collapse collapse" aria-labelledby="category{{$area->id}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                @can('create.marturity.subarea')
                                <a href="{{route('admin.marturity-sub-area.create',['area'=>$area->id])}}" class="btn btn-primary btn-sm mb-3">Tambah Sub Area</a>
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
                                                        @can('create.marturity.level')
                                                        <a href="{{route('admin.marturity-level.create',['sub_area'=> $subArea->id])}}" class="btn btn-success btn-sm">Tambah Level</a>
                                                        @endcan
                                                        @can('edit.marturity.subarea')
                                                        <a href="{{route('admin.marturity-sub-area.edit',['sub_area'=>$subArea->id,'area'=> $area->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                        @endcan
                                                        @can('delete.marturity.subarea')
                                                        <form
                                                            id="delete-maturity-subarea-{{ $subArea->id }}"
                                                            action="{{route('admin.marturity-sub-area.destroy',['sub_area'=>$subArea->id,'area'=>$area->id])}}"
                                                            method="POST"
                                                            class="d-inline"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete(
                                                                    'delete-maturity-subarea-{{ $subArea->id }}',
                                                                    'Sub Area akan dihapus.'
                                                                )"
                                                            >
                                                                Hapus
                                                            </button>
                                                        </form>
                                                        @endcan
                                                        @can('view.marturity.level')
                                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#accordionRow{{$subArea->id}}" aria-expanded="false" aria-controls="accordionRow{{$subArea->id}}">
                                                            Lihat Detail Level
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            @can('view.marturity.level')
                                            <tr id="accordionRow{{$subArea->id}}" class="collapse accordion-content">
                                                <td colspan="6">
                                                    <table class="table table-bordered">
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
                                                                                @can('create.marturity.note')
                                                                                <a href="{{route('admin.marturity-note.create',['level'=> $level->id])}}" class="btn btn-success btn-sm">Tambah Note</a>
                                                                                @endcan
                                                                                @can('edit.marturity.level')
                                                                                <a href="{{route('admin.marturity-level.edit',['level'=> $level->id,'sub_area' => $subArea->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                @endcan
                                                                                @can('delete.marturity.level')
                                                                                <form
                                                                                    id="delete-maturity-level-{{ $level->id }}"
                                                                                    action="{{route('admin.marturity-level.destroy',['level'=>$level->id,'sub_area'=>$subArea->id])}}"
                                                                                    method="POST"
                                                                                    class="d-inline"
                                                                                >
                                                                                    @csrf
                                                                                    @method('DELETE')

                                                                                    <button
                                                                                        type="button"
                                                                                        class="btn btn-danger btn-sm"
                                                                                        onclick="confirmDelete(
                                                                                            'delete-maturity-level-{{ $level->id }}',
                                                                                            'Level akan dihapus.'
                                                                                        )"
                                                                                    >
                                                                                        Hapus
                                                                                    </button>
                                                                                </form>
                                                                                @endcan
                                                                                @can('view.marturity.note')
                                                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#noteRow{{$level->id}}" aria-expanded="false" aria-controls="noteRow{{$level->id}}">
                                                                                    Lihat Detail Note
                                                                                </button>
                                                                                @endcan
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @can('view.marturity.note')
                                                                    <tr id="noteRow{{$level->id}}" class="collapse accordion-content">
                                                                        <td colspan="6">
                                                                            <table class="table table-bordered">
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
                                                                                            <td class="text-left">{{$loop->iteration}}</td>
                                                                                            <td class="text-left">{{$note->note}}</td>
                                                                                            <td class="">
                                                                                                <div class="d-flex justify-content-end gap-2">
                                                                                                    @can('edit.marturity.note')
                                                                                                    <a href="{{route('admin.marturity-note.edit',['note' => $note->id,'level'=> $level->id,])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                                    @endcan
                                                                                                    @can('delete.marturity.note')
                                                                                                    <form
                                                                                                        id="delete-maturity-note-{{ $note->id }}"
                                                                                                        action="{{route('admin.marturity-note.destroy',['note'=>$note->id,'level'=>$level->id])}}"
                                                                                                        method="POST"
                                                                                                        class="d-inline"
                                                                                                    >
                                                                                                        @csrf
                                                                                                        @method('DELETE')

                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-danger btn-sm"
                                                                                                            onclick="confirmDelete(
                                                                                                                'delete-maturity-note-{{ $note->id }}',
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

