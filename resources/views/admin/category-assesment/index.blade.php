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
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end pe-3">
                    @can('create.category.assesment')
                        <a href="{{route('admin.category-assesment.create')}}" class="btn btn-sm btn-primary mb-3">Tambah Kategori</a>
                    @endcan
                </div>
                <!-- Komitmen Management -->
                <div class="accordion" id="formAccordion">

                <!-- Section A: Komitmen Management -->
                    <div class="accordion-item">
                        @foreach ($categories as $category)
                            
                        <h2 class="accordion-header bg-light" id="heading{{$category->id}}">
                            <div
                                class="d-flex justify-content-between align-items-center w-100"
                                style="padding: 10px 15px; border: none;"
                            >
                                <!-- Area interaktif untuk accordion -->
                                <div
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#category_{{$category->id}}"
                                    aria-expanded="false"
                                    aria-controls="collapseA"
                                    style="flex: 1; border: none; background-color: transparent;"
                                >
                                    <span class="fw-bold" style="white-space: normal; word-break: break-word;">
                                        {{ $category->name }}
                                    </span>

                                </div>
                        
                                <!-- Tombol yang bisa diklik -->
                                <div class="flex gap-2" style="align-items: center;display: flex;">
                                    @can('edit.category.assesment')
                                    <a href="{{route('admin.category-assesment.edit',['category_assesment' => $category->id])}}" class="btn btn-warning btn-sm mt-1">Edit Kategori</a>
                                    @endcan
                                    @can('delete.category.assesment')
                                    <form
                                            id="delete-category-assesment-{{ $category->id }}"
                                            action="{{route('admin.category-assesment.destroy',['category_assesment'=>$category->id])}}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-category-assesment-{{ $category->id }}',
                                                    'Kategori akan dihapus.'
                                                )"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </h2>
                        @can('view.question.assesment')
                        <div id="category_{{$category->id}}" class="accordion-collapse collapse" aria-labelledby="category{{$category->id}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                @can('create.question.assesment')
                                <a href="{{route('admin.question-assesment.create',['category_assesment'=>$category->id])}}" class="btn btn-success btn-sm mb-3">Tambah Question</a>
                                @endcan
                                <table class="table table-bordered text-center">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Indikator</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($category->questions as $question)
                                            
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-start"  style="white-space: normal; word-break: break-word;">{{$question->indicator}}</td>
                                                @canany(['view.level.assesment', 'create.level.assesment', 'edit.question.assesment', 'delete.question.assesment'])                                                    
                                                <td class="">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        @can('view.level.assesment')
                                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#accordionRow{{$question->id}}" aria-expanded="false" aria-controls="accordionRow{{$question->id}}">
                                                            Lihat Detail Level
                                                        </button>
                                                        @endcan
                                                        @can('create.level.assesment')
                                                        <a href="{{route('admin.level-assesment.create',['question_assesment'=> $question->id])}}" class="btn btn-success btn-sm">Tambah Level</a>
                                                        @endcan
                                                        @can('edit.question.assesment')
                                                        <a href="{{route('admin.question-assesment.edit',['question_assesment'=> $question->id,'category_assesment' => $category->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                        @endcan
                                                        @can('delete.question.assesment')
                                                        <form
                                                            id="delete-question-assesment-{{ $question->id }}"
                                                            action="{{route('admin.question-assesment.destroy',['question_assesment'=>$question->id,'category_assesment' => $category->id])}}"
                                                            method="POST"
                                                            class="d-inline"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete(
                                                                    'delete-question-assesment-{{ $question->id }}',
                                                                    'Indikator akan dihapus.'
                                                                )"
                                                            >
                                                                Hapus
                                                            </button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                                @endcanany
                                            </tr>
                                            @can('view.level.assesment')
                                            <tr id="accordionRow{{$question->id}}" class="collapse accordion-content">
                                                <td colspan="6">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center align-middle">No</th>
                                                                <th class="text-center align-middle">Level</th>
                                                                <th class="text-center align-middle">Deskripsi Level</th>
                                                                @canany(['edit.level.assesment', 'delete.level.assesment'])
                                                                <th class="text-center align-middle">Action</th>
                                                                @endcanany
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($question->levels as $level)   
                                                                <tr>
                                                                    <td>{{$loop->iteration}}</td>
                                                                    <td>Level {{$level->level}}</td>
                                                                    <td class="text-start">{{$level->level_description}}</td>
                                                                    @canany(['edit.level.assesment', 'delete.level.assesment'])
                                                                    <td class="">
                                                                        <div class="d-flex justify-content-end gap-2">
                                                                            @can('edit.level.assesment')
                                                                            <a href="{{route('admin.level-assesment.edit',['level_assesment'=> $level->id,'question_assesment' => $question->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                            @endcan
                                                                            @can('delete.level.assesment')
                                                                            <form
                                                                                id="delete-level-assesment-{{ $level->id }}"
                                                                                action="{{route('admin.level-assesment.destroy',['level_assesment'=>$level->id,'question_assesment' => $question->id])}}"
                                                                                method="POST"
                                                                                class="d-inline"
                                                                            >
                                                                                @csrf
                                                                                @method('DELETE')

                                                                                <button
                                                                                    type="button"
                                                                                    class="btn btn-danger btn-sm"
                                                                                    onclick="confirmDelete(
                                                                                        'delete-level-assesment-{{ $level->id }}',
                                                                                        'Level akan dihapus.'
                                                                                    )"
                                                                                >
                                                                                    Hapus
                                                                                </button>
                                                                            </form>
                                                                            @endcan
                                                                        </div>
                                                                    </td>
                                                                    @endcanany
                                                                </tr>
                                                            @endforeach
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

