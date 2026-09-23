<a href="{{ route('user.monthly-audit.row.edit', ['monthlyId' => $monthlyId, 'section' => $section, 'rowId' => $rowId]) }}"
   class="btn btn-warning btn-sm">Edit</a>
<button type="button" class="btn btn-danger btn-sm"
        onclick="confirmRowDelete('{{ route('user.monthly-audit.row.destroy', ['monthlyId' => $monthlyId, 'section' => $section, 'rowId' => $rowId]) }}')">Hapus</button>
