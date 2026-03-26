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
        <h4 class="fs-18 fw-semibold m-0">Atribut</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Data Atribut</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.attribute.update',['attribute'=>$attribute->id])}}" class="my-4" method="POST" id="form-attribute-unit" onsubmit="confirmSave('form-attribute-unit', 'Data atribut akan disimpan')">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" required="" placeholder="Masukan nama" name="name" value="{{ old('name',$attribute->name) }}">
                            @error('name')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="status_ownership" class="form-label">Status Kepemilikan</label>
                            <select class="form-select @error('status_ownership') is-invalid @enderror" aria-label="Default select example" name="status_ownership" required>
                                <option value="">Pilih Status Kepemilikan</option>
                                <option value="BUJP" {{old('status_ownership',$attribute->status_ownership) == 'BUJP' ? 'selected' : ''}}>BUJP</option>
                                <option value="PNP" {{old('status_ownership',$attribute->status_ownership) == 'PNP' ? 'selected' : ''}}>PNP</option>
                              </select> 
                            @error('status_ownership')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="unit" class="form-label">Satuan</label>
                            <select class="form-select @error('unit') is-invalid @enderror" aria-label="Default select example" name="unit" required>
                                <option value="">Pilih Satuan</option>
                                <option value="Unit" {{old('unit',$attribute->unit) == 'Unit' ? 'selected' : ''}}>Unit</option>
                                <option value="Lembar" {{old('unit',$attribute->unit) == 'Lembar' ? 'selected' : ''}}>Lembar</option>
                                <option value="Jumlah" {{old('unit',$attribute->unit) == 'Jumlah' ? 'selected' : ''}}>Jumlah</option>
                                <option value="Orang" {{old('unit',$attribute->unit) == 'Orang' ? 'selected' : ''}} >Orang</option>
                                <option value="Titik"  {{old('unit',$attribute->unit) == 'Titik' ? 'selected' : ''}}>Titik</option>
                                <option value="Meter"{{old('unit',$attribute->unit) == 'Meter' ? 'selected' : ''}}>Meter</option>
                              </select> 
                            @error('unit')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="standard_contract" class="form-label">Jumlah Standar Kontrak</label>
                            <input class="form-control @error('standard_contract') is-invalid @enderror" type="text" id="standard_contract" required="" placeholder="Masukan jumlah standar kontrak" name="standard_contract" value="{{ old('standard_contract',$attribute->standard_contract) }}">
                            @error('standard_contract')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="type_attribute" class="form-label">Tipe Atribut</label>
                            <select class="form-select @error('type_attribute') is-invalid @enderror" aria-label="Default select example" name="type_attribute" required>
                                <option value="">Pilih Status Kepemilikan</option>
                                <option value="Attribute" {{old('type_attribute',$attribute->type_attribute) == 'Attribute' ? 'selected' : ''}}>Attribute</option>
                                <option value="Sarana" {{old('type_attribute',$attribute->type_attribute) == 'Sarana' ? 'selected' : ''}}>Sarana</option>
                                <option value="Administrasi" {{old('type_attribute',$attribute->type_attribute) == 'Administrasi' ? 'selected' : ''}}>Administrasi</option>
                              </select> 
                            @error('type_attribute')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('user.attribute.index')}}" class="btn btn-danger"> Kembali</a>
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
@section('scripts')

@endsection

