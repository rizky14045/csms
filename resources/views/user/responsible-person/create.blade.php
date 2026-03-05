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
        <h4 class="fs-18 fw-semibold m-0">Penanggung Jawab Keamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Penanggung Jawab Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.responsible-person.store')}}" class="my-4" method="POST" enctype="multipart/form-data" id="form-responsible" onsubmit="confirmSave('form-responsible', 'Data penanggung jawab keamanan akan disimpan')">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="name" required="" placeholder="Masukan nama" name="name" value="{{old('name')}}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="position" class="form-label">Jabatan</label>
                            <input class="form-control" type="text" id="position" required="" placeholder="Masukan jabatan" name="position" value="{{old('position')}}">
                            @error('position')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="work_unit" class="form-label">Unit Kerja</label>
                            <input class="form-control" type="text" id="work_unit" required="" placeholder="Masukan unit kerja" name="work_unit" value="{{old('work_unit')}}">
                            @error('work_unit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="training_smp" class="form-label">Pelatihan SMP</label>
                            <input class="form-control" type="text" id="training_smp" placeholder="Masukan pelatihan smp" name="training_smp" value="{{old('training_smp')}}">
                            @error('training_smp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="auditor_smp" class="form-label">Auditor SMP</label>
                            <input class="form-control" type="text" id="auditor_smp" placeholder="Masukan auditor smp" name="auditor_smp" value="{{old('auditor_smp')}}">
                            @error('auditor_smp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="main" class="form-label">Utama</label>
                            <input class="form-control" type="text" id="main" placeholder="Masukan utama" name="main" value="{{old('main')}}">
                            @error('main')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="investigation" class="form-label">Investigasi</label>
                            <input class="form-control" type="text" id="investigation" placeholder="Masukan investigasi" name="investigation" value="{{old('investigation')}}">
                            @error('investigation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="mansrisk" class="form-label">Mansrisk</label>
                            <input class="form-control" type="text" id="mansrisk" placeholder="Masukan mansrisk" name="mansrisk" value="{{old('mansrisk')}}">
                            @error('mansrisk')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="stackholder_management" class="form-label">Stakeholder Management</label>
                            <input class="form-control" type="text" id="stackholder_management" placeholder="Masukan stakeholder management" name="stackholder_management" value="{{old('stackholder_management')}}">
                            @error('stackholder_management')
                                <div class="error text-danger">{{ $errors->first('stackholder_management') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_education" class="form-label">Pendidikan Terakhir</label>
                            <input class="form-control" type="text" id="last_education" placeholder="Masukan pendidikan terakhir" name="last_education" value="{{old('last_education')}}">
                            @error('last_education')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>    
                        <div class="form-group mb-3">
                            <label for="note" class="form-label">Note</label>
                            <input class="form-control" type="text" id="note" placeholder="Masukan note" name="note" value="{{old('note')}}">
                            @error('note')
                                <small class="text-danger">{{ $message }}</small>
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

