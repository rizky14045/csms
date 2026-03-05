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
        <h4 class="fs-18 fw-semibold m-0">Kerja Sama Eksternal</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data Kerja Sama Eksternal</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.agreement-external.update',['agreement'=>$agreement->id])}}" class="my-4" method="POST" enctype="multipart/form-data" id="form-agreement" onsubmit="confirmSave('form-agreement', 'Data perjanjian kerjasama eksternal akan disimpan')">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="regional_unit" class="form-label">Nama Satuan Wilayah</label>
                            <input class="form-control" type="text" id="regional_unit" required="" placeholder="Masukan nama satuan wilayah" name="regional_unit" value="{{ old('regional_unit', $agreement->regional_unit) }}">
                            @error('regional_unit')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="instansi" class="form-label">Instansi</label>
                            <input class="form-control" type="text" id="instansi" required="" placeholder="Masukan instansi" name="instansi" value="{{ old('instansi', $agreement->instansi) }}">
                            @error('instansi')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="name" required="" placeholder="Masukan nama" name="name" value="{{ old('name', $agreement->name) }}">
                            @error('name')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="pkt_number" class="form-label">Nomor PKT</label>
                            <input class="form-control" type="text" id="pkt_number" required="" placeholder="Masukan nomor pkt" name="pkt_number" value="{{ old('pkt_number', $agreement->pkt_number) }}">
                            @error('pkt_number')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="pkt_title" class="form-label">Judul PKT</label>
                            <input class="form-control" type="text" id="pkt_title" required="" placeholder="Masukan judul pkt" name="pkt_title" value="{{ old('pkt_title', $agreement->pkt_title) }}">
                            @error('pkt_title')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                      
                        <div class="form-group mb-3">
                            <label for="expired_date" class="form-label">Tanggal Masa Berlaku</label>
                            <input class="form-control" type="date" id="expired_date" required="" name="expired_date" value="{{ old('expired_date', $agreement->expired_date) }}">
                            @error('expired_date')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="note" class="form-label">Note</label>
                            <input class="form-control" type="text" id="note" required="" placeholder="Masukan note" name="note" value="{{ old('note', $agreement->note) }}">
                            @error('note')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="note" class="form-label">Note</label>
                            <input class="form-control" type="text" id="note" required="" placeholder="Masukan note" name="note" value="{{ old('note', $agreement->note) }}">
                            @error('note')
                                <div class="error text-danger">{{ $error }}</div>
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

