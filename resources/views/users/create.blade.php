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
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <form
                    id="form-user"
                    action="{{ route('users.store') }}"
                    method="POST"
                >
                    @csrf

                    {{-- Nama User --}}
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email User --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role User --}}
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select
                            name="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="" disabled selected>Pilih Role</option>
                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role['id'] }}"
                                    {{ old('role') == $role['id'] ? 'selected' : '' }}
                                >
                                    {{ $role['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password User --}}
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>

                        <div class="input-group">
                            <input class="form-control @error('password') is-invalid @enderror" type="password" required id="password"
                                placeholder="Masukan password" name="password">

                            <button class="btn btn-outline-primary" type="button"
                                id="togglePassword">
                                <i data-feather="eye" id="eyeIcon"></i>
                            </button>
                        </div>

                        @if ($errors->has('password'))
                            <div class="error text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>

                        <div class="input-group">
                            <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" required id="password_confirmation"
                                placeholder="Masukan konfirmasi password" name="password_confirmation"> 
                            <button class="btn btn-outline-primary" type="button"
                                id="togglePasswordConfirmation">
                                <i data-feather="eye" id="eyeIconConfirmation"></i>
                            </button>
                        </div>

                        @if ($errors->has('password_confirmation'))
                            <div class="error text-danger">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    {{-- Action --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-danger">
                            Kembali
                        </a>
                        <button
                            type="button"
                            class="btn btn-success"
                            onclick="confirmSave('form-user', 'User akan disimpan')"
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
