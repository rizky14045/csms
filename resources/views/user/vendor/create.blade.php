@extends('user.layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">BUJP / Vendor</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data BUJP / Vendor</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.vendor.store')}}" class="my-4" method="POST">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan nama" name="name" value="{{old('name')}}">
                            @if($errors->has('name'))
                                <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">NPWP</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan npwp" name="npwp" value="{{old('npwp')}}">
                            @if($errors->has('npwp'))
                                <div class="error text-danger">{{ $errors->first('npwp') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Email</label>
                            <input class="form-control" type="email" id="emailaddress" required="" placeholder="Masukan email" name="email" value="{{old('email')}}">
                            @if($errors->has('email'))
                                <div class="error text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Alamat</label>
                            <input class="form-control" type="text" id="emailaddress" required="" placeholder="Masukan alamat" name="address" value="{{old('address')}}">
                            @if($errors->has('address'))
                                <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.vendor.index')}}" class="btn btn-danger"> Back</a>
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

