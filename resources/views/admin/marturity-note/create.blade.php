@extends('layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Marturity Note</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Marturity Note</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.marturity-note.store',['level'=>$level->id])}}" class="my-4" method="POST" id="form-marturity-note" onsubmit="confirmSave('form-marturity-note', 'Data note akan disimpan')">
                    @csrf
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label for="note" class="form-label">Note</label>
                            <input class="form-control @error('note') is-invalid @enderror" name="note" type="text" id="note" required="" placeholder="Masukan note" value="{{old('note')}}">
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('admin.marturity-area.index')}}" class="btn btn-danger"> Kembali</a>
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

