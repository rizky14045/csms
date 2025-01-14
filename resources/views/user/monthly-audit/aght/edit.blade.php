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
        <h4 class="fs-18 fw-semibold m-0">Data AGHT</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data Data AGHT</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.monthly-audit.aght.update',['monthlyId'=>$monthlyId,'aghtId'=>$aght->id])}}" class="my-4" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Uraian Kegiatan</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan uraian kegiatan" name="activity" value="{{$aght->activity}}">
                            @if($errors->has('activity'))
                                <div class="error text-danger">{{ $errors->first('activity') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tanggal Kejadian Perkara</label>
                            <input class="form-control" type="date" id="emailaddress" required="" name="incident_date" value="{{$aght->incident_date}}">
                            @if($errors->has('incident_date'))
                                <div class="error text-danger">{{ $errors->first('incident_date') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Waktu Kejadian Perkara</label>
                            <input class="form-control" type="time" id="emailaddress" required="" name="incident_time" value="{{$aght->incident_time}}">
                            @if($errors->has('incident_time'))
                                <div class="error text-danger">{{ $errors->first('incident_time') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tempat Kejadian Perkara</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan tempat kejadian perkara" name="incident_location" value="{{$aght->incident_location}}">
                            @if($errors->has('incident_location'))
                                <div class="error text-danger">{{ $errors->first('incident_location') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Kerugian Akibat Yang Ditimbulkan</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan kerugian akibat yang ditimbulkan" name="loss" value="{{$aght->loss}}">
                            @if($errors->has('loss'))
                                <div class="error text-danger">{{ $errors->first('loss') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Tindakan Pengamanan Setelah Kejadian</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan tindakan pengamanan setelah kejadian" name="after_incident" value="{{$aght->after_incident}}">
                            @if($errors->has('after_incident'))
                                <div class="error text-danger">{{ $errors->first('after_incident') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Aparat Yang Dihubungi</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan aparat yang dihubungi" name="officer_contacted" value="{{$aght->officer_contacted}}">
                            @if($errors->has('officer_contacted'))
                                <div class="error text-danger">{{ $errors->first('officer_contacted') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Note</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan note" name="note" value="{{$aght->note}}">
                            @if($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
        
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.monthly-audit.aght.index',['monthlyId'=>$monthlyId])}}" class="btn btn-danger"> Back</a>
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

