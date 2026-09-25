{{-- Tombol Sanggah (Unit): tampil hanya selama masa sanggah 7 hari dan belum pernah disanggah.
     $item bisa model atau objek biasa hasil query, jadi dihitung dari atributnya. --}}
@php
    $rebuttalDeadline = !empty($item->validated_at) ? \Carbon\Carbon::parse($item->validated_at)->addDays(7)->endOfDay() : null;
    $canRebut = (int) ($item->status ?? 0) === 3
        && (int) ($item->rebuttal_state ?? 0) === 0
        && $rebuttalDeadline
        && now()->lte($rebuttalDeadline);
@endphp
@if ($canRebut)
    @php $formId = 'rebuttal-' . $kind . '-' . $item->id; @endphp
    <form id="{{ $formId }}" action="{{ route('user.' . ($kind === 'kpi' ? 'keamanan' : 'marturity') . '.rebuttal', [$kind === 'kpi' ? 'kpi' : 'marturity' => $item->id]) }}" method="POST" style="margin:0;"
          onsubmit="return confirmAction('{{ $formId }}', 'Ajukan sanggahan?', 'Sanggahan hanya dapat dilakukan satu kali. Hanya data yang belum divalidasi Pusat yang dapat diubah. Batas sanggah: {{ $rebuttalDeadline->format('d-m-Y') }}.', 'Ya, Sanggah')">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm" style="min-width:80px;" title="Batas sanggah {{ $rebuttalDeadline->format('d-m-Y') }}">
            ⚖ Sanggah
        </button>
    </form>
@endif
