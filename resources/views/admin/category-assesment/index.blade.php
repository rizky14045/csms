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
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('admin.category-assesment.create')}}" class="btn btn-sm btn-success mb-3">Tambah Kategori</a>
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
                                    <span class="fw-bold">{{$category->name}}</span>
                                </div>
                        
                                <!-- Tombol yang bisa diklik -->
                                    
                                    <a href="{{route('admin.category-assesment.edit',['categoryId' => $category->id])}}" class="btn btn-warning btn-sm mt-1">Edit Kategori</a>
                                    <form action="{{route('admin.category-assesment.destroy',['categoryId'=>$category->id])}}" method="post" class="d-block ms-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                            </div>
                        </h2>
                        <div id="category_{{$category->id}}" class="accordion-collapse collapse" aria-labelledby="category{{$category->id}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <a href="{{route('admin.question-assesment.create',['categoryId'=>$category->id])}}" class="btn btn-success btn-sm mb-3">Tambah Question</a>
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
                                                <td class="text-nowrap text-start">{{$question->indicator}}</td>
                                                <td class="">
                                                    <div class="d-flex justify-content-end gap-2">

                                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#accordionRow{{$question->id}}" aria-expanded="false" aria-controls="accordionRow{{$question->id}}">
                                                            Lihat Detail Level
                                                        </button>
                                                        <a href="{{route('admin.level-assesment.create',['questionId'=> $question->id])}}" class="btn btn-success btn-sm">Tambah Level</a>
                                                        <a href="{{route('admin.question-assesment.edit',['questionId'=> $question->id,'categoryId' => $category->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                        <form action="{{route('admin.question-assesment.destroy',['questionId'=> $question->id,'categoryId' => $category->id])}}" method="post" class="d-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            <tr id="accordionRow{{$question->id}}" class="collapse accordion-content">
                                                <td colspan="6">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center align-middle">No</th>
                                                                <th class="text-center align-middle">Level</th>
                                                                <th class="text-center align-middle">Deskripsi Level</th>
                                                                <th class="text-center align-middle">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($question->levels as $level)   
                                                                <tr>
                                                                    <td>{{$loop->iteration}}</td>
                                                                    <td>Level {{$level->level}}</td>
                                                                    <td class="text-start">{{$level->level_description}}</td>
                                                                    <td class="">
                                                                        <div class="d-flex justify-content-end gap-2">

                                                                            <a href="{{route('admin.level-assesment.edit',['levelId'=> $level->id,'questionId' => $question->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                            <form action="{{route('admin.level-assesment.destroy',['levelId'=> $level->id,'questionId' => $question->id])}}" method="post" class="d-block">
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

