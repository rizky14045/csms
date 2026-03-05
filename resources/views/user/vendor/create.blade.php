@extends('layout.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">BUJP / Vendor</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item">
                <a href="{{route('dashboard')}}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                Tambah Data BUJP / Vendor
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('user.vendor.store') }}" method="POST" id="form-vendor" onsubmit="confirmSave('form-vendor', 'Data vendor akan disimpan')">
                    @csrf

                    {{-- ===============================
                        PILIHAN VENDOR EXISTING ?
                    =============================== --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Vendor sudah terdaftar?</label>

                        <div>
                            <label>
                                <input type="radio" name="vendor_exists" value="1"
                                    {{ old('vendor_exists') == "1" ? 'checked' : '' }}>
                                Ya
                            </label>

                            <label class="ms-3">
                                <input type="radio" name="vendor_exists" value="0"
                                    {{ old('vendor_exists', '0') == "0" ? 'checked' : '' }}>
                                Tidak
                            </label>
                        </div>
                    </div>

                    <div id="vendorSelect"
                        style="{{ old('vendor_exists') == '1' ? '' : 'display:none;' }}">

                        <div class="form-group mb-3">
                            <label>Pilih Vendor</label>

                            <select name="vendor_id" class="form-control select2">
                                <option value="">-- Pilih Vendor --</option>

                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor['id'] }}"
                                        {{ old('vendor_id') == $vendor['id'] ? 'selected' : '' }}>
                                        {{ $vendor['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('vendor_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- ===============================
                        FORM VENDOR BARU
                    =============================== --}}
                    <div id="vendorForm"
                        style="{{ old('vendor_exists', '0') == '0' ? '' : 'display:none;' }}">

                        <div class="form-group mb-3">
                            <label>Nama Vendor</label>
                            <input type="text" name="name"
                                class="form-control"
                                value="{{ old('name') }}">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="text" name="email"
                                class="form-control"
                                value="{{ old('email') }}">

                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>NPWP</label>
                            <input type="text" name="npwp"
                                class="form-control"
                                value="{{ old('npwp') }}">

                            @error('npwp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Alamat Vendor</label>
                            <textarea name="address"
                                    class="form-control">{{ old('address') }}</textarea>

                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- ===============================
                        DATA KONTRAK
                    =============================== --}}
                    <div class="form-group mb-3">
                        <label>Nomor Kontrak</label>
                        <input type="text" name="contract_number"
                            class="form-control"
                            value="{{ old('contract_number') }}">

                        @error('contract_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date"
                            class="form-control"
                            value="{{ old('start_date') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label>Tanggal Berakhir</label>
                        <input type="date" name="end_date"
                            class="form-control"
                            value="{{ old('end_date') }}">
                    </div>

                    <a href="{{route('user.vendor.index')}}" class="btn btn-danger"> Kembali</a>
                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
$(document).ready(function () {

    // Init Select2
    $('.select2').select2({
        width: '100%'
    });

    // Function toggle
    function toggleVendorForm() {
        let value = $('input[name="vendor_exists"]:checked').val();

        if (value === "1") {
            $('#vendorSelect').show();
            $('#vendorForm').hide();
        } else {
            $('#vendorSelect').hide();
            $('#vendorForm').show();
        }
    }

    // Trigger saat radio berubah
    $('input[name="vendor_exists"]').on('change', toggleVendorForm);

    // Trigger saat halaman load (penting untuk old value)
    toggleVendorForm();
});
</script>

@endsection