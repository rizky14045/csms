@php $v = fn($k) => old($k, isset($item) ? $item->{$k} : null); @endphp

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
    @include('components.rupiah-input', ['name' => 'jumlah_anggaran', 'value' => isset($item) ? $item->jumlah_anggaran : null, 'required' => true, 'placeholder' => 'Masukan Jumlah Anggaran'])
    @error('jumlah_anggaran') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Penyerapan Anggaran <span class="text-muted">(opsional)</span></label>
    @include('components.rupiah-input', ['name' => 'penyerapan_anggaran', 'value' => isset($item) ? $item->penyerapan_anggaran : null, 'placeholder' => 'Masukan Penyerapan Anggaran'])
    @error('penyerapan_anggaran') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
<div class="form-group mb-3">
    <label class="form-label">Keterangan <span class="text-muted">(opsional)</span></label>
    <input class="form-control" type="text" name="keterangan" placeholder="Masukan keterangan" value="{{ $v('keterangan') }}">
    @error('keterangan') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
