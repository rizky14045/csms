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
        <h4 class="fs-18 fw-semibold m-0">Satuan Pengamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Satuan Pengamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.security.store')}}" class="my-4" method="POST" id="form-security" onsubmit="confirmSave('form-security', 'Data satuan pengaman akan disimpan')">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label"><span class="text-danger">*</span> Nama</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" required="" id="name" placeholder="Masukan nama" name="name" value="{{old('name')}}">
                            @error('name')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="gender" class="form-label"><span class="text-danger">*</span> Jenis Kelamin</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" aria-label="Default select example" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Pria" {{old('gender') == 'Pria' ? 'selected' : ''}}>Pria</option>
                                <option value="Wanita" {{old('gender') == 'Wanita' ? 'selected' : ''}}>Wanita</option>
                            </select> 
                            @error('gender')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="unit_work" class="form-label"><span class="text-danger">*</span> Unit Kerja</label>
                            <input class="form-control @error('unit_work') is-invalid @enderror" id="unit_work" type="text" required="" placeholder="Masukan unit kerja" name="unit_work" value="{{old('unit_work')}}">
                            @error('unit_work')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nid" class="form-label"><span class="text-danger">*</span> NID</label>
                            <input class="form-control @error('nid') is-invalid @enderror" id="nid" type="text" required="" placeholder="Masukan nid" name="nid" value="{{old('nid')}}">
                            @error('nid')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="registration_number" class="form-label"><span class="text-danger">*</span> Nomor REG KTA</label>
                            <input class="form-control @error('registration_number') is-invalid @enderror" id="registration_number" type="text" required="" placeholder="Masukan nomor reg kta" name="registration_number" value="{{old('registration_number')}}">
                            @error('registration_number')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="expired_card_date" class="form-label"><span class="text-danger">*</span> Expired KTA</label>
                            <input class="form-control @error('expired_card_date') is-invalid @enderror" id="expired_card_date" type="date" required="" name="expired_card_date" value="{{old('expired_card_date')}}">
                            @error('expired_card_date')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="position" class="form-label"><span class="text-danger">*</span> Jabatan</label>
                            <select class="form-select @error('position') is-invalid @enderror" id="position" aria-label="Default select example" name="position" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="Komandan" {{old('position') == 'Komandan' ? 'selected' : ''}}>Komandan</option>
                                <option value="Anggota" {{old('position') == 'Anggota' ? 'selected' : ''}}>Anggota</option>
                                <option value="Chief" {{old('position') == 'Chief' ? 'selected' : ''}}>Chief</option>
                              </select> 
                            @error('position')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="birth_place" class="form-label"><span class="text-danger">*</span> Tempat Lahir</label>
                            <input class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" type="text" required="" placeholder="Masukan tempat lahir" name="birth_place" value="{{old('birth_place')}}">
                            @error('birth_place')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="birth_date" class="form-label"><span class="text-danger">*</span> Tanggal Lahir</label>
                            <input class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" type="date" required="" name="birth_date" value="{{old('birth_date')}}">
                            @error('birth_date')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="qualification" class="form-label"><span class="text-danger">*</span> Kualifikasi</label>
                            <select class="form-select @error('qualification') is-invalid @enderror" id="qualification" aria-label="Default select example" name="qualification" required>
                                <option value="">Pilih Kualifikasi</option>
                                <option value="Pratama" {{old('qualification') == 'Pratama' ? 'selected' : ''}}>Pratama</option>
                                <option value="Madya" {{old('qualification') == 'Madya' ? 'selected' : ''}}>Madya</option>
                                <option value="Utama" {{old('qualification') == 'Utama' ? 'selected' : ''}}>Utama</option>
                              </select> 
                            @error('qualification')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_education" class="form-label"><span class="text-danger">*</span> Pendidikan Terakhir</label>
                            <input class="form-control @error('last_education') is-invalid @enderror" id="last_education" type="text" required="" placeholder="Masukan pendidikan terakhir" name="last_education" value="{{old('last_education')}}">
                            @error('last_education')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="note" class="form-label">Note (opsional)</label>
                            <input class="form-control @error('note') is-invalid @enderror" id="note" type="text" placeholder="Masukan note" name="note" value="{{old('note')}}">
                            @error('note')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('user.security.index')}}" class="btn btn-danger"> Kembali</a>
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

