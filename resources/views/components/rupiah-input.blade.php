{{-- Input nilai rupiah. Tampil berformat (1.234.567), terkirim sebagai angka murni. --}}
@php $rupiahValue = old($name, $value ?? null); @endphp
<div class="input-group">
    <span class="input-group-text">Rp</span>
    <input class="form-control rupiah-input" type="text" name="{{ $name }}" value="{{ $rupiahValue }}"
        placeholder="{{ $placeholder ?? '0' }}" {{ !empty($required) ? 'required' : '' }}>
</div>
