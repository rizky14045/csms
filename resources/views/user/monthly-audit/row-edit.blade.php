@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Laporan Bulanan</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit {{ $meta['title'] }}</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('user.monthly-audit.row.update', ['monthlyId' => $monthlyId, 'section' => $section, 'rowId' => $rowId]) }}"
              method="POST" id="form-row-edit" class="my-3">
            @csrf
            @method('PATCH')

            <div class="alert alert-info">
                Perubahan hanya berlaku pada laporan bulanan ini. Centang opsi di bawah untuk memperbarui master data juga.
            </div>

            @foreach ($meta['fields'] as $field)
                <div class="mb-3">
                    <label class="form-label">{{ $field['label'] }}</label>
                    @php $value = old($field['name'], $item->{$field['name']}); @endphp

                    @if ($field['type'] === 'select')
                        <select name="{{ $field['name'] }}" class="form-select @error($field['name']) is-invalid @enderror">
                            <option value="">Pilih {{ $field['label'] }}</option>
                            @foreach ($field['options'] as $option)
                                <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif ($field['type'] === 'date')
                        <input type="date" name="{{ $field['name'] }}" class="form-control @error($field['name']) is-invalid @enderror"
                               value="{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '' }}">
                    @else
                        <input type="text" name="{{ $field['name'] }}" class="form-control @error($field['name']) is-invalid @enderror"
                               value="{{ $value }}">
                    @endif

                    @error($field['name'])
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="save_to_master" value="1" id="save_to_master"
                       {{ old('save_to_master') ? 'checked' : '' }}>
                <label class="form-check-label" for="save_to_master">
                    Simpan juga ke master data
                    @if(!$hasSource)
                        <span class="text-muted">(data ini khusus laporan, akan dibuat sebagai master data baru)</span>
                    @endif
                </label>
            </div>

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route($meta['back'], ['monthlyId' => $monthlyId]) }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection
