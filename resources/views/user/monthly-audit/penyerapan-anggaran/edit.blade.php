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
        <h4 class="fs-18 fw-semibold m-0">Penyerapan Anggaran</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Penyerapan Anggaran</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.monthly-audit.penyerapan-anggaran.update',['monthlyId'=>$monthlyId,'anggaranId' => $anggaran->id])}}" class="my-4" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Jenis Anggaran</label>
                            <select name="type" id="jenis_anggaran" class="form-select">
                                <option value="pemeliharaan" {{$anggaran->type == 'pemeliharaan' ? 'selected' : ''}}>Pemeliharaan</option>
                                <option value="administrasi" {{$anggaran->type == 'administrasi' ? 'selected' : ''}}>Administrasi</option>
                            </select>
                            @if($errors->has('type'))
                                <div class="error text-danger">{{ $errors->first('type') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="kode_aktifitas" class="form-label">Kode Aktifitas</label>
                            <input class="form-control" type="text" id="kode_aktifitas" required="" placeholder="Masukan Kode Aktifitas" name="kode_aktifitas" value="{{$anggaran->kode_aktifitas}}">
                            @if($errors->has('kode_aktifitas'))
                                <div class="error text-danger">{{ $errors->first('kode_aktifitas') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="kode_prk" class="form-label">Kode PRK</label>
                            <input class="form-control" type="text" id="kode_prk" required="" placeholder="Masukan Kode PRK" name="kode_prk" value="{{$anggaran->kode_prk}}">
                            @if($errors->has('kode_prk'))
                                <div class="error text-danger">{{ $errors->first('kode_prk') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="deskripsi_kegiatan" class="form-label">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi_kegiatan" id="" cols="30" rows="10" class="form-control">{{$anggaran->deskripsi_kegiatan}}</textarea>
                            @if($errors->has('deskripsi_kegiatan'))
                                <div class="error text-danger">{{ $errors->first('deskripsi_kegiatan') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="jumlah_anggaran" class="form-label">Jumlah Anggaran</label>
                            <input class="form-control" type="number" id="jumlah_anggaran" required="" placeholder="Masukan Jumlah Anggaran" name="jumlah_anggaran" value="{{$anggaran->jumlah_anggaran}}">
                            @if($errors->has('jumlah_anggaran'))
                                <div class="error text-danger">{{ $errors->first('jumlah_anggaran') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="penyerapan_anggaran" class="form-label">Penyerapan Anggaran</label>
                            <input class="form-control" type="number" id="penyerapan_anggaran" required="" placeholder="Masukan Penyerapan Anggaran" name="penyerapan_anggaran" value="{{$anggaran->penyerapan_anggaran}}">
                            @if($errors->has('penyerapan_anggaran'))
                                <div class="error text-danger">{{ $errors->first('penyerapan_anggaran') }}</div>
                            @endif
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Keterangan</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan keterangan" name="keterangan" value="{{$anggaran->keterangan}}">
                            @if($errors->has('keterangan'))
                                <div class="error text-danger">{{ $errors->first('keterangan') }}</div>
                            @endif
                        </div>
        
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId'=>$monthlyId])}}" class="btn btn-danger"> Back</a>
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

