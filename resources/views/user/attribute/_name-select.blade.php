{{-- Nama attribute: pilih dari daftar predefined atau ketik nama baru (nama baru hanya disimpan di attribute unit ini). --}}
@php
    $current = old('name', $current ?? null);
    $options = collect($presets ?? [])->all();
    if ($current !== null && $current !== '' && !collect($options)->contains(fn($o) => mb_strtolower($o) === mb_strtolower($current))) {
        $options[] = $current;
    }
@endphp
<div class="form-group mb-3">
    <label for="name" class="form-label">Nama</label>
    <select class="form-select @error('name') is-invalid @enderror" id="name" name="name" required>
        <option value=""></option>
        @foreach ($options as $option)
            <option value="{{ $option }}" {{ $current !== null && mb_strtolower($current) === mb_strtolower($option) ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
    <div class="form-text">Pilih dari daftar, atau ketik nama baru lalu tekan Enter jika tidak ada di daftar.</div>
    @error('name')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
