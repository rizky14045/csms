@extends('layout.app')

@section('content')

<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ route('roles.index') }}" class="text-muted text-decoration-none">
        <i class="ri-arrow-left-line fs-5"></i>
    </a>
    <h4 class="mb-0">Role Management - Create</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-shield-keyhole-line fs-5 text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Tambah Role Baru</h6>
                </div>
            </div>
            <div class="card-body p-4">

                <form
                    id="form-role"
                    action="{{ route('roles.store') }}"
                    method="POST"
                    onsubmit="confirmSave('form-role', 'Role dan permission akan disimpan')"
                >
                    @csrf

                    {{-- Nama Role --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Role</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama role"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permissions --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Permissions</label>

                        <div class="mb-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="togglePermissions(true)">Pilih Semua</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="togglePermissions(false)">Hapus Semua</button>
                        </div>

                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach ($groupedPermissions as $module => $permissions)
                                <div class="mb-3">
                                    <div class="fw-semibold text-primary mb-2">{{ $module }}</div>
                                    @foreach ($permissions as $permission)
                                        <div class="form-check ms-3">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="permissions[]"
                                                id="permission-{{ $permission['id'] }}"
                                                value="{{ $permission['id'] }}"
                                                {{ in_array($permission['id'], old('permissions', [])) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="permission-{{ $permission['id'] }}">
                                                {{ $permission['name'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                            @endforeach
                        </div>

                        @error('permissions')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-danger">
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

<script>
    function togglePermissions(state) {
        document.querySelectorAll('input[name="permissions[]"]').forEach(function (checkbox) {
            checkbox.checked = state;
        });
    }
</script>

@endsection
