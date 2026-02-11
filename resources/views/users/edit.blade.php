@extends('layout.app')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">User Management</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item">
                <a href="{{ route('users.index') }}">User Management</a>
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
                    id="form-user"
                    action="{{ route('users.update', $user->id) }}"
                    method="POST"
                    onsubmit="confirmSave('form-user', 'Perubahan user akan disimpan')"
                >
                    @csrf
                    @method('PUT')

                    {{-- Nama User --}}
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Nama</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email User --}}
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role User --}}
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Role</label>
                        <select
                            name="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="" disabled>Pilih Role</option>
                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role['id'] }}"
                                    {{ old('role', $user->roles->first()->id ?? null) == $role['id'] ? 'selected' : '' }}
                                >
                                    {{ $role['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password User (Optional) --}}
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">
                            Password <small class="text-muted">(Opsional)</small>
                        </label>

                        <div class="input-group">
                            <input
                                class="form-control @error('password') is-invalid @enderror"
                                type="password"
                                id="password"
                                placeholder="Masukan password baru"
                                name="password"
                            >

                            <button
                                class="btn btn-outline-primary"
                                type="button"
                                id="togglePassword"
                            >
                                <i data-feather="eye" id="eyeIcon"></i>
                            </button>
                        </div>

                        @error('password')
                            <div class="error text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">
                            Konfirmasi Password <small class="text-muted">(Opsional)</small>
                        </label>

                        <div class="input-group">
                            <input
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                type="password"
                                id="password_confirmation"
                                placeholder="Masukan konfirmasi password"
                                name="password_confirmation"
                            >
                            <button
                                class="btn btn-outline-primary"
                                type="button"
                                id="togglePasswordConfirmation"
                            >
                                <i data-feather="eye" id="eyeIconConfirmation"></i>
                            </button>
                        </div>

                        @error('password_confirmation')
                            <div class="error text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-danger">
                            Kembali
                        </a>
                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
