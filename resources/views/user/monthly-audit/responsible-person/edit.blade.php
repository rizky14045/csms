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
        <h4 class="fs-18 fw-semibold m-0">Penanggung Jawab Keamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data Penanggung Jawab Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.monthly-audit.responsible-person.update',['monthlyId'=>$monthlyId,'personId'=>$person->id])}}" class="my-4" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nama" name="name" value="{{$person->name}}">
                            @if($errors->has('name'))
                                <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Jabatan</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan jabatan" name="position" value="{{$person->position}}">
                            @if($errors->has('position'))
                                <div class="error text-danger">{{ $errors->first('position') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Unit Kerja</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan unit kerja" name="work_unit" value="{{$person->work_unit}}">
                            @if($errors->has('work_unit'))
                                <div class="error text-danger">{{ $errors->first('work_unit') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pelatihan SMP</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan pelatihan smp" name="training_smp" value="{{$person->training_smp}}">
                            @if($errors->has('training_smp'))
                                <div class="error text-danger">{{ $errors->first('training_smp') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Auditor SMP</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan auditor smp" name="auditor_smp" value="{{$person->auditor_smp}}">
                            @if($errors->has('auditor_smp'))
                                <div class="error text-danger">{{ $errors->first('auditor_smp') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Utama</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan utama" name="main" value="{{$person->main}}">
                            @if($errors->has('main'))
                                <div class="error text-danger">{{ $errors->first('main') }}</div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Investigasi</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan investigasi" name="investigation" value="{{$person->investigation}}">
                            @if($errors->has('investigation'))
                                <div class="error text-danger">{{ $errors->first('investigation') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Mansrisk</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan mansrisk" name="mansrisk" value="{{$person->mansrisk}}">
                            @if($errors->has('mansrisk'))
                                <div class="error text-danger">{{ $errors->first('mansrisk') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Stakeholder Management</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan stakeholder management" name="stackholder_management" value="{{$person->stackholder_management}}">
                            @if($errors->has('stackholder_management'))
                                <div class="error text-danger">{{ $errors->first('stackholder_management') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pendidikan Terakhir</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan pendidikan terakhir" name="last_education" value="{{$person->last_education}}">
                            @if($errors->has('last_education'))
                                <div class="error text-danger">{{ $errors->first('last_education') }}</div>
                            @endif
                        </div>    
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Note</label>
                            <input class="form-control" type="text" id="emailaddress" placeholder="Masukan note" name="note" value="{{$person->note}}">
                            @if($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                      
                       
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.monthly-audit.worker-sum.index',['monthlyId'=>$monthlyId])}}" class="btn btn-danger"> Back</a>
                                    <button class="btn btn-primary" type="submit"> Ubah</button>
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

