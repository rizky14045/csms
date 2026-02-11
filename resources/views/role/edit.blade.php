@extends('layout.app')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Role Management</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item">
                <a href="{{ route('roles.index') }}">Role Management</a>
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
                    id="form-role"
                    action="{{ route('roles.update', $role->id) }}"
                    method="POST"
                    onsubmit="confirmSave('form-role', 'Perubahan role akan disimpan')"
                >
                    @csrf
                    @method('PUT')

                    {{-- Nama Role --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Role</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $role->name) }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permissions --}}
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>

                        <div class="mb-2">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                onclick="togglePermissions(true)"
                            >
                                Pilih Semua
                            </button>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                onclick="togglePermissions(false)"
                            >
                                Hapus Semua
                            </button>
                        </div>

                        <div class="border rounded p-3" style="max-height:300px;overflow-y:auto;">

                            @foreach ($groupedPermissions as $module => $permissions)
                                <div class="mb-3">

                                    {{-- Module title --}}
                                    <div class="fw-semibold text-primary mb-2">
                                        {{ $module }}
                                    </div>

                                    @foreach ($permissions as $permission)
                                        <div class="form-check ms-3">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="permissions[]"
                                                id="permission-{{ $permission['id'] }}"
                                                value="{{ $permission['id'] }}"
                                                {{ in_array(
                                                    $permission['id'],
                                                    old('permissions', $rolePermissions)
                                                ) ? 'checked' : '' }}
                                            >
                                            <label
                                                class="form-check-label"
                                                for="permission-{{ $permission['id'] }}"
                                            >
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

                    {{-- Action --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-danger">
                            Kembali
                        </a>
                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function togglePermissions(state) {
        document.querySelectorAll('input[name="permissions[]"]').forEach(function (el) {
            el.checked = state;
        });
    }
</script>

@endsection
