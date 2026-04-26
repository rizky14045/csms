@extends('layout.app')
@section('styles')
@stop
@section('content')


    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Tipe Fasilitas Umum</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tambah Data Tipe Fasilitas Umum</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.fasum-type.store') }}" class="my-4" method="POST" id="form-unit"
                        onsubmit="confirmSave('form-unit', 'Data tipe fasilitas umum akan disimpan')">
                        @csrf
                        <!-- Formulir Pendaftaran -->
                        <div class="col-xl-12">
                            <div class="d-flex gap-1">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input class="form-control" type="text" id="name" required=""
                                        placeholder="Masukan tipe fasilitas umum" name="name" value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                        <div class="error text-danger">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="color_code" class="form-label">Kode Warna</label>
                                        <input class="form-control" type="color" id="color_code" required=""
                                        placeholder="Masukan kode warna (contoh: #FF0000)" name="color_code"
                                        value="{{ old('color_code') }}">
                                        @if ($errors->has('color_code'))
                                        <div class="error text-danger">{{ $errors->first('color_code') }}</div>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end">

                                        <a href="{{ route('admin.fasum-type.index') }}" class="btn btn-danger"> Kembali</a>
                                        <button type="submit" class="btn btn-success">
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
