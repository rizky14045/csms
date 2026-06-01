@extends('layout.app')
@section('content')

@php $monthlyId = request('monthly_id'); @endphp
<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ $monthlyId ? route('user.monthly-audit.worker-sum.index', ['monthlyId' => $monthlyId]) : route('user.worker-sum.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Tambah Personil Keamanan Eksternal</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="shield" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Tambah Data Personil Keamanan Eksternal</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.security-external.store') }}" method="POST" id="form-security-external"
                    onsubmit="confirmSave('form-security-external', 'Data keamanan eksternal akan disimpan')">
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
                        <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Pria" {{ old('gender') == 'Pria' ? 'selected' : '' }}>Pria</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="instansi" class="form-label"><span class="text-danger">*</span> Instansi</label>
                        <input class="form-control @error('instansi') is-invalid @enderror" type="text" id="instansi"
                            required placeholder="Masukan instansi" name="instansi" value="{{ old('instansi') }}">
                        @error('instansi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="regional_unit" class="form-label"><span class="text-danger">*</span> Satuan Wilayah</label>
                        <input class="form-control @error('regional_unit') is-invalid @enderror" type="text" id="regional_unit"
                            required placeholder="Masukan satuan wilayah" name="regional_unit" value="{{ old('regional_unit') }}">
                        @error('regional_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="warrant_number" class="form-label"><span class="text-danger">*</span> Nomor Surat Perintah</label>
                        <input class="form-control @error('warrant_number') is-invalid @enderror" type="text" id="warrant_number"
                            required placeholder="Masukan nomor surat perintah" name="warrant_number" value="{{ old('warrant_number') }}">
                        @error('warrant_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="note" class="form-label"><span class="text-danger">*</span> Keterangan</label>
                        <select class="form-select @error('note') is-invalid @enderror" name="note" id="note" required>
                            <option value="">Pilih Keterangan</option>
                            <option value="Polri" {{ old('note') == 'Polri' ? 'selected' : '' }}>Polri</option>
                            <option value="TNI" {{ old('note') == 'TNI' ? 'selected' : '' }}>TNI</option>
                        </select>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($monthlyId)
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="save_to_master" id="save_to_master" value="1" {{ old('save_to_master') ? 'checked' : '' }}>
                            <label class="form-check-label" for="save_to_master">Tambahkan ke master data</label>
                        </div>
                        <div class="form-text text-muted">Jika dicentang, data juga akan disimpan ke daftar master data personil keamanan.</div>
                    </div>
                    @endif

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ $monthlyId ? route('user.monthly-audit.worker-sum.index', ['monthlyId' => $monthlyId]) : route('user.worker-sum.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
