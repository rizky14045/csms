@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Assesment</li>
        </ol>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card shadow-sm rounded-4">
            <div class="card-body">
                <form action="{{route('admin.category-assesment.store')}}" class="my-4" method="POST" id="form-category-assesment" onsubmit="confirmSave('form-category-assesment', 'Data kategori akan disimpan')">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="name" required placeholder="Masukan nama" value="{{old('name')}}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{route('admin.category-assesment.index')}}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
