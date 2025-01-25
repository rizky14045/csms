@extends('admin.layout.app')
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
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data KPI</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('admin.kpi-area.create')}}" class="btn btn-sm btn-success mb-3">Tambah Area</a>
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
                                    
                                    <a href="{{route('admin.kpi-area.edit',['areaId' => $area->id])}}" class="btn btn-warning btn-sm mt-1">Edit Kategori</a>
                                    <form action="{{route('admin.kpi-area.destroy',['areaId'=>$area->id])}}" method="post" class="d-block ms-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                            </div>
                        </h2>
                        <div id="area_{{$area->id}}" class="accordion-collapse collapse" aria-labelledby="category{{$area->id}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <a href="{{route('admin.kpi-sub-area.create',['areaId'=>$area->id])}}" class="btn btn-success btn-sm mb-3">Tambah Sub Area</a>
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
                                        @foreach ($area->subAreas as $subArea)
                                            
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-start">{{$subArea->name}}</td>
                                                <td class="text-start">{{$subArea->description}}</td>
                                                <td class="text-start">{{$subArea->reference}}</td>
                                                <td class="">
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="{{route('admin.kpi-level.create',['subAreaId'=> $subArea->id])}}" class="btn btn-success btn-sm">Tambah Level</a>
                                                       
                                                        <a href="{{route('admin.kpi-sub-area.edit',['subAreaId'=>$subArea->id,'areaId'=> $area->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                        <form action="{{route('admin.kpi-sub-area.destroy',['subAreaId'=>$subArea->id,'areaId'=> $area->id])}}" method="post" class="d-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#accordionRow{{$subArea->id}}" aria-expanded="false" aria-controls="accordionRow{{$subArea->id}}">
                                                            Lihat Detail Level
                                                        </button>
                                                    </div>
                                                </td>
                                                
                                            </tr>
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
                                                            @if ($subArea->levels->isNotEmpty())
                                                                
                                                                @foreach ($subArea->levels as $level)   
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>Level {{$level->level}}</td>
                                                                        <td class="text-start">{{$level->description}}</td>
                                                                        <td class="">
                                                                            <div class="d-flex flex-wrap gap-2">
                                                                                <a href="{{route('admin.kpi-note.create',['levelId'=> $level->id])}}" class="btn btn-success btn-sm">Tambah Note</a>
                                                        
                                                                                <a href="{{route('admin.kpi-level.edit',['levelId'=> $level->id,'subAreaId' => $subArea->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                <form action="{{route('admin.kpi-level.destroy',['levelId'=> $level->id,'subAreaId' => $subArea->id])}}" method="post" class="d-block">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                                                </form>
                                                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#noteRow{{$level->id}}" aria-expanded="false" aria-controls="noteRow{{$level->id}}">
                                                                                    Lihat Detail Note
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                        </tbody>
                                                    </table>  
                                                </td>
                                            </tr>
                                            @if ($subArea->levels->isNotEmpty())
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

                                                                            <a href="{{route('admin.kpi-note.edit',['noteId' => $note->id,'levelId'=> $level->id,])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                            <form action="{{route('admin.kpi-note.destroy',['noteId' => $note->id,'levelId'=> $level->id])}}" method="post" class="d-block">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>  
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach

                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

