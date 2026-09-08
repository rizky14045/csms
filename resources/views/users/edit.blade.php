@extends('layout.app')

@section('styles')
@endsection

@section('content')
    <div class="py-3 d-flex align-items-center gap-2">
        <a href="{{ route('users.index') }}" class="text-muted text-decoration-none">
            <i class="ri-arrow-left-line fs-5"></i>
        </a>
        <h4 class="mb-0">User Management - Edit</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-user-settings-line fs-5 text-primary"></i>
                        <h6 class="mb-0 fw-semibold">Edit Data Pengguna</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="formEditUser" onsubmit="confirmSave('formEditUser', 'Simpan perubahan user?')" action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" class="form-control" required value="{{ $user->name }}" placeholder="Masukkan nama">
                        </div>

                        {{-- Email / Username --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email / Username</label>
                            <input type="text" name="email" class="form-control" required value="{{ $user->email }}" placeholder="Masukkan email atau username (untuk login LDAP)">
                        </div>

                        {{-- Role --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role</label>
                            <select id="roleSelect" name="role" class="form-select" required>
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['id'] }}" {{ $user->roles->contains($role['id']) ? 'selected' : '' }}>
                                        {{ $role['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- LDAP --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_ldap" name="is_ldap" value="1" {{ $user->login_type == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_ldap">Login via LDAP</label>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3" id="passwordContainer">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i data-feather="eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div id="passwordFeedback" class="mt-2 ps-1 d-flex flex-column gap-1"></div>
                            <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                        </div>

                        {{-- Confirm --}}
                        <div class="mb-4" id="passwordConfirmationContainer">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                    <i data-feather="eye" id="eyeIconConfirmation"></i>
                                </button>
                            </div>
                            <div id="passwordMatchFeedback" class="mt-2 ps-1"></div>
                        </div>

                        {{-- Unit --}}
                        <div class="mb-4" id="unitContainer"
                            style="display: {{ in_array(optional($user->roles->first())->id, [2, 3]) ? 'block' : 'none' }}">
                            <label class="form-label fw-semibold">Unit</label>
                            <select id="unitSelect" name="unit_id" class="form-select">
                                @isset($unit_lists)
                                    @foreach ($unit_lists as $unit)
                                        <option value="{{ $unit->id }}" {{ $user->unit_id == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-danger" type="button" onclick="window.history.back();">
                                <i class="ri-arrow-go-back-line me-1"></i>Batal
                            </button>
                            <button class="btn btn-success" type="submit">
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
        $(document).ready(function() {

            const $role = $('#roleSelect');
            const $unitContainer = $('#unitContainer');
            const $unit = $('#unitSelect');

            const $isLdap = $('#is_ldap');
            const $password = $('#password');
            const $passwordConfirmation = $('#password_confirmation');
            const $passwordContainer = $('#passwordContainer');
            const $passwordConfirmationContainer = $('#passwordConfirmationContainer');

            // ================= TOGGLE PASSWORD (LDAP) =================
            function togglePasswordVisibility() {
                const isLdap = $isLdap.is(':checked');

                $passwordContainer.toggle(!isLdap);
                $passwordConfirmationContainer.toggle(!isLdap);

                if (isLdap) {
                    $password.val('');
                    $passwordConfirmation.val('');
                }
            }

            $isLdap.on('change', togglePasswordVisibility);
            togglePasswordVisibility();

            // ================= TOGGLE UNIT =================
            function toggleUnitByRole() {
                const roleValue = parseInt($role.val());

                // reset option hanya saat role diganti
                $unit.html('<option value="">Pilih Unit</option>');
                $unitContainer.hide();

                // Role 2 = Pusat
                if (roleValue === 2) {
                    fetchUnits('Pusat');
                }

                // Role 3 = Unit
                else if (roleValue === 3) {
                    fetchUnits('Unit');
                }

                // selain itu hide unit
                else {
                    $unit.removeAttr('required');
                    $unitContainer.hide();
                }
            }

            // ================= FETCH UNIT =================
            function fetchUnits(type) {

                if (!type) {
                    $unitContainer.hide();
                    $unit.html('<option value="">Pilih Unit</option>');
                    return;
                }

                $unitContainer.show();
                $unit.attr('required', true);
                $unit.html('<option value="">Loading...</option>');

                $.ajax({
                    url: "{{ route('units.byType') }}",
                    type: "GET",
                    data: {
                        type_unit: type
                    },

                    success: function(res) {
                        console.log('Units:', res);

                        $unit.html('<option value="">Pilih Unit</option>');

                        if (Array.isArray(res) && res.length > 0) {

                            res.forEach(unit => {
                                $unit.append(
                                    `<option value="${unit.id}">${unit.name}</option>`
                                );
                            });

                            // auto select kalau cuma 1 data
                            if (res.length === 1) {
                                $unit.val(res[0].id);
                            }

                            $unitContainer.show();

                        } else {
                            $unitContainer.hide();
                        }
                    },

                    error: function(err) {
                        console.log('Error:', err);
                        $unit.html('<option value="">Gagal load data</option>');
                    }
                });
            }

            // ================= EVENT =================
            // hanya trigger saat role berubah
            $role.on('change', function() {
                toggleUnitByRole();
            });

            // TIDAK pakai INIT
            // toggleUnitByRole();

        });

        // ================= PASSWORD CHECKER =================
        checkPasswordStrength('password', 'passwordFeedback', 'password_confirmation', 'passwordMatchFeedback');
    </script>
@endsection
