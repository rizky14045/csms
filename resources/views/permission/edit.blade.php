@extends('layout.app')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Permission Management</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item">
                <a href="{{ route('permissions.index') }}">Permission Management</a>
            </li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <form
                    id="form-permission"
                    action="{{ route('permissions.update', $permission->id) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    {{-- Nama Permission --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Permission</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $permission->name) }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('permissions.index') }}" class="btn btn-danger">
                            Kembali
                        </a>
                        <button
                            type="button"
                            class="btn btn-success"
                            onclick="confirmSave('form-permission', 'Perubahan permission akan disimpan')"
                        >
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
