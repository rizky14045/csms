@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Master Penyerapan Anggaran</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('budget-master.store') }}" class="my-4" method="POST" id="form-budget-master" onsubmit="return confirmSave('form-budget-master', 'Data master penyerapan anggaran akan disimpan.')">
                    @csrf
                    
                    <div class="col-xl-9">
                        @include('budget-master._form')
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('budget-master.index') }}" class="btn btn-danger">Kembali</a>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
