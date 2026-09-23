<button type="button" class="btn btn-outline-success btn-sm" style="min-width:90px;"
        onclick="exportAssesment('{{ route('export.assesment', ['assesment' => $assesment->id]) }}')">
    ⬇ Export Excel
</button>
