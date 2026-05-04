@extends('layout.app')

@section('content')

<div class="py-3 d-flex justify-content-between align-items-center">
    <h4 class="m-0">Tambah Data Maturity</h4>

    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('user.marturity.index')}}">Maturity</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
</div>

<div style="display:flex; justify-content:center;">
    <div style="width:100%; max-width:500px;">

        <div class="card">
            <div class="card-body">

                <form action="{{route('user.marturity.store')}}"
                      method="POST"
                      id="form-marturity"
                      onsubmit="confirmSave('form-marturity', 'Data maturity akan disimpan')">

                    @csrf

                    {{-- Tahun --}}
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>

                        <select name="year" class="form-select select2" required>
                            <option value="">Pilih Tahun</option>

                            @for ($i = date('Y'); $i >= 2010; $i--)
                                <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>

                        @error('year')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Semester --}}
                    <div class="mb-4">
                        <label class="form-label">Semester</label>

                        <select name="semester" class="form-select select2" required>
                            <option value="">Pilih Semester</option>
                            <option value="1" {{old('semester') == '1' ? 'selected' : ''}}>Semester 1</option>
                            <option value="2" {{old('semester') == '2' ? 'selected' : ''}}>Semester 2</option>
                        </select>

                        @error('semester')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <div style="display:flex; justify-content:flex-end; gap:10px;">
                        <a href="{{route('user.marturity.index')}}"
                           class="btn btn-outline-danger">
                            ← Kembali
                        </a>

                        <button type="submit" class="btn btn-success">
                            💾 Simpan
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
document.addEventListener('DOMContentLoaded', function () {

    $('.select2').select2({
        width: '100%',
        placeholder: "Pilih",
        allowClear: true
    });

});
</script>
@stop