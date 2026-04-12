@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
    <style>
        #map { height: 500px; width: 100%; }
    </style>
</style>
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Edit Securepedia</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Data Securepedia</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.securepedia.update',['securepedia'=>$securepedia->id])}}" enctype="multipart/form-data" class="my-4" method="POST" id="form-securepedia" onsubmit="confirmSave('form-securepedia', 'Data securepedia akan disimpan')">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">JUDUL</label>
                            <input class="form-control" type="text" id="title" required="" placeholder="Masukan judul" name="title" value="{{old('title', $securepedia->title)}}">
                            @error('title')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Status Kepemilikan</label>
                            <select class="form-select" aria-label="Default select example" name="type" required>
                                <option value="">Pilih Status Kepemilikan</option>
                                <option value="Internal" {{old('type', $securepedia->type) == 'Internal' ? 'selected' : ''}}>Internal</option>
                                <option value="External" {{old('type', $securepedia->type) == 'External' ? 'selected' : ''}}>External</option>
                              </select> 
                            @error('type')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="file" class="form-label">File Pendukung</label>
                            <input class="form-control" type="file" id="file" accept="pdf" placeholder="Masukan judul" name="file" value="{{old('file', $securepedia->file)}}">
                            @error('file')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('admin.securepedia.index')}}" class="btn btn-danger"> Kembali</a>
                                    <button class="btn btn-primary" type="submit"> Edit</button>
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
@section('scripts')

@endsection

