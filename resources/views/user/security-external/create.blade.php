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
        <h4 class="fs-18 fw-semibold m-0">Keamanan Eksternal</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Keamanan Eksternal</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.security-external.store')}}" class="my-4" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nama" name="name" value="{{old('name')}}">
                            @if($errors->has('name'))
                                <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Instansi</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan instansi" name="instansi" value="{{old('instansi')}}">
                            @if($errors->has('instansi'))
                                <div class="error text-danger">{{ $errors->first('instansi') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Satuan Wilayah</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan satuan wilayah" name="regional_unit" value="{{old('regional_unit')}}">
                            @if($errors->has('regional_unit'))
                                <div class="error text-danger">{{ $errors->first('regional_unit') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nomor Surat Perintah</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nomor surat perintah" name="warrant_number" value="{{old('warrant_number')}}">
                            @if($errors->has('warrant_number'))
                                <div class="error text-danger">{{ $errors->first('warrant_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Keterangan</label>
                            <select class="form-select" aria-label="Default select example" name="note" required>
                                <option value="">Pilih Keterangan</option>
                                <option value="Polri" {{old('note') == 'Polri' ? 'selected' : ''}}>Polri</option>
                                <option value="TNI" {{old('note') == 'TNI' ? 'selected' : ''}}>TNI</option>
                              </select> 
                            @if($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.worker-sum.index')}}" class="btn btn-danger"> Back</a>
                                    <button class="btn btn-primary" type="submit"> Tambah</button>
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

