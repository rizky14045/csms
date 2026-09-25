@php
    $status = (int) ($status ?? 0);
    $rebuttal = (int) ($rebuttal ?? 0);
@endphp
@if($rebuttal > 0)
    {{-- Tahap sanggahan (status memakai alur biasa: 0 draft, 1 MMRK, 2 Pusat, 3 selesai) --}}
    @if($status === 0)
        <span class="badge bg-secondary">Sanggahan (Unit menyusun)</span>
    @elseif($status === 1)
        <span class="badge bg-warning text-dark">Sanggahan dikirim ke MMRK</span>
    @elseif($status === 2)
        <span class="badge bg-info">Sanggahan menunggu validasi Pusat</span>
    @else
        <span class="badge bg-success">Selesai sanggah</span>
    @endif
@elseif($status === 0)
    <span class="badge bg-secondary">Draft</span>
@elseif($status === 1)
    <span class="badge bg-warning text-dark">Dikirim ke MMRK</span>
@elseif($status === 2)
    <span class="badge bg-info">Menunggu validasi Pusat</span>
@else
    <span class="badge bg-success">Selesai</span>
@endif
