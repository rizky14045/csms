@extends('layout.app')

@section('styles')
@endsection

@section('content')
    <div class="py-3 d-flex justify-content-between">
        <h4>User Management - Create</h4>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="form-user" action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- Nama --}}
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label>Role</label>
                    <select id="roleSelect" name="role" class="form-select" required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipe Unit --}}
                <div class="mb-3" id="typeUnitContainer" style="display: none;">
                    <label>Tipe Unit</label>
                    <select id="typeUnitSelect" name="type_unit" class="form-select">
                        <option value="">Pilih Tipe Unit</option>
                        <option value="Pusat">Pusat</option>
                        <option value="Unit">Unit</option>
                    </select>
                </div>

                {{-- Unit --}}
                <div class="mb-3" id="unitContainer" style="display: none;">
                    <label>Unit</label>
                    <select id="unitSelect" name="unit_id" class="form-select">
                        <option value="">Pilih Unit</option>
                    </select>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                {{-- Confirm --}}
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="text-end">
                    <button class="btn btn-danger" type="button" onclick="window.history.back();">Batal</button>
                    <button class="btn btn-success">Simpan</button>
                </div>

            </form>

        </div>
        ```

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

            // ================= TOGGLE TYPE UNIT =================
            function toggleTypeUnit() {

                if (parseInt($role.val()) === 3) {
                    $typeUnitContainer.show();
                    $typeUnit.attr('required', true);
                } else {
                    $typeUnitContainer.hide();
                    $unitContainer.hide();

                    $typeUnit.removeAttr('required').val('');
                    $unit.html('<option value="">Pilih Unit</option>');
                }
            }

            // ================= FETCH UNIT =================
            function fetchUnits(type) {

                if (!type) {
                    $unitContainer.hide();
                    $unit.html('<option value="">Pilih Unit</option>');
                    return;
                }

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

                            // 🔥 FIX UTAMA: paksa tampil
                            $unitContainer.show();

                            res.forEach(unit => {
                                $unit.append(
                                `<option value="${unit.id}">${unit.name}</option>`);
                            });

                            if (res.length === 1) {
                                $unit.val(res[0].id);
                            }

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
                toggleTypeUnit();
            });

            $typeUnit.on('change', function() {
                fetchUnits($(this).val());
            });

            // ================= INIT =================
            toggleTypeUnit();

        });
    </script>
@endsection
