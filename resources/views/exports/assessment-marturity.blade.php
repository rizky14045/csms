@php
    $isFa = $mode === 'fa';
    $totalSubAreas = collect($areas)->sum(fn($a) => count($a['sub_areas']));
    $bobot = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;
    $cols = $isFa ? 14 : 10;
    $grandSa = 0;
    $th = 'border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;';
    $td = 'border:1px solid #000;';
    $tdc = 'border:1px solid #000; text-align:center;';
@endphp
<table>
    <tr>
        <td colspan="{{ $cols }}" style="text-align:center; font-size:14pt; font-weight:bold;">
            {{ $isFa ? 'FINAL ASSESSMENT (FA)' : 'SELF ASSESSMENT (SA)' }} MATURITY
        </td>
    </tr>
    <tr>
        <td colspan="{{ $cols }}" style="text-align:center; font-weight:bold;">
            {{ $marturity->unit->name ?? '-' }} - Tahun {{ $marturity->year }} - {{ $marturity->period_label }}
        </td>
    </tr>
    <tr><td colspan="{{ $cols }}"></td></tr>

    @foreach ($areas as $area)
        <tr>
            <td colspan="{{ $cols }}" style="{{ $td }} background-color:#4472C4; color:#fff; font-weight:bold;">{{ $area['name'] }}</td>
        </tr>
        <tr>
            <th style="{{ $th }}">No</th>
            <th style="{{ $th }}">Sub Area</th>
            <th style="{{ $th }}">Level</th>
            <th style="{{ $th }}">Uraian</th>
            <th style="{{ $th }}">Note / Evidence</th>
            <th style="{{ $th }}">Total Evidence</th>
            <th style="{{ $th }}">Jumlah Evidence (SA)</th>
            <th style="{{ $th }}">Bobot</th>
            <th style="{{ $th }}">Hasil SA</th>
            <th style="{{ $th }}">Score ML SA</th>
            @if ($isFa)
                <th style="{{ $th }}">Evidence Tervalidasi Pusat</th>
                <th style="{{ $th }}">Catatan Validasi Pusat</th>
                <th style="{{ $th }}">Hasil FA</th>
                <th style="{{ $th }}">Score ML FA</th>
            @endif
        </tr>

        @foreach ($area['sub_areas'] as $subIdx => $subArea)
            @php
                $levels = $subArea['levels'];
                $levelCount = max(1, count($levels));
                $hasilSa = 0;
                foreach ($levels as $lvl) {
                    $files = json_decode($lvl['attachment_files'] ?? '[]', true) ?: [];
                    $totalEv = max(1, (int) ($lvl['total_evidence'] ?? 1));
                    $hasilSa += round(count($files) / $totalEv, 4);
                }
                $hasilSa = round($hasilSa, 4);
                $scoreSa = round($hasilSa * $bobot, 4);
                $grandSa += $scoreSa;
                $subActual = $isFa ? ($actual['subAreas'][$subArea['id']] ?? null) : null;
            @endphp

            @foreach ($levels as $idx => $lvl)
                @php
                    $files = json_decode($lvl['attachment_files'] ?? '[]', true) ?: [];
                    $lvlActual = $subActual['levels'][$lvl['id']] ?? null;
                @endphp
                <tr>
                    @if ($idx === 0)
                        <td rowspan="{{ $levelCount }}" style="{{ $tdc }}">{{ $subIdx + 1 }}</td>
                        <td rowspan="{{ $levelCount }}" style="{{ $td }} vertical-align:top;">{{ $subArea['name'] }}</td>
                    @endif
                    <td style="{{ $tdc }}">{{ $lvl['level'] }}</td>
                    <td style="{{ $td }}">{{ $lvl['description'] }}</td>
                    <td style="{{ $td }}">
                        @foreach (($lvl['notes'] ?? []) as $noteRow)
                            {{ $noteRow['note'] ?? '' }}@if(!$loop->last)<br><br>@endif
                        @endforeach
                    </td>
                    <td style="{{ $tdc }}">{{ (int) ($lvl['total_evidence'] ?? 1) }}</td>
                    <td style="{{ $tdc }}">{{ count($files) }}</td>
                    @if ($idx === 0)
                        <td rowspan="{{ $levelCount }}" style="{{ $tdc }}">{{ round($bobot, 4) }}</td>
                        <td rowspan="{{ $levelCount }}" style="{{ $tdc }}">{{ $hasilSa }}</td>
                        <td rowspan="{{ $levelCount }}" style="{{ $tdc }}">{{ $scoreSa }}</td>
                    @endif
                    @if ($isFa)
                        <td style="{{ $tdc }}">{{ $lvlActual['checkedCount'] ?? 0 }}</td>
                        <td style="{{ $td }}">{{ $lvl['validation_note'] ?? '' }}</td>
                        @if ($idx === 0)
                            <td rowspan="{{ $levelCount }}" style="{{ $tdc }}">{{ $subActual['hasil'] ?? 0 }}</td>
                            <td rowspan="{{ $levelCount }}" style="{{ $tdc }}">{{ $subActual['score'] ?? 0 }}</td>
                        @endif
                    @endif
                </tr>
            @endforeach
        @endforeach
        <tr><td colspan="{{ $cols }}"></td></tr>
    @endforeach

    <tr>
        <td colspan="{{ $isFa ? 13 : 9 }}" style="{{ $td }} font-weight:bold; background-color:#DDEBF7;">Total Score ML Self Assessment (SA)</td>
        <td style="{{ $tdc }} font-weight:bold; background-color:#DDEBF7;">{{ round($grandSa, 4) }}</td>
    </tr>
    @if ($isFa)
        <tr>
            <td colspan="13" style="{{ $td }} font-weight:bold; background-color:#DDEBF7;">Total Score ML Final Assessment (FA, hasil validasi Pusat)</td>
            <td style="{{ $tdc }} font-weight:bold; background-color:#DDEBF7;">{{ round($actual['total'] ?? 0, 4) }}</td>
        </tr>
    @endif
</table>
