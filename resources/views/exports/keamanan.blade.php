<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
    * { font-family: Arial, sans-serif; font-size: 10pt; }
    table { border-collapse: collapse; }
    td, th { border: 1px solid #4472C4; vertical-align: middle; padding: 4px 6px; }
    .title    { background-color:#1F3864; color:#FFFFFF; font-size:13pt; font-weight:bold; text-align:center; border:none; }
    .subtitle { background-color:#2E75B6; color:#FFFFFF; font-size:10pt; text-align:center; border:none; }
    .area-header { background-color:#C00000; color:#FFFFFF; font-weight:bold; font-size:11pt; }
    .col-header  { background-color:#2E75B6; color:#FFFFFF; font-weight:bold; text-align:center; }
    .text-center { text-align:center; }
    .text-bold   { font-weight:bold; }
    .val-top     { vertical-align:top; }
    .total-row   { background-color:#1F3864; color:#FFFFFF; font-weight:bold; text-align:center; }
    .even-row    { background-color:#DEEAF1; }
    .odd-row     { background-color:#FFFFFF; }
    .num         { text-align:center; }
</style>
</head>
<body>
@php $grandML = 0; @endphp

<table>
    <colgroup>
        <col style="width:35pt;">   {{-- No --}}
        <col style="width:130pt;">  {{-- Sub Area --}}
        <col style="width:50pt;">   {{-- Level --}}
        <col style="width:180pt;">  {{-- Uraian --}}
        <col style="width:80pt;">   {{-- File Evidence --}}
        <col style="width:55pt;">   {{-- Bobot --}}
        <col style="width:70pt;">   {{-- Hasil Assesment --}}
        <col style="width:65pt;">   {{-- Score ML --}}
    </colgroup>

    <tr style="height:28pt;">
        <td colspan="8" class="title">LAPORAN KPI KEAMANAN — TAHUN {{ $kpi->year }} SEMESTER {{ $kpi->semester }}</td>
    </tr>
    <tr style="height:14pt;">
        <td colspan="8" class="subtitle">{{ optional($kpi->unit)->name ?? '' }}</td>
    </tr>
    <tr><td colspan="8" style="border:none; height:6pt;"></td></tr>

    @foreach ($areas as $area)

    <tr style="height:18pt;">
        <td colspan="8" class="area-header">&nbsp;{{ $area['name'] }}</td>
    </tr>
    <tr style="height:32pt;">
        <th class="col-header">No</th>
        <th class="col-header">Sub Area</th>
        <th class="col-header">Level</th>
        <th class="col-header">Uraian</th>
        <th class="col-header">File Evidence</th>
        <th class="col-header">Bobot</th>
        <th class="col-header">Hasil Assesment</th>
        <th class="col-header">Score ML</th>
    </tr>

    @foreach ($area['sub_areas'] as $subIdx => $subArea)
    @php
        $levels     = $subArea['levels'];
        $levelCount = count($levels);
        $uploaded   = collect($levels)->filter(fn($l) => !empty($l['attachment_file']))->count();
        $hasil      = $levelCount > 0 ? round($uploaded / $levelCount, 4) : 0;
        $scoreML    = round($hasil * $bobot, 4);
        $grandML   += $scoreML;
        $rowClass   = ($subIdx % 2 === 0) ? 'even-row' : 'odd-row';
    @endphp

    @foreach ($levels as $idx => $level)
    <tr class="{{ $rowClass }}">
        @if ($idx === 0)
        <td class="text-center val-top text-bold" rowspan="{{ $levelCount }}">{{ $subIdx + 1 }}</td>
        <td class="val-top text-bold" rowspan="{{ $levelCount }}" style="white-space:normal;">
            {{ $subArea['name'] }}
            @if(!empty($subArea['description']))
            <div style="font-weight:normal; font-size:9pt; color:#444;">{{ $subArea['description'] }}</div>
            @endif
            @if(!empty($subArea['reference']))
            <div style="font-weight:normal; font-size:9pt; color:#888;">Ref: {{ $subArea['reference'] }}</div>
            @endif
        </td>
        @endif

        <td class="text-center">{{ $level['level'] }}</td>
        <td style="white-space:normal;">{{ $level['description'] }}</td>
        <td class="text-center">{{ !empty($level['attachment_file']) ? '✔ Ada' : '-' }}</td>

        @if ($idx === 0)
        <td class="num text-bold" rowspan="{{ $levelCount }}">{{ round($bobot, 4) }}</td>
        <td class="num text-bold" rowspan="{{ $levelCount }}">{{ $hasil }}</td>
        <td class="num text-bold" rowspan="{{ $levelCount }}">{{ $scoreML }}</td>
        @endif
    </tr>
    @endforeach

    @endforeach

    <tr><td colspan="8" style="border:none; height:6pt;"></td></tr>

    @endforeach

    <tr style="height:18pt;">
        <td colspan="5" class="total-row">TOTAL KESELURUHAN</td>
        <td class="total-row num">{{ round($bobot * $totalSubAreas, 4) }}</td>
        <td class="total-row text-center">ML</td>
        <td class="total-row num">{{ round($grandML, 4) }}</td>
    </tr>
</table>
</body>
</html>
