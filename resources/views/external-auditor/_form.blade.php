@php
    $editing = isset($item);
    $selectedIds = collect(old('audit_ids', $selected ?? []))->map(fn($i) => (int) $i)->all();
    $fmt = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d-m-Y') : '-';
    $statusLabel = [0 => 'Draft', 1 => 'Dikirim Unit', 2 => 'Proses Audit', 3 => 'Selesai'];
@endphp

<div class="form-group mb-3">
    <label class="form-label"><span class="text-danger">*</span> Nama</label>
    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" required
           placeholder="Masukan nama auditor" value="{{ old('name', $editing ? $item->user->name : '') }}">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group mb-3">
    <label class="form-label"><span class="text-danger">*</span> Email (Username)</label>
    @if ($editing)
        <input class="form-control" type="email" value="{{ $item->user->email }}" disabled readonly>
    @else
        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" required
               placeholder="Masukan email" value="{{ old('email') }}">
        <div class="form-text">Username dan password akan dikirim ke email ini.</div>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @endif
</div>

<div class="form-group mb-3">
    <label class="form-label"><span class="text-danger">*</span> Tanggal Expired</label>
    <input class="form-control @error('expired_at') is-invalid @enderror" type="date" name="expired_at" required
           @unless($editing) min="{{ date('Y-m-d') }}" @endunless
           value="{{ old('expired_at', $editing ? $item->expired_at->format('Y-m-d') : '') }}">
    <div class="form-text">Setelah tanggal ini auditor tidak bisa login/mengakses lagi.</div>
    @error('expired_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group mb-3">
    <label class="form-label"><span class="text-danger">*</span> Data Audit SMP yang Dapat Dilihat</label>
    <div class="form-text mt-0 mb-1">Hanya data audit yang sudah selesai. Auditor external hanya dapat melihat, tidak dapat mengisi atau mengubah.</div>
    @if ($audits->isEmpty())
        <div class="text-muted">Belum ada data audit SMP yang sudah selesai untuk unit Anda.</div>
    @else
        <div class="border rounded p-2" style="max-height:260px; overflow:auto;">
            @foreach ($audits as $audit)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="audit_ids[]" value="{{ $audit->id }}" id="audit-{{ $audit->id }}"
                           {{ in_array((int) $audit->id, $selectedIds, true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="audit-{{ $audit->id }}">
                        Audit SMP {{ $fmt($audit->start_audit) }} s/d {{ $fmt($audit->end_audit) }}
                        <span class="text-muted">({{ $statusLabel[(int) $audit->status] ?? '-' }})</span>
                    </label>
                </div>
            @endforeach
        </div>
    @endif
    @error('audit_ids') <div class="text-danger small">{{ $message }}</div> @enderror
</div>
