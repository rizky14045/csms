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
        <h4 class="fs-18 fw-semibold m-0">Audit SMP</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Audit SMP</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.audit-smp.store')}}" class="my-4" method="POST" id="form-audit-smp" onsubmit="confirmSave('form-audit-smp', 'Data Audit SMP akan disimpan')">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="name" required="" placeholder="Masukan nama" value="{{old('name')}}">
                            @error('name')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="bobot" class="form-label">
                                Bobot
                                <span class="ms-2 badge {{ $remainingBobot > 0 ? 'bg-success' : 'bg-danger' }}">
                                    Sisa: {{ $remainingBobot }}%
                                </span>
                                <span class="ms-1 badge bg-secondary">Total saat ini: {{ $totalBobot }}%</span>
                            </label>
                            <input class="form-control @error('bobot') is-invalid @enderror" name="bobot" type="number"
                                id="bobot" required placeholder="Masukan bobot (1–{{ $remainingBobot }})"
                                min="1" max="{{ $remainingBobot }}" value="{{ old('bobot') }}">
                            @error('bobot')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                            @if($remainingBobot <= 0)
                                <div class="alert alert-danger mt-2 py-2 px-3 small">
                                    Total bobot sudah mencapai 100%. Tidak dapat menambah data baru.
                                </div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('admin.audit-smp.index')}}" class="btn btn-danger"> Kembali</a>
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

