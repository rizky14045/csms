@extends('layout.app')

@section('content')

<div class="py-3 d-flex justify-content-between align-items-center">
    <h4 class="m-0">Tambah Data Assesment</h4>

    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('bujp.assesment.index', ['unit' => request()->query('unit')])}}">Assesment</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
</div>

<div style="display:flex; justify-content:center;">
    <div style="width:100%; max-width:500px;">

        <div class="card">
            <div class="card-body">

                <form action="{{route('bujp.assesment.store', ['unit' => request('unit')])}}"
                      method="POST"
                      id="form-assesment"
                      onsubmit="confirmSave('form-assesment', 'Data akan disimpan')">

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

                    {{-- Triwulan --}}
                    <div class="mb-4">
                        <label class="form-label">Triwulan</label>

                        <select name="triwulan" class="form-select select2" required>
                            <option value="">Pilih Triwulan</option>
                            <option value="1" {{old('triwulan') == '1' ? 'selected' : ''}}>Triwulan 1</option>
                            <option value="2" {{old('triwulan') == '2' ? 'selected' : ''}}>Triwulan 2</option>
                            <option value="3" {{old('triwulan') == '3' ? 'selected' : ''}}>Triwulan 3</option>
                            <option value="4" {{old('triwulan') == '4' ? 'selected' : ''}}>Triwulan 4</option>
                        </select>

                        @error('triwulan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <div style="display:flex; justify-content:flex-end; gap:10px;">
                        <a href="{{route('bujp.assesment.index', ['unit' => request()->query('unit')])}}"
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