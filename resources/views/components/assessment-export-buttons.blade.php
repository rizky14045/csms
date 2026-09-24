@php $exportStatus = (int) ($item->status ?? 0); @endphp
@if($exportStatus >= 1)
    <a href="{{ route('export.' . $kind, [$kind => $item->id, 'type' => 'sa']) }}" class="btn btn-outline-success btn-sm">📥 Export SA</a>
@endif
@if($exportStatus === 3)
    <a href="{{ route('export.' . $kind, [$kind => $item->id, 'type' => 'fa']) }}" class="btn btn-success btn-sm">📥 Export FA</a>
@endif
