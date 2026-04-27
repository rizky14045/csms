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
        <h4 class="fs-18 fw-semibold m-0">Keamanan Eksternal</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data Keamanan Eksternal</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.security-external.update',['security' =>$security->id])}}" class="my-4" method="POST" enctype="multipart/form-data" id="form-security-external" onsubmit="confirmSave('form-security-external', 'Data keamanan eksternal akan disimpan')">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="name" required="" placeholder="Masukan nama" name="name" value="{{ old('name', $security->name) }}">
                            @error('name')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" aria-label="Default select example" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Pria" {{old('gender', $security->gender) == 'Pria' ? 'selected' : ''}}>Pria</option>
                                <option value="Perempuan" {{old('gender', $security->gender) == 'Perempuan' ? 'selected' : ''}}>Perempuan</option>
                            </select>
                            @error('gender')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="instansi" class="form-label">Instansi</label>
                            <input class="form-control" type="text" id="instansi" required="" placeholder="Masukan instansi" name="instansi" value="{{ old('instansi', $security->instansi) }}">
                            @error('instansi')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="regional_unit" class="form-label">Satuan Wilayah</label>
                            <input class="form-control" type="text" id="regional_unit" required="" placeholder="Masukan satuan wilayah" name="regional_unit" value="{{ old('regional_unit', $security->regional_unit) }}">
                            @error('regional_unit')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="warrant_number" class="form-label">Nomor Surat Perintah</label>
                            <input class="form-control" type="text" id="warrant_number" required="" placeholder="Masukan nomor surat perintah" name="warrant_number" value="{{ old('warrant_number', $security->warrant_number) }}">
                            @error('warrant_number')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="note" class="form-label">Keterangan</label>
                            <select class="form-select" aria-label="Default select example" name="note" required>
                                <option value="">Pilih Keterangan</option>
                                <option value="Polri" {{$security->note == 'Polri' ? 'selected' : ''}}>Polri</option>
                                <option value="TNI" {{$security->note == 'TNI' ? 'selected' : ''}}>TNI</option>
                              </select> 
                            @error('note')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.worker-sum.index')}}" class="btn btn-danger"> Kembali</a>
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

