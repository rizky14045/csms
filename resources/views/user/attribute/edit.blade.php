@extends('user.layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Atribut</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Data Atribut</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.attribute.update',['id'=>$attribute->id])}}" class="my-4" method="POST">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nama" name="name" value="{{$attribute->name}}">
                            @if($errors->has('name'))
                                <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Status Kepemilikan</label>
                            <select class="form-select" aria-label="Default select example" name="status_ownership" required>
                                <option value="">Pilih Status Kepemilikan</option>
                                <option value="BUJP" {{$attribute->status_ownership == 'BUJP' ? 'selected' : ''}}>BUJP</option>
                                <option value="PNP" {{$attribute->status_ownership == 'PNP' ? 'selected' : ''}}>PNP</option>
                              </select> 
                            @if($errors->has('status_ownership'))
                                <div class="error text-danger">{{ $errors->first('status_ownership') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Satuan</label>
                            <select class="form-select" aria-label="Default select example" name="unit" required>
                                <option value="">Pilih Satuan</option>
                                <option value="Unit" {{$attribute->unit == 'Unit' ? 'selected' : ''}}>Unit</option>
                                <option value="Lembar" {{$attribute->unit == 'Lembar' ? 'selected' : ''}}>Lembar</option>
                                <option value="Jumlah" {{$attribute->unit == 'Jumlah' ? 'selected' : ''}}>Jumlah</option>
                                <option value="Orang" {{$attribute->unit == 'Orang' ? 'selected' : ''}} >Orang</option>
                                <option value="Titik"  {{$attribute->unit == 'Titik' ? 'selected' : ''}}>Titik</option>
                                <option value="Meter"{{$attribute->unit == 'Meter' ? 'selected' : ''}}>Meter</option>
                              </select> 
                            @if($errors->has('unit'))
                                <div class="error text-danger">{{ $errors->first('unit') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Jumlah Standar Kontrak</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan jumlah standar kontrak" name="standard_contract" value="{{$attribute->standard_contract}}">
                            @if($errors->has('standard_contract'))
                                <div class="error text-danger">{{ $errors->first('standard_contract') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tipe Atribut</label>
                            <select class="form-select" aria-label="Default select example" name="type_attribute" required>
                                <option value="">Pilih Status Kepemilikan</option>
                                <option value="Attribute" {{$attribute->type_attribute == 'Attribute' ? 'selected' : ''}}>Attribute</option>
                                <option value="Sarana" {{$attribute->type_attribute == 'Sarana' ? 'selected' : ''}}>Sarana</option>
                              </select> 
                            @if($errors->has('type_attribute'))
                                <div class="error text-danger">{{ $errors->first('type_attribute') }}</div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('user.attribute.index')}}" class="btn btn-danger"> Back</a>
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

