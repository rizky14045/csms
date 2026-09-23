@php $status = (int) ($status ?? 0); @endphp
@if($status === 0)
    <span class="badge bg-secondary">Draft</span>
@elseif($status === 1)
    <span class="badge bg-warning text-dark">Dikirim ke MMRK</span>
@elseif($status === 2)
    <span class="badge bg-info">Menunggu validasi Pusat</span>
@else
    <span class="badge bg-success">Selesai</span>
@endif
