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
            <li class="breadcrumb-item active">Tambah Data Program Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.main-security-program.store',['programId'=>$programId])}}" class="my-4" method="POST">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama Program</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nama program" name="program_name" value="{{old('program_name')}}">
                            @if($errors->has('program_name'))
                                <div class="error text-danger">{{ $errors->first('program_name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Mulai Bulan</label>
                            <select class="form-select" aria-label="Default select example" name="start_month" required>
                                <option value="">Pilih Bulan</option>
                                <option value="Januari" {{old('start_month') == 'Januari' ? 'selected' : ''}}>Januari</option>
                                <option value="Februari" {{old('start_month') == 'Februari' ? 'selected' : ''}}>Febuari</option>
                                <option value="Maret" {{old('start_month') == 'Maret' ? 'selected' : ''}}>Maret</option>
                                <option value="April" {{old('start_month') == 'April' ? 'selected' : ''}}>April</option>
                                <option value="Mei" {{old('start_month') == 'Mei' ? 'selected' : ''}}>Mei</option>
                                <option value="Juni" {{old('start_month') == 'Juni' ? 'selected' : ''}}>Juni</option>
                                <option value="Juli" {{old('start_month') == 'Juli' ? 'selected' : ''}}>Juli</option>
                                <option value="Agustus" {{old('start_month') == 'Agustus' ? 'selected' : ''}}>Agustus</option>
                                <option value="September" {{old('start_month') == 'September' ? 'selected' : ''}}>September</option>
                                <option value="Oktober" {{old('start_month') == 'Oktober' ? 'selected' : ''}}>Oktober</option>
                                <option value="November" {{old('start_month') == 'November' ? 'selected' : ''}}>November</option>
                                <option value="Desember" {{old('start_month') == 'Desember' ? 'selected' : ''}}>Desember</option>
                            </select> 
                            @if($errors->has('start_month'))
                                <div class="error text-danger">{{ $errors->first('start_month') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Mulai Minggu</label>
                            <select class="form-select" aria-label="Default select example" name="start_week" required>
                                <option value="">Pilih Minggu</option>
                                <option value="1" {{old('start_week') == '1' ? 'selected' : ''}}>Minggu 1</option>
                                <option value="2" {{old('start_week') == '2' ? 'selected' : ''}}>Minggu 2</option>
                                <option value="3" {{old('start_week') == '3' ? 'selected' : ''}}>Minggu 3</option>
                                <option value="4" {{old('start_week') == '4' ? 'selected' : ''}}>Minggu 4</option>
                            </select> 
                            @if($errors->has('start_week'))
                                <div class="error text-danger">{{ $errors->first('start_week') }}</div>
                            @endif
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Selesai Bulan</label>
                            <select class="form-select" aria-label="Default select example" name="end_month" required>
                                <option value="">Pilih Bulan</option>
                                <option value="Januari" {{old('end_month') == 'Januari' ? 'selected' : ''}}>Januari</option>
                                <option value="Februari" {{old('end_month') == 'Februari' ? 'selected' : ''}}>Febuari</option>
                                <option value="Maret" {{old('end_month') == 'Maret' ? 'selected' : ''}}>Maret</option>
                                <option value="April" {{old('end_month') == 'April' ? 'selected' : ''}}>April</option>
                                <option value="Mei" {{old('end_month') == 'Mei' ? 'selected' : ''}}>Mei</option>
                                <option value="Juni" {{old('end_month') == 'Juni' ? 'selected' : ''}}>Juni</option>
                                <option value="Juli" {{old('end_month') == 'Juli' ? 'selected' : ''}}>Juli</option>
                                <option value="Agustus" {{old('end_month') == 'Agustus' ? 'selected' : ''}}>Agustus</option>
                                <option value="September" {{old('end_month') == 'September' ? 'selected' : ''}}>September</option>
                                <option value="Oktober" {{old('end_month') == 'Oktober' ? 'selected' : ''}}>Oktober</option>
                                <option value="November" {{old('end_month') == 'November' ? 'selected' : ''}}>November</option>
                                <option value="Desember" {{old('end_month') == 'Desember' ? 'selected' : ''}}>Desember</option>
                            </select> 
                            @if($errors->has('end_month'))
                                <div class="error text-danger">{{ $errors->first('end_month') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Pilih Planning Selesai Minggu</label>
                            <select class="form-select" aria-label="Default select example" name="end_week" required>
                                <option value="">Pilih Minggu</option>
                                <option value="1" {{old('end_week') == '1' ? 'selected' : ''}}>Minggu 1</option>
                                <option value="2" {{old('end_week') == '2' ? 'selected' : ''}}>Minggu 2</option>
                                <option value="3" {{old('end_week') == '3' ? 'selected' : ''}}>Minggu 3</option>
                                <option value="4" {{old('end_week') == '4' ? 'selected' : ''}}>Minggu 4</option>
                            </select> 
                            @if($errors->has('end_week'))
                                <div class="error text-danger">{{ $errors->first('end_week') }}</div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('user.main-security-program.index',['programId'=>$programId])}}" class="btn btn-danger"> Back</a>
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

