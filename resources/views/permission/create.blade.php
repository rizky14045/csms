@extends('layout.app')

@section('content')

<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ route('permissions.index') }}" class="text-muted text-decoration-none">
        <i class="ri-arrow-left-line fs-5"></i>
    </a>
    <h4 class="mb-0">Permission Management - Create</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-lock-password-line fs-5 text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Tambah Permission Baru</h6>
                </div>
            </div>
            <div class="card-body p-4">

                <form
                    id="form-permission"
                    action="{{ route('permissions.store') }}"
                    method="POST"
                    onsubmit="confirmSave('form-permission', 'Permission akan disimpan')"
                >
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Permission</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama permission"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('permissions.index') }}" class="btn btn-danger">
                            <i class="ri-arrow-go-back-line me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i>Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
