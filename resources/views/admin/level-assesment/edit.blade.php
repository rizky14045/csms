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
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.level-assesment.update',['level_assesment'=>$level->id,'question_assesment'=>$question->id])}}" class="my-4" method="POST" id="form-level-assesment" onsubmit="confirmSave('form-level-assesment', 'Data level akan disimpan')">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select" aria-label="Default select example" name="level" required>
                                <option value="">Pilih Level</option>
                                <option value="1" {{old('level',$level->level) == '1' ? 'selected' : ''}}>Level 1</option>
                                <option value="2" {{old('level',$level->level) == '2' ? 'selected' : ''}}>Level 2</option>
                                <option value="3" {{old('level',$level->level) == '3' ? 'selected' : ''}}>Level 3</option>
                                <option value="4" {{old('level',$level->level) == '4' ? 'selected' : ''}}>Level 4</option>
                                <option value="5" {{old('level',$level->level) == '5' ? 'selected' : ''}}>Level 5</option>
                              </select> 
                            @error('level')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="level_descriptioin" class="form-label">Level Penilaian</label>
                            <input class="form-control" name="level_description" type="text" id="level_descriptioin" required="" placeholder="Masukan level penilaian" value="{{old('level_description',$level->level_description)}}">
                            @error('level_description')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('admin.category-assesment.index')}}" class="btn btn-danger"> Kembali</a>
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                    >
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

