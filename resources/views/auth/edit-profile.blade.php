@extends('layout.app')

@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop

@section('content')
    <div class="py-3 d-flex align-items-center gap-2">
        <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">
            <i class="ri-arrow-left-line fs-5"></i>
        </a>
        <h4 class="mb-0">Ubah Password</h4>
    </div>

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="ri-error-warning-line fs-5"></i>
            <span>{{ session('warning') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-lock-password-line fs-5 text-primary"></i>
                        <h6 class="mb-0 fw-semibold">Ubah Password Akun</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" id="form-edit-profile"
                        onsubmit="confirmSave('form-edit-profile', 'Password akan disimpan')">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Lama</label>
                            <div class="input-group">
                                <input type="password" id="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror"
                                    name="old_password" placeholder="Masukkan password lama">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('old_password', this)">
                                    <i data-feather="eye" class="eye-icon"></i>
                                </button>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" placeholder="Masukkan password baru">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('password', this)">
                                    <i data-feather="eye" class="eye-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="passwordFeedback" class="mt-2 ps-1 d-flex flex-column gap-1"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ulangi Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('password_confirmation', this)">
                                    <i data-feather="eye" class="eye-icon"></i>
                                </button>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="passwordMatchFeedback" class="mt-2 ps-1"></div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-danger">
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

@section('scripts')
    <script>
        function togglePassword(id, el) {
            const input = document.getElementById(id);
            const icon = el.querySelector('.eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-feather', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        }

        checkPasswordStrength('password', 'passwordFeedback', 'password_confirmation', 'passwordMatchFeedback');
    </script>
@endsection
