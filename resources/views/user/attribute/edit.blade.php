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
    <a href="{{ route('user.attribute.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Edit Atribut</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="edit-2" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Edit Data Atribut</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.attribute.update', ['attribute' => $attribute->id]) }}" method="POST"
                    id="form-attribute-unit"
                    onsubmit="confirmSave('form-attribute-unit', 'Data atribut akan disimpan')">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                            required placeholder="Masukan nama" name="name"
                            value="{{ old('name', $attribute->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status_ownership" class="form-label">Status Kepemilikan</label>
                        <select class="form-select @error('status_ownership') is-invalid @enderror"
                            name="status_ownership" required>
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="BUJP" {{ old('status_ownership', $attribute->status_ownership) == 'BUJP' ? 'selected' : '' }}>BUJP</option>
                            <option value="PNP" {{ old('status_ownership', $attribute->status_ownership) == 'PNP' ? 'selected' : '' }}>PNP</option>
                        </select>
                        @error('status_ownership')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="unit" class="form-label">Satuan</label>
                        <select class="form-select @error('unit') is-invalid @enderror" name="unit" required>
                            <option value="">Pilih Satuan</option>
                            <option value="Unit" {{ old('unit', $attribute->unit) == 'Unit' ? 'selected' : '' }}>Unit</option>
                            <option value="Lembar" {{ old('unit', $attribute->unit) == 'Lembar' ? 'selected' : '' }}>Lembar</option>
                            <option value="Jumlah" {{ old('unit', $attribute->unit) == 'Jumlah' ? 'selected' : '' }}>Jumlah</option>
                            <option value="Orang" {{ old('unit', $attribute->unit) == 'Orang' ? 'selected' : '' }}>Orang</option>
                            <option value="Titik" {{ old('unit', $attribute->unit) == 'Titik' ? 'selected' : '' }}>Titik</option>
                            <option value="Meter" {{ old('unit', $attribute->unit) == 'Meter' ? 'selected' : '' }}>Meter</option>
                        </select>
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="standard_contract" class="form-label">Jumlah Standar Kontrak</label>
                        <input class="form-control @error('standard_contract') is-invalid @enderror" type="text"
                            id="standard_contract" required placeholder="Masukan jumlah standar kontrak"
                            name="standard_contract"
                            value="{{ old('standard_contract', $attribute->standard_contract) }}">
                        @error('standard_contract')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="type_attribute" class="form-label">Tipe Atribut</label>
                        <select class="form-select @error('type_attribute') is-invalid @enderror"
                            name="type_attribute" required>
                            <option value="">Pilih Tipe Atribut</option>
                            <option value="Attribute" {{ old('type_attribute', $attribute->type_attribute) == 'Attribute' ? 'selected' : '' }}>Attribute</option>
                            <option value="Sarana" {{ old('type_attribute', $attribute->type_attribute) == 'Sarana' ? 'selected' : '' }}>Sarana</option>
                            <option value="Administrasi" {{ old('type_attribute', $attribute->type_attribute) == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                        </select>
                        @error('type_attribute')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-2">
                        <a href="{{ route('user.attribute.index') }}" class="btn btn-danger">
                            <i data-feather="arrow-left" style="width:14px;height:14px;" class="me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i data-feather="save" style="width:14px;height:14px;" class="me-1"></i>Simpan
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
    feather.replace();
</script>
@endsection
