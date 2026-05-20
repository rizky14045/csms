@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ route('user.worker-sum.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Edit Penanggung Jawab Keamanan</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="user" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Edit Data Penanggung Jawab Keamanan</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.responsible-person.update', ['person' => $person->id]) }}" method="POST" id="form-responsible"
                    onsubmit="confirmSave('form-responsible', 'Data penanggung jawab keamanan akan disimpan')">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="name" class="form-label"><span class="text-danger">*</span> Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                            required placeholder="Masukan nama" name="name" value="{{ old('name', $person->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="position" class="form-label"><span class="text-danger">*</span> Jabatan</label>
                        <input class="form-control @error('position') is-invalid @enderror" type="text" id="position"
                            required placeholder="Masukan jabatan" name="position" value="{{ old('position', $person->position) }}">
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="work_unit" class="form-label"><span class="text-danger">*</span> Unit Kerja</label>
                        <input class="form-control @error('work_unit') is-invalid @enderror" type="text" id="work_unit"
                            required placeholder="Masukan unit kerja" name="work_unit" value="{{ old('work_unit', $person->work_unit) }}">
                        @error('work_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Checklist fields --}}
                    @php
                        $checklistFields = [
                            'training_smp'           => 'Pelatihan SMP',
                            'auditor_smp'            => 'Auditor SMP',
                            'main'                   => 'Utama',
                            'investigation'          => 'Investigasi',
                            'mansrisk'               => 'Mansrisk',
                            'stackholder_management' => 'Stakeholder Management',
                        ];
                    @endphp

                    @foreach ($checklistFields as $field => $label)
                    <div class="form-group mb-3">
                        <label class="form-label">{{ $label }}</label>
                        <div class="d-flex gap-2">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="{{ $field }}" id="{{ $field }}_ya"
                                    value="Ya" autocomplete="off"
                                    {{ old($field, $person->$field) == 'Ya' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="{{ $field }}_ya">Ya</label>

                                <input type="radio" class="btn-check" name="{{ $field }}" id="{{ $field }}_tidak"
                                    value="Tidak" autocomplete="off"
                                    {{ old($field, $person->$field) == 'Tidak' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="{{ $field }}_tidak">Tidak</label>
                            </div>
                        </div>
                        @error($field)
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    @endforeach

                    <div class="form-group mb-3">
                        <label for="last_education" class="form-label">Pendidikan Terakhir</label>
                        <input class="form-control @error('last_education') is-invalid @enderror" type="text" id="last_education"
                            placeholder="Masukan pendidikan terakhir" name="last_education" value="{{ old('last_education', $person->last_education) }}">
                        @error('last_education')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="note" class="form-label">Keterangan</label>
                        <input class="form-control @error('note') is-invalid @enderror" type="text" id="note"
                            placeholder="Masukan keterangan" name="note" value="{{ old('note', $person->note) }}">
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('user.worker-sum.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
