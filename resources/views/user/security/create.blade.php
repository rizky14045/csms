@extends('layout.app')

@section('content')

@php $monthlyId = request('monthly_id'); @endphp
<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ $monthlyId ? route('user.monthly-audit.security-form.index', ['monthlyId' => $monthlyId]) : route('user.security.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Tambah Satuan Pengamanan</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="shield" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Tambah Data Satuan Pengamanan</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.security.store') }}" method="POST" id="form-security"
                    enctype="multipart/form-data"
                    onsubmit="confirmSave('form-security', 'Data satuan pengaman akan disimpan')">
                    @csrf
                    @if($monthlyId)
                    <input type="hidden" name="monthly_id" value="{{ $monthlyId }}">
                    @endif

                    <div class="form-group mb-3">
                        <label for="name" class="form-label"><span class="text-danger">*</span> Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                            required placeholder="Masukan nama" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="gender" class="form-label"><span class="text-danger">*</span> Jenis Kelamin</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Pria" {{ old('gender') == 'Pria' ? 'selected' : '' }}>Pria</option>
                            <option value="Wanita" {{ old('gender') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="unit_work" class="form-label"><span class="text-danger">*</span> Unit Kerja</label>
                        <input class="form-control @error('unit_work') is-invalid @enderror" id="unit_work" type="text"
                            required placeholder="Masukan unit kerja" name="unit_work" value="{{ old('unit_work') }}">
                        @error('unit_work')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="nid" class="form-label"><span class="text-danger">*</span> NID</label>
                        <input class="form-control @error('nid') is-invalid @enderror" id="nid" type="text"
                            required placeholder="Masukan NID" name="nid" value="{{ old('nid') }}">
                        @error('nid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="registration_number" class="form-label"><span class="text-danger">*</span> Nomor REG KTA</label>
                        <input class="form-control @error('registration_number') is-invalid @enderror" id="registration_number"
                            type="text" required placeholder="Masukan nomor reg KTA" name="registration_number"
                            value="{{ old('registration_number') }}">
                        @error('registration_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="expired_card_date" class="form-label"><span class="text-danger">*</span> Expired KTA</label>
                        <input class="form-control @error('expired_card_date') is-invalid @enderror" id="expired_card_date"
                            type="date" required name="expired_card_date" value="{{ old('expired_card_date') }}">
                        @error('expired_card_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="position" class="form-label"><span class="text-danger">*</span> Jabatan</label>
                        <select class="form-select @error('position') is-invalid @enderror" id="position" name="position" required>
                            <option value="">Pilih Jabatan</option>
                            <option value="Komandan" {{ old('position') == 'Komandan' ? 'selected' : '' }}>Komandan</option>
                            <option value="Anggota" {{ old('position') == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                            <option value="Chief" {{ old('position') == 'Chief' ? 'selected' : '' }}>Chief</option>
                        </select>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="birth_place" class="form-label"><span class="text-danger">*</span> Tempat Lahir</label>
                        <input class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" type="text"
                            required placeholder="Masukan tempat lahir" name="birth_place" value="{{ old('birth_place') }}">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="birth_date" class="form-label"><span class="text-danger">*</span> Tanggal Lahir</label>
                        <input class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" type="date"
                            required name="birth_date" value="{{ old('birth_date') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="qualification" class="form-label"><span class="text-danger">*</span> Kualifikasi</label>
                        <select class="form-select @error('qualification') is-invalid @enderror" id="qualification" name="qualification" required>
                            <option value="">Pilih Kualifikasi</option>
                            <option value="Pratama" {{ old('qualification') == 'Pratama' ? 'selected' : '' }}>Pratama</option>
                            <option value="Madya" {{ old('qualification') == 'Madya' ? 'selected' : '' }}>Madya</option>
                            <option value="Utama" {{ old('qualification') == 'Utama' ? 'selected' : '' }}>Utama</option>
                        </select>
                        @error('qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_education" class="form-label"><span class="text-danger">*</span> Pendidikan Terakhir</label>
                        <input class="form-control @error('last_education') is-invalid @enderror" id="last_education"
                            type="text" required placeholder="Masukan pendidikan terakhir" name="last_education"
                            value="{{ old('last_education') }}">
                        @error('last_education')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="note" class="form-label">Note <span class="text-muted small">(opsional)</span></label>
                        <input class="form-control @error('note') is-invalid @enderror" id="note" type="text"
                            placeholder="Masukan note" name="note" value="{{ old('note') }}">
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="kta_file" class="form-label">
                            @if(!$monthlyId)<span class="text-danger">*</span> @endif
                            File KTA
                            @if($monthlyId)<span class="text-muted small">(opsional)</span>@endif
                        </label>
                        <input class="form-control @error('kta_file') is-invalid @enderror" id="kta_file" type="file"
                            name="kta_file" accept=".pdf,.jpg,.jpeg,.png" {{ $monthlyId ? '' : 'required' }}>
                        <div class="form-text text-muted">Format: PDF, JPG, JPEG, PNG. Maksimal 5 MB.</div>
                        @error('kta_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($monthlyId)
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="save_to_master" id="save_to_master" value="1" {{ old('save_to_master') ? 'checked' : '' }}>
                            <label class="form-check-label" for="save_to_master">Tambahkan ke master data</label>
                        </div>
                        <div class="form-text text-muted">Jika dicentang, data juga akan disimpan ke daftar master data satuan pengamanan.</div>
                    </div>
                    @endif

                    <div class="d-flex gap-2 justify-content-end mt-2">
                        <a href="{{ $monthlyId ? route('user.monthly-audit.security-form.index', ['monthlyId' => $monthlyId]) : route('user.security.index') }}" class="btn btn-danger">
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
