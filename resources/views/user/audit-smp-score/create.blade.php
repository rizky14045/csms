@extends('layout.app')

@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }

    #map {
        height: 500px;
        width: 100%;
    }

    .form-wrapper {
        display: flex;
        justify-content: center;
    }

    .form-container {
        width: 100%;
        max-width: 600px;
    }

    .error-text {
        font-size: 12px;
        color: #dc3545;
        margin-top: 4px;
    }
</style>
@stop

@section('content')

<div class="py-3 d-flex justify-content-between align-items-center">
    <h4 class="m-0">Tambah Data Audit SMP</h4>

    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}">Dashboard</a>
        </li>

        <li class="breadcrumb-item">
            <a href="{{route('user.audit-smp-score.index')}}">Audit SMP</a>
        </li>

        <li class="breadcrumb-item active">
            Create
        </li>
    </ol>
</div>

<div class="form-wrapper">

    <div class="form-container">

        <div class="card">
            <div class="card-body">

                <form action="{{route('user.audit-smp-score.store')}}"
                      method="POST"
                      id="form-audit"
                      onsubmit="confirmSave('form-audit', 'Data audit akan disimpan')">

                    @csrf

                    {{-- START AUDIT --}}
                    <div class="mb-3">
                        <label for="start_audit" class="form-label">
                            Tanggal Mulai Audit
                        </label>

                        <input type="date"
                               id="start_audit"
                               name="start_audit"
                               class="form-control"
                               value="{{old('start_audit')}}"
                               required>

                        @error('start_audit')
                            <div class="error-text">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- END AUDIT --}}
                    <div class="mb-4">
                        <label for="end_audit" class="form-label">
                            Tanggal Selesai Audit
                        </label>

                        <input type="date"
                               id="end_audit"
                               name="end_audit"
                               class="form-control"
                               value="{{old('end_audit')}}"
                               required>

                        @error('end_audit')
                            <div class="error-text">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <div style="display:flex; justify-content:flex-end; gap:10px;">

                        <a href="{{route('user.audit-smp-score.index')}}"
                           class="btn btn-outline-danger">
                            ← Kembali
                        </a>

                        <button type="submit"
                                class="btn btn-success">
                            💾 Simpan
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection