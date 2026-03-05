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
            <li class="breadcrumb-item active">Ubah Data Penanggung Jawab Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.responsible-person.update',['person'=>$person->id])}}" class="my-4" method="POST" enctype="multipart/form-data" id="form-responsible" onsubmit="confirmSave('form-responsible', 'Data penanggung jawab keamanan akan disimpan')">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="name" required="" placeholder="Masukan nama" name="name" value="{{ old('name', $person->name) }}">
                            @error('name')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="position" class="form-label">Jabatan</label>
                            <input class="form-control" type="text" id="position" required="" placeholder="Masukan jabatan" name="position" value="{{ old('position', $person->position) }}">
                            @error('position')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="work_unit" class="form-label">Unit Kerja</label>
                            <input class="form-control" type="text" id="work_unit" required="" placeholder="Masukan unit kerja" name="work_unit" value="{{ old('work_unit', $person->work_unit) }}">
                            @error('work_unit')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="training_smp" class="form-label">Pelatihan SMP</label>
                            <input class="form-control" type="text" id="training_smp" placeholder="Masukan pelatihan smp" name="training_smp" value="{{ old('training_smp', $person->training_smp) }}">
                            @error('training_smp')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="auditor_smp" class="form-label">Auditor SMP</label>
                            <input class="form-control" type="text" id="auditor_smp" placeholder="Masukan auditor smp" name="auditor_smp" value="{{ old('auditor_smp', $person->auditor_smp) }}">
                            @error('auditor_smp')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="main" class="form-label">Utama</label>
                            <input class="form-control" type="text" id="main" placeholder="Masukan utama" name="main" value="{{ old('main', $person->main) }}">
                            @error('main')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="investigation" class="form-label">Investigasi</label>
                            <input class="form-control" type="text" id="investigation" placeholder="Masukan investigasi" name="investigation" value="{{ old('investigation', $person->investigation) }}">
                            @error('investigation')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="mansrisk" class="form-label">Mansrisk</label>
                            <input class="form-control" type="text" id="mansrisk" placeholder="Masukan mansrisk" name="mansrisk" value="{{ old('mansrisk', $person->mansrisk) }}">
                            @error('mansrisk')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="stackholder_management" class="form-label">Stakeholder Management</label>
                            <input class="form-control" type="text" id="stackholder_management" placeholder="Masukan stakeholder management" name="stackholder_management" value="{{ old('stackholder_management', $person->stackholder_management) }}">
                            @error('stackholder_management')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_education" class="form-label">Pendidikan Terakhir</label>
                            <input class="form-control" type="text" id="last_education" placeholder="Masukan pendidikan terakhir" name="last_education" value="{{ old('last_education', $person->last_education) }}">
                            @error('last_education')
                                <div class="error text-danger">{{ $error }}</div>
                            @enderror
                        </div>    
                        <div class="form-group mb-3">
                            <label for="note" class="form-label">Note</label>
                            <input class="form-control" type="text" id="note" placeholder="Masukan note" name="note" value="{{ old('note', $person->note) }}">
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

