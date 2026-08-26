@extends('layout.app')

@section('styles')
@endsection

@section('content')
    <div class="py-3 d-flex align-items-center gap-2">
        <a href="{{ route('users.index') }}" class="text-muted text-decoration-none">
            <i class="ri-arrow-left-line fs-5"></i>
        </a>
        <h4 class="mb-0">User Management - Create</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-user-add-line fs-5 text-primary"></i>
                        <h6 class="mb-0 fw-semibold">Tambah Pengguna Baru</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="form-user" onsubmit="confirmSave('form-user', 'Simpan data user?')" action="{{ route('users.store') }}" method="POST">
                        @csrf

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" class="form-control" required placeholder="Masukkan nama">
                        </div>

                        {{-- Email / Username --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email / Username</label>
                            <input type="text" name="email" class="form-control" required placeholder="Masukkan email atau username (untuk login LDAP)">
                        </div>

                        {{-- Role --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role</label>
                            <select id="roleSelect" name="role" class="form-select" required>
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- LDAP --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_ldap" name="is_ldap" value="1">
                            <label class="form-check-label" for="is_ldap">Login via LDAP</label>
                        </div>

                        {{-- Tipe Unit --}}
                        <div class="mb-3" id="typeUnitContainer" style="display: none;">
                            <label class="form-label fw-semibold">Tipe Unit</label>
                            <select id="typeUnitSelect" name="type_unit" class="form-select">
                                <option value="">Pilih Tipe Unit</option>
                                <option value="Pusat">Pusat</option>
                                <option value="Unit">Unit</option>
                            </select>
                        </div>

                        {{-- Unit --}}
                        <div class="mb-3" id="unitContainer" style="display: none;">
                            <label class="form-label fw-semibold">Unit</label>
                            <select id="unitSelect" name="unit_id" class="form-select">
                                <option value="">Pilih Unit</option>
                            </select>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i data-feather="eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div id="passwordFeedback" class="mt-2 ps-1 d-flex flex-column gap-1"></div>
                        </div>

                        {{-- Confirm --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                    <i data-feather="eye" id="eyeIconConfirmation"></i>
                                </button>
                            </div>
                            <div id="passwordMatchFeedback" class="mt-2 ps-1"></div>
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

            const $typeUnitContainer = $('#typeUnitContainer');
            const $typeUnit = $('#typeUnitSelect');

            const $unitContainer = $('#unitContainer');
            const $unit = $('#unitSelect');

            // ================= TOGGLE UNIT =================
            function toggleUnitByRole() {
                const roleValue = parseInt($role.val());

                // reset dulu
                $typeUnitContainer.hide(); // tidak perlu tampil lagi
                $typeUnit.removeAttr('required').val('');

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
            $role.on('change', function() {
                toggleUnitByRole();
            });

            // ================= INIT =================
            toggleUnitByRole();

        });

        // ================= PASSWORD CHECKER =================
        checkPasswordStrength('password', 'passwordFeedback', 'password_confirmation', 'passwordMatchFeedback');
    </script>
@endsection
