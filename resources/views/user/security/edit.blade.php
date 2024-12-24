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
        <h4 class="fs-18 fw-semibold m-0">Satuan Pengamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Data Satuan Pengamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.security.update',['id'=>$security->id])}}" class="my-4" method="POST">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama</label>
                            <input class="form-control" type="text" required="" placeholder="Masukan nama" name="name" value="{{$security->name}}">
                            @if($errors->has('name'))
                                <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" aria-label="Default select example" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Pratama" {{$security->gender == 'Pria' ? 'selected' : ''}}>Pria</option>
                                <option value="Madya" {{$security->gender == 'Wanita' ? 'selected' : ''}}>Wanita</option>
                              </select> 
                            @if($errors->has('gender'))
                                <div class="error text-danger">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Unit Kerja</label>
                            <input class="form-control" type="text" required="" placeholder="Masukan unit kerja" name="unit_work" value="{{$security->unit_work}}">
                            @if($errors->has('unit_work'))
                                <div class="error text-danger">{{ $errors->first('unit_work') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">NID</label>
                            <input class="form-control" type="text" required="" placeholder="Masukan nid" name="nid" value="{{$security->nid}}">
                            @if($errors->has('nid'))
                                <div class="error text-danger">{{ $errors->first('nid') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nomor REG KTA</label>
                            <input class="form-control" type="text" required="" placeholder="Masukan nomor reg kta" name="registration_number" value="{{$security->registration_number}}">
                            @if($errors->has('registration_number'))
                                <div class="error text-danger">{{ $errors->first('registration_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Expired KTA</label>
                            <input class="form-control" type="date" required="" name="expired_card_date" value="{{$security->expired_card_date}}">
                            @if($errors->has('expired_card_date'))
                                <div class="error text-danger">{{ $errors->first('expired_card_date') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Jabatan</label>
                            <select class="form-select" aria-label="Default select example" name="position" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="Komandan" {{$security->position == 'Komandan' ? 'selected' : ''}}>Komandan</option>
                                <option value="Anggota" {{$security->position == 'Anggota' ? 'selected' : ''}}>Anggota</option>
                                <option value="Chief" {{$security->position == 'Chief' ? 'selected' : ''}}>Chief</option>
                              </select> 
                            @if($errors->has('position'))
                                <div class="error text-danger">{{ $errors->first('position') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tempat Lahir</label>
                            <input class="form-control" type="text" required="" placeholder="Masukan tempat lahir" name="birth_place" value="{{$security->birth_place}}">
                            @if($errors->has('birth_place'))
                                <div class="error text-danger">{{ $errors->first('birth_place') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tanggal Lahir</label>
                            <input class="form-control" type="date" required="" name="birth_date" value="{{$security->birth_date}}">
                            @if($errors->has('birth_date'))
                                <div class="error text-danger">{{ $errors->first('birth_date') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Kualifikasi</label>
                            <select class="form-select" aria-label="Default select example" name="qualification" required>
                                <option value="">Pilih Kualifikasi</option>
                                <option value="Pratama" {{$security->qualification == 'Pratama' ? 'selected' : ''}}>Pratama</option>
                                <option value="Madya" {{$security->qualification == 'Madya' ? 'selected' : ''}}>Madya</option>
                                <option value="Utama" {{$security->qualification == 'Utama' ? 'selected' : ''}}>Utama</option>
                              </select> 
                            @if($errors->has('qualification'))
                                <div class="error text-danger">{{ $errors->first('qualification') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pendidikan Terakhir</label>
                            <input class="form-control" type="text" required="" placeholder="Masukan pendidikan terakhir" name="last_education" value="{{$security->last_education}}">
                            @if($errors->has('last_education'))
                                <div class="error text-danger">{{ $errors->first('last_education') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Note</label>
                            <input class="form-control" type="text" required="" placeholder="Masukan note" name="note" value="{{$security->note}}">
                            @if($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('user.security.index')}}" class="btn btn-danger"> Back</a>
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

