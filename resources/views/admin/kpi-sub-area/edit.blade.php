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
        <h4 class="fs-18 fw-semibold m-0">KPI Area</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data KPI Area</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.kpi-sub-area.update',['sub_area'=> $subArea->id ,'area'=>$area->id])}}" class="my-4" method="POST" id="form-kpi-subarea" onsubmit="confirmSave('form-kpi-subarea', 'Data subarea akan disimpan')">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Nama</label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="username" required="" placeholder="Masukan nama" value="{{ old('name',$subArea->name) }}">
                            @error('name')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Uraian</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3" name="description">{{ old('name',$subArea->description) }}</textarea>
                            @error('description')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="reference" class="form-label">Referensi</label>
                            <input class="form-control @error('reference') is-invalid @enderror" name="reference" type="text" id="reference" required="" placeholder="Masukan referensi" value="{{ old('name',$subArea->reference) }}">
                            @error('reference')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('admin.kpi-area.index')}}" class="btn btn-danger"> Back</a>
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

