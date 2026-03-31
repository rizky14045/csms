@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
    <style>
        #map { height: 500px; width: 100%; }
    </style>
</style>
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Audit SMP</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Audit</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.audit-smp-score.store')}}" class="my-4" method="POST" id="form-audit" onsubmit="confirmSave('form-audit', 'Data audit akan disimpan')">
                    @csrf
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="unit_id" class="form-label">Unit</label>

                            <select 
                                class="form-select select2-unit" 
                                name="unit_id" 
                                required
                            >
                                <option value="">Pilih Unit</option>
                                @foreach ($units_list as $unit)
                                    <option value="{{ $unit['id'] }}" 
                                        {{ old('unit_id') == $unit['id'] ? 'selected' : '' }}>
                                        {{ $unit['name'] }}
                                    </option>
                                @endforeach
                            </select> 

                            @error('unit_id')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="start_audit" class="form-label">Tanggal Mulai Audit</label>
                            <input class="form-control" type="date" id="start_audit" required="" placeholder="Masukan tanggal mulai audit" name="start_audit" value="{{old('start_audit')}}">
                            @error('start_audit')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="end_audit" class="form-label">Tanggal Selesai Audit</label>
                            <input class="form-control" type="date" id="end_audit" required="" placeholder="Masukan tanggal selesai audit" name="end_audit" value="{{old('end_audit')}}">
                            @error('end_audit')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="auditor_lead_id" class="form-label">Ketua Auditor</label>
                            <select 
                                class="form-select select2-single" 
                                name="auditor_lead_id" 
                                required
                            >
                                <option value="">Pilih Ketua Auditor</option>
                                @foreach ($auditors as $auditor)
                                    <option value="{{ $auditor['id'] }}" 
                                        {{ old('auditor_lead_id') == $auditor['id'] ? 'selected' : '' }}>
                                        {{ $auditor['name'] }}
                                    </option>
                                @endforeach
                            </select> 

                            @error('auditor_lead_id')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="auditors_ids" class="form-label">Anggota Auditor</label>

                            <select 
                                class="form-select select2-multiple" 
                                name="auditors_ids[]" 
                                multiple
                                required
                            >
                                @foreach ($auditors as $auditor)
                                    <option value="{{ $auditor['id'] }}"
                                        {{ in_array($auditor['id'], old('auditors_ids', [])) ? 'selected' : '' }}>
                                        {{ $auditor['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('auditors_ids')
                                <div class="error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('admin.audit-smp-score.index')}}" class="btn btn-danger"> Kembali</a>
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                    >
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection
@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Single select
    $('.select2-single').select2({
        placeholder: "Pilih Ketua Auditor",
        allowClear: true,
        width: '100%'
    });

    // Multiple select
    $('.select2-multiple').select2({
        placeholder: "Pilih Anggota Auditor",
        width: '100%'
    });
});
</script>
@endsection

