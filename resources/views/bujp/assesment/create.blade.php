@extends('bujp.layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('bujp.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{route('bujp.assesment.store')}}" class="my-4" method="POST">
                                    @csrf
                                    <div class="col-xl-12">
                                        <div class="form-group mb-3">
                                            <label for="date" class="form-label">Tanggal</label>
                                            <input class="form-control" type="date" id="date" required="" name="date" value="{{old('date')}}">
                                            @if($errors->has('date'))
                                                <div class="error text-danger">{{ $errors->first('date') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="emailaddress" class="form-label">Triwulan</label>
                                            <select class="form-select" aria-label="Default select example" name="triwulan" required>
                                                <option value="">Pilih Triwulan</option>
                                                <option value="1" {{old('triwulan') == '1' ? 'selected' : ''}}>1</option>
                                                <option value="2" {{old('triwulan') == '2' ? 'selected' : ''}}>2</option>
                                                <option value="3" {{old('triwulan') == '3' ? 'selected' : ''}}>3</option>
                                                <option value="4" {{old('triwulan') == '4' ? 'selected' : ''}}>4</option>
                                              </select> 
                                            @if($errors->has('triwulan'))
                                                <div class="error text-danger">{{ $errors->first('triwulan') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="contract" class="form-label">Kontrak</label>
                                            <input class="form-control" type="text" id="contract" required="" name="contract" value="{{old('contract')}}">
                                            @if($errors->has('contract'))
                                                <div class="error text-danger">{{ $errors->first('contract') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <div class="d-flex gap-3 justify-content-end">
                                                    <a href="{{route('bujp.assesment.index')}}" class="btn btn-danger"> Back</a>
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
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

