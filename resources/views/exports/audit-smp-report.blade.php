<table>
    <tr>
        <td colspan="8" style="text-align:center; font-size:14pt; font-weight:bold;">
            Hasil Audit SMP {{ $audit->unit->name ?? '-' }}
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:center;">
            Periode Audit: {{ \Carbon\Carbon::parse($audit->start_audit)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($audit->end_audit)->format('d-m-Y') }}
        </td>
    </tr>
    <tr><td colspan="8"></td></tr>

    <tr>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Elemen</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Bobot</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">No</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Kriteria</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Nilai Self</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Elemen Self</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Nilai Audit</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Elemen Audit</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Evidence</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Temuan</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Rekomendasi</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">Due Date</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; font-weight:bold;">PIC</th>
    </tr>

    @php
        $grandTotalAudit = 0;
        $grandTotalSelf = 0;
    @endphp

    @foreach ($audit->childrenHeader as $header)
        @php
            $allKriteria = collect($header->kriteria);
            foreach ($header->pernyataan as $p) {
                $allKriteria = $allKriteria->merge($p->kriteria);
            }
            $pembagi = max(1, $allKriteria->count() * 2);
            $subAudit = 0;
            $subSelf = 0;
        @endphp

        @foreach ($allKriteria as $index => $kriteria)
            @php
                $nilaiSelf = (int) ($kriteria->pencapaian_nilai_kriteria_self ?? 0);
                $nilaiAudit = (int) ($kriteria->pencapaian_nilai_kriteria ?? 0);
                $nilaiElemenSelf = ($nilaiSelf * $header->bobot) / $pembagi;
                $nilaiElemenAudit = ($nilaiAudit * $header->bobot) / $pembagi;
                $subSelf += $nilaiElemenSelf;
                $subAudit += $nilaiElemenAudit;
                $evidences = $kriteria->evidence->count() ? $kriteria->evidence : collect([null]);
            @endphp

            @foreach ($evidences as $i => $evidence)
                <tr>
                    <td style="border:1px solid #000;">{{ $i === 0 && $index === 0 ? $header->name : '' }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $i === 0 && $index === 0 ? $header->bobot . '%' : '' }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $i === 0 ? $index + 1 : '' }}</td>
                    <td style="border:1px solid #000;">{{ $i === 0 ? $kriteria->name : '' }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $i === 0 ? $nilaiSelf : '' }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $i === 0 ? number_format($nilaiElemenSelf, 2) . '%' : '' }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $i === 0 ? $nilaiAudit : '' }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $i === 0 ? number_format($nilaiElemenAudit, 2) . '%' : '' }}</td>
                    <td style="border:1px solid #000;">{{ $evidence->name ?? '-' }}</td>
                    <td style="border:1px solid #000;">{{ $evidence->temuan ?? '-' }}</td>
                    <td style="border:1px solid #000;">{{ $evidence->rekomendasi ?? '-' }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ isset($evidence->due_date) ? \Carbon\Carbon::parse($evidence->due_date)->format('d-m-Y') : '-' }}</td>
                    <td style="border:1px solid #000;">{{ $evidence->pic ?? '-' }}</td>
                </tr>
            @endforeach
        @endforeach

        <tr>
            <td colspan="4" style="border:1px solid #000; font-weight:bold; background-color:#DDEBF7;">SubTotal Elemen ({{ $header->name }})</td>
            <td style="border:1px solid #000;"></td>
            <td style="border:1px solid #000; text-align:center; font-weight:bold; background-color:#DDEBF7;">{{ number_format($subSelf, 2) }}%</td>
            <td style="border:1px solid #000;"></td>
            <td style="border:1px solid #000; text-align:center; font-weight:bold; background-color:#DDEBF7;">{{ number_format($subAudit, 2) }}%</td>
            <td colspan="5" style="border:1px solid #000;"></td>
        </tr>

        @php
            $grandTotalAudit += $subAudit;
            $grandTotalSelf += $subSelf;
        @endphp
    @endforeach

    <tr>
        <td colspan="4" style="border:1px solid #000; font-weight:bold; background-color:#4472C4; color:#fff;">TOTAL</td>
        <td style="border:1px solid #000;"></td>
        <td style="border:1px solid #000; text-align:center; font-weight:bold; background-color:#4472C4; color:#fff;">{{ number_format($grandTotalSelf, 2) }}%</td>
        <td style="border:1px solid #000;"></td>
        <td style="border:1px solid #000; text-align:center; font-weight:bold; background-color:#4472C4; color:#fff;">{{ number_format($grandTotalAudit, 2) }}%</td>
        <td colspan="5" style="border:1px solid #000;"></td>
    </tr>

    @php
        $kategoriFor = fn($v) => $v < 55 ? 'Kurang' : ($v <= 70 ? 'Cukup' : ($v <= 85 ? 'Baik' : 'Baik Sekali'));
    @endphp

    <tr>
        <td colspan="4" style="border:1px solid #000; font-weight:bold; background-color:#4472C4; color:#fff;">KATEGORI</td>
        <td style="border:1px solid #000;"></td>
        <td style="border:1px solid #000; text-align:center; font-weight:bold;">{{ $kategoriFor($grandTotalSelf) }}</td>
        <td style="border:1px solid #000;"></td>
        <td style="border:1px solid #000; text-align:center; font-weight:bold;">{{ $kategoriFor($grandTotalAudit) }}</td>
        <td colspan="5" style="border:1px solid #000;"></td>
    </tr>
</table>
