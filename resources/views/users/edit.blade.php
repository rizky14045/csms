@extends('layout.app')

@section('styles')
@endsection

@section('content')
    <div class="py-3 d-flex justify-content-between">
        <h4>User Management - Edit</h4>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required value="{{ $user->name }}">
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label>Role</label>
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
                <div class="mb-3" id="unitContainer"
                    style="display: {{ $user->type == 'user' && in_array(optional($user->roles->first())->id, [2, 3]) ? 'block' : 'none' }}">

                    <label>Unit</label>

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
                <div class="text-end">
                    <button class="btn btn-danger" type="button" onclick="window.history.back();">Batal</button>
                    <button class="btn btn-success">Simpan</button>
                </div>

            </form>

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
