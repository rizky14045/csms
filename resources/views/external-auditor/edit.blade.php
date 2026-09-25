@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Auditor External</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Auditor External</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('external-auditor.update', ['item' => $item->id]) }}" class="my-4" method="POST" id="form-external-auditor" onsubmit="return confirmSave('form-external-auditor', 'Perubahan data auditor external akan disimpan.')">
                    @csrf
                    @method('PATCH')
                    <div class="col-xl-9">
                        @include('external-auditor._form')
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('external-auditor.index') }}" class="btn btn-danger">Kembali</a>
                            <button class="btn btn-success" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
