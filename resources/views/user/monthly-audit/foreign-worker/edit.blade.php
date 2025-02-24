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
        <h4 class="fs-18 fw-semibold m-0">Tenaga Kerja Asing</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data Tenaga Kerja Asing</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.monthly-audit.form-foreign-worker.update',['monthlyId'=>$monthlyId,'foreignId' => $foreign->id])}}" class="my-4" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nama" name="name" value="{{$foreign->name}}">
                            @if($errors->has('name'))
                                <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Kebangsaan</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan kebangsaan" name="nationality" value="{{$foreign->nationality}}">
                            @if($errors->has('nationality'))
                                <div class="error text-danger">{{ $errors->first('nationality') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Perusahaan</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan perusahaan" name="company" value="{{$foreign->company}}">
                            @if($errors->has('company'))
                                <div class="error text-danger">{{ $errors->first('company') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Jabatan</label>
                            <select class="form-select" aria-label="Default select example" name="position" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="Tenaga Ahli" {{$foreign->position == 'Tenaga Ahli' ? 'selected' : ''}}>Tenaga Ahli</option>
                                <option value="Staff" {{$foreign->position == 'Staff' ? 'selected' : ''}}>Staff</option>
                              </select> 
                            @if($errors->has('position'))
                                <div class="error text-danger">{{ $errors->first('position') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Kategori Tamu</label>
                            <select class="form-select" aria-label="Default select example" name="category" required>
                                <option value="">Pilih Kategori Tamu</option>
                                <option value="Tamu" {{$foreign->category == 'Tamu' ? 'selected' : ''}}>Tamu</option>
                                <option value="Pekerja" {{$foreign->category == 'Pekerja' ? 'selected' : ''}}>Pekerja</option>
                              </select> 
                            @if($errors->has('category'))
                                <div class="error text-danger">{{ $errors->first('category') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Paspor</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" name="paspor" required>
                                        <option value="">Ketersediaan Paspor</option>
                                        <option value="1" {{$foreign->paspor == '1' ? 'selected' : ''}}>Ada</option>
                                        <option value="0" {{$foreign->paspor == '0' ? 'selected' : ''}}>Tidak Ada</option>
                                      </select> 
                                    @if($errors->has('paspor'))
                                        <div class="error text-danger">{{ $errors->first('paspor') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="file" accept=".pdf,.jpg,.jpeg.png" name="paspor_file">
                                    <small>Ekstensi file pdf,png,jpg,jpeg <span class="text-danger">( wajib )</span></small>
                                    @if($errors->has('paspor_file'))
                                        <div class="error text-danger">{{ $errors->first('paspor_file') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Visa Kunjungan</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" name="visa">
                                        <option value="">Ketersediaan Visa Kunjungan</option>
                                        <option value="1" {{$foreign->visa == '1' ? 'selected' : ''}}>Ada</option>
                                        <option value="0" {{$foreign->visa == '0' ? 'selected' : ''}}>Tidak Ada</option>
                                      </select> 
                                    @if($errors->has('visa'))
                                        <div class="error text-danger">{{ $errors->first('visa') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="file" accept=".pdf,.jpg,.jpeg.png" name="visa_file">
                                    <small>Ekstensi file pdf,png,jpg,jpeg ( optional )</small>
                                    @if($errors->has('visa_file'))
                                        <div class="error text-danger">{{ $errors->first('visa_file') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">VITAS</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" name="vitas">
                                        <option value="">Ketersediaan VITAS</option>
                                        <option value="1" {{$foreign->vitas == '1' ? 'selected' : ''}}>Ada</option>
                                        <option value="0" {{$foreign->vitas == '0' ? 'selected' : ''}}>Tidak Ada</option>
                                      </select> 
                                    @if($errors->has('vitas'))
                                        <div class="error text-danger">{{ $errors->first('vitas') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="file" accept=".pdf,.jpg,.jpeg.png" name="vitas_file">
                                    <small>Ekstensi file pdf,png,jpg,jpeg ( optional )</small>
                                    @if($errors->has('vitas_file'))
                                        <div class="error text-danger">{{ $errors->first('vitas_file') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">KITAS</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" name="kitas">
                                        <option value="">Ketersediaan KITAS</option>
                                        <option value="1" {{$foreign->kitas == '1' ? 'selected' : ''}}>Ada</option>
                                        <option value="0" {{$foreign->kitas == '0' ? 'selected' : ''}}>Tidak Ada</option>
                                      </select> 
                                    @if($errors->has('kitas'))
                                        <div class="error text-danger">{{ $errors->first('kitas') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="file" accept=".pdf,.jpg,.jpeg.png" name="kitas_file">
                                    <small>Ekstensi file pdf,png,jpg,jpeg ( optional )</small>
                                    @if($errors->has('kitas_file'))
                                        <div class="error text-danger">{{ $errors->first('kitas_file') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">RPTKA</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" name="rptka">
                                        <option value="">Ketersediaan RPTKA</option>
                                        <option value="1" {{$foreign->rptka == '1' ? 'selected' : ''}}>Ada</option>
                                        <option value="0" {{$foreign->rptka == '0' ? 'selected' : ''}}>Tidak Ada</option>
                                      </select> 
                                    @if($errors->has('rptka'))
                                        <div class="error text-danger">{{ $errors->first('rptka') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="file" accept=".pdf,.jpg,.jpeg.png" name="rptka_file">
                                    <small>Ekstensi file pdf,png,jpg,jpeg ( optional )</small>
                                    @if($errors->has('rptka_file'))
                                        <div class="error text-danger">{{ $errors->first('rptka_file') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tanggal Datang</label>
                            <input class="form-control" type="date" required="" name="arrived_date" value="{{$foreign->arrived_date}}">
                            @if($errors->has('arrived_date'))
                                <div class="error text-danger">{{ $errors->first('arrived_date') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tanggal Kembali</label>
                            <input class="form-control" type="date" required="" name="return_date" value="{{$foreign->return_date}}">
                            @if($errors->has('return_date'))
                                <div class="error text-danger">{{ $errors->first('return_date') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Note</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan note" name="note" value="{{$foreign->note}}">
                            @if($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.monthly-audit.form-foreign-worker.index',['monthlyId'=>$monthlyId])}}" class="btn btn-danger"> Back</a>
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

