@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ route('admin.audit-smp-score.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Edit Data Audit SMP</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="clipboard" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Edit Data Audit SMP</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.audit-smp-score.update', $audit->id) }}" method="POST"
                    enctype="multipart/form-data" id="form-audit"
                    onsubmit="confirmSave('form-audit', 'Data audit akan disimpan')">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="unit_id" class="form-label"><span class="text-danger">*</span> Unit</label>
                        <select class="form-select select2-unit @error('unit_id') is-invalid @enderror" name="unit_id" id="unit_id" required>
                            <option value="">Pilih Unit</option>
                            @foreach ($units_list as $unit)
                                <option value="{{ $unit['id'] }}"
                                    {{ old('unit_id', $audit->unit_id) == $unit['id'] ? 'selected' : '' }}>
                                    {{ $unit['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="start_audit" class="form-label"><span class="text-danger">*</span> Tanggal Mulai Audit</label>
                        <input class="form-control @error('start_audit') is-invalid @enderror" type="date"
                            id="start_audit" name="start_audit" required
                            value="{{ old('start_audit', $audit->start_audit) }}">
                        @error('start_audit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="end_audit" class="form-label"><span class="text-danger">*</span> Tanggal Selesai Audit</label>
                        <input class="form-control @error('end_audit') is-invalid @enderror" type="date"
                            id="end_audit" name="end_audit" required
                            value="{{ old('end_audit', $audit->end_audit) }}">
                        @error('end_audit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="auditor_lead_id" class="form-label"><span class="text-danger">*</span> Ketua Auditor</label>
                        <select class="form-select select2-single @error('auditor_lead_id') is-invalid @enderror"
                            name="auditor_lead_id" id="auditor_lead_id" required>
                            <option value="">Pilih Ketua Auditor</option>
                            @foreach ($auditors as $auditor)
                                <option value="{{ $auditor['id'] }}"
                                    {{ old('auditor_lead_id', $audit->auditor_lead_id) == $auditor['id'] ? 'selected' : '' }}>
                                    {{ $auditor['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('auditor_lead_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="auditors_ids" class="form-label"><span class="text-danger">*</span> Anggota Auditor</label>
                        @php
                            $selectedAuditors = old('auditors_ids', $audit->auditors->pluck('id')->toArray());
                        @endphp
                        <select class="form-select select2-multiple @error('auditors_ids') is-invalid @enderror"
                            name="auditors_ids[]" id="auditors_ids" multiple required>
                            @foreach ($auditors as $auditor)
                                <option value="{{ $auditor['id'] }}"
                                    {{ in_array($auditor['id'], $selectedAuditors) ? 'selected' : '' }}>
                                    {{ $auditor['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('auditors_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SK Audit --}}
                    <div class="form-group mb-3">
                        <label for="sk_file" class="form-label">File SK Audit</label>
                        @if ($audit->sk_file)
                            <div class="mb-2">
                                <a href="{{ asset('uploads/sk_audit/' . $audit->sk_file) }}"
                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i data-feather="file-text" style="width:14px;height:14px;"></i>
                                    Lihat File SK Saat Ini
                                </a>
                            </div>
                        @endif
                        <input class="form-control @error('sk_file') is-invalid @enderror" type="file"
                            id="sk_file" name="sk_file" accept=".pdf">
                        <div class="form-text text-muted">
                            Wajib format <strong>PDF</strong>. Ukuran maksimal <strong>15MB</strong>.
                            @if ($audit->sk_file) Kosongkan jika tidak ingin mengganti file. @endif
                        </div>
                        @error('sk_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.audit-smp-score.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    $('.select2-single').select2({
        placeholder: "Pilih Ketua Auditor",
        allowClear: true,
        width: '100%'
    });

    $('.select2-multiple').select2({
        placeholder: "Pilih Anggota Auditor",
        width: '100%'
    });

    $('.select2-unit').select2({
        placeholder: "Pilih Unit",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endsection
