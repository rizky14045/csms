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

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ $user->email }}" placeholder="Masukkan email">
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

                        {{-- Unit --}}
                        <div class="mb-4" id="unitContainer"
                            style="display: {{ in_array(optional($user->roles->first())->id, [2, 3]) ? 'block' : 'none' }}">
                            <label class="form-label fw-semibold">Unit</label>
                            <select id="unitSelect" name="unit_id" class="form-select">
                                @isset($units)
                                    @foreach ($units as $unit)
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
    </script>
@endsection
