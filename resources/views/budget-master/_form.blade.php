@php $v = fn($k) => old($k, isset($item) ? $item->{$k} : null); @endphp

@if (!empty($units) && $units->isNotEmpty())
    <div class="form-group mb-3">
        <label class="form-label">Unit</label>
        <select name="unit_id" class="form-select" required>
            <option value="">-- Pilih Unit --</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
            @endforeach
        </select>
        @error('unit_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
@endif

<div class="form-group mb-3">
    <label class="form-label">Jenis Anggaran</label>
    <select name="type" class="form-select">
        <option value="pemeliharaan" {{ $v('type') == 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan</option>
        <option value="administrasi" {{ $v('type') == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
    </select>
    @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Kode Aktifitas</label>
    <input class="form-control" type="text" name="kode_aktifitas" required placeholder="Masukan Kode Aktifitas" value="{{ $v('kode_aktifitas') }}">
    @error('kode_aktifitas') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Kode PRK</label>
    <input class="form-control" type="text" name="kode_prk" required placeholder="Masukan Kode PRK" value="{{ $v('kode_prk') }}">
    @error('kode_prk') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Deskripsi Kegiatan</label>
    <textarea name="deskripsi_kegiatan" rows="4" class="form-control" required>{{ $v('deskripsi_kegiatan') }}</textarea>
    @error('deskripsi_kegiatan') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Jumlah Anggaran</label>
    <input class="form-control" type="number" step="any" min="0" name="jumlah_anggaran" required placeholder="Masukan Jumlah Anggaran" value="{{ $v('jumlah_anggaran') }}">
    @error('jumlah_anggaran') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Penyerapan Anggaran <span class="text-muted">(opsional)</span></label>
    <input class="form-control" type="number" step="any" min="0" name="penyerapan_anggaran" placeholder="Masukan Penyerapan Anggaran" value="{{ $v('penyerapan_anggaran') }}">
    @error('penyerapan_anggaran') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Keterangan <span class="text-muted">(opsional)</span></label>
    <input class="form-control" type="text" name="keterangan" placeholder="Masukan keterangan" value="{{ $v('keterangan') }}">
    @error('keterangan') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
