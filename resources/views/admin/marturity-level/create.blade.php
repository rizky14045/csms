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
        <h4 class="fs-18 fw-semibold m-0">Marturity Level</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Marturity Level</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.marturity-level.store',['subAreaId'=>$subAreaId])}}" class="my-4" method="POST">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Level</label>
                            <input class="form-control" name="level" type="text" id="username" required="" placeholder="Masukan Level" value="{{old('level')}}">
                            @if($errors->has('level'))
                                <div class="error text-danger">{{ $errors->first('level') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Uraian</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{old('description')}}</textarea>
                            @if($errors->has('description'))
                                <div class="error text-danger">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('admin.marturity-area.index')}}" class="btn btn-danger"> Back</a>
                                    <button class="btn btn-primary" type="submit">Tambah</button>
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

