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
        <h4 class="fs-18 fw-semibold m-0">Program Keamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Data Program Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.main-security-program.update',['programId'=>$programId,'id'=> $main->id])}}" class="my-4" method="POST">
                    @csrf
                    @method('PATCH')
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama Program</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nama program" name="program_name" value="{{$main->program_name}}">
                            @if($errors->has('program_name'))
                                <div class="error text-danger">{{ $errors->first('program_name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Mulai Bulan</label>
                            <select class="form-select" aria-label="Default select example" name="start_month" required>
                                <option value="">Pilih Bulan</option>
                                <option value="Januari" {{$main->start_month == 'Januari' ? 'selected' : ''}}>Januari</option>
                                <option value="Februari" {{$main->start_month == 'Februari' ? 'selected' : ''}}>Febuari</option>
                                <option value="Maret" {{$main->start_month == 'Maret' ? 'selected' : ''}}>Maret</option>
                                <option value="April" {{$main->start_month == 'April' ? 'selected' : ''}}>April</option>
                                <option value="Mei" {{$main->start_month == 'Mei' ? 'selected' : ''}}>Mei</option>
                                <option value="Juni" {{$main->start_month == 'Juni' ? 'selected' : ''}}>Juni</option>
                                <option value="Juli" {{$main->start_month == 'Juli' ? 'selected' : ''}}>Juli</option>
                                <option value="Agustus" {{$main->start_month == 'Agustus' ? 'selected' : ''}}>Agustus</option>
                                <option value="September" {{$main->start_month == 'September' ? 'selected' : ''}}>September</option>
                                <option value="Oktober" {{$main->start_month == 'Oktober' ? 'selected' : ''}}>Oktober</option>
                                <option value="November" {{$main->start_month == 'November' ? 'selected' : ''}}>November</option>
                                <option value="Desember" {{$main->start_month == 'Desember' ? 'selected' : ''}}>Desember</option>
                            </select> 
                            @if($errors->has('start_month'))
                                <div class="error text-danger">{{ $errors->first('start_month') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Mulai Minggu</label>
                            <select class="form-select" aria-label="Default select example" name="start_week" required>
                                <option value="">Pilih Minggu</option>
                                <option value="1" {{$main->start_week == '1' ? 'selected' : ''}}>Minggu 1</option>
                                <option value="2" {{$main->start_week == '2' ? 'selected' : ''}}>Minggu 2</option>
                                <option value="3" {{$main->start_week == '3' ? 'selected' : ''}}>Minggu 3</option>
                                <option value="4" {{$main->start_week == '4' ? 'selected' : ''}}>Minggu 4</option>
                            </select> 
                            @if($errors->has('start_month'))
                                <div class="error text-danger">{{ $errors->first('start_month') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Selesai Bulan</label>
                            <select class="form-select" aria-label="Default select example" name="end_month" required>
                                <option value="">Pilih Bulan</option>
                                <option value="Januari" {{$main->end_month == 'Januari' ? 'selected' : ''}}>Januari</option>
                                <option value="Februari" {{$main->end_month == 'Februari' ? 'selected' : ''}}>Febuari</option>
                                <option value="Maret" {{$main->end_month == 'Maret' ? 'selected' : ''}}>Maret</option>
                                <option value="April" {{$main->end_month == 'April' ? 'selected' : ''}}>April</option>
                                <option value="Mei" {{$main->end_month == 'Mei' ? 'selected' : ''}}>Mei</option>
                                <option value="Juni" {{$main->end_month == 'Juni' ? 'selected' : ''}}>Juni</option>
                                <option value="Juli" {{$main->end_month == 'Juli' ? 'selected' : ''}}>Juli</option>
                                <option value="Agustus" {{$main->end_month == 'Agustus' ? 'selected' : ''}}>Agustus</option>
                                <option value="September" {{$main->end_month == 'September' ? 'selected' : ''}}>September</option>
                                <option value="Oktober" {{$main->end_month == 'Oktober' ? 'selected' : ''}}>Oktober</option>
                                <option value="November" {{$main->end_month == 'November' ? 'selected' : ''}}>November</option>
                                <option value="Desember" {{$main->end_month == 'Desember' ? 'selected' : ''}}>Desember</option>
                            </select> 
                            @if($errors->has('end_month'))
                                <div class="error text-danger">{{ $errors->first('end_month') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Selesai Minggu</label>
                            <select class="form-select" aria-label="Default select example" name="end_week" required>
                                <option value="">Pilih Minggu</option>
                                <option value="1" {{$main->end_week == '1' ? 'selected' : ''}}>Minggu 1</option>
                                <option value="2" {{$main->end_week == '2' ? 'selected' : ''}}>Minggu 2</option>
                                <option value="3" {{$main->end_week == '3' ? 'selected' : ''}}>Minggu 3</option>
                                <option value="4" {{$main->end_week == '4' ? 'selected' : ''}}>Minggu 4</option>
                            </select> 
                            @if($errors->has('end_month'))
                                <div class="error text-danger">{{ $errors->first('end_month') }}</div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('user.main-security-program.index',['programId'=>$programId])}}" class="btn btn-danger"> Back</a>
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

