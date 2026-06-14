<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
    * { font-family: Arial, sans-serif; font-size: 10pt; }

    table { border-collapse: collapse; }

    td, th {
        border: 1px solid #4472C4;
        vertical-align: middle;
        padding: 4px 6px;
        mso-number-format: "\@";
    }

    .title {
        background-color: #1F3864;
        color: #FFFFFF;
        font-size: 13pt;
        font-weight: bold;
        text-align: center;
        border: none;
    }

    .subtitle {
        background-color: #2E75B6;
        color: #FFFFFF;
        font-size: 10pt;
        text-align: center;
        border: none;
    }

    .area-header {
        background-color: #C00000;
        color: #FFFFFF;
        font-weight: bold;
        font-size: 11pt;
    }

    .col-header {
        background-color: #2E75B6;
        color: #FFFFFF;
        font-weight: bold;
        text-align: center;
    }

    .text-center { text-align: center; }
    .text-bold   { font-weight: bold; }
    .val-top     { vertical-align: top; }

    .total-row {
        background-color: #1F3864;
        color: #FFFFFF;
        font-weight: bold;
        text-align: center;
    }

    .even-row { background-color: #DEEAF1; }
    .odd-row  { background-color: #FFFFFF; }
    .num      { mso-number-format: "0.0000"; text-align: center; }
</style>
</head>
<body>
@php
    $grandML = 0;
@endphp

<table>
    {{-- Column widths --}}
    <colgroup>
        <col style="width: 35pt;">   {{-- No --}}
        <col style="width: 130pt;">  {{-- Sub Area --}}
        <col style="width: 55pt;">   {{-- Level --}}
        <col style="width: 180pt;">  {{-- Uraian --}}
        <col style="width: 65pt;">   {{-- Total Evidence --}}
        <col style="width: 65pt;">   {{-- Jumlah Evidence --}}
        <col style="width: 55pt;">   {{-- Bobot --}}
        <col style="width: 55pt;">   {{-- Hasil --}}
        <col style="width: 60pt;">   {{-- Score ML --}}
    </colgroup>

    {{-- Title --}}
    <tr style="height: 28pt;">
        <td colspan="9" class="title">
            LAPORAN MATURITY — TAHUN {{ $marturity->year }} SEMESTER {{ $marturity->semester }}
        </td>
    </tr>
    <tr style="height: 14pt;">
        <td colspan="9" class="subtitle">
            {{ optional($marturity->unit)->name ?? '' }}
        </td>
    </tr>
    <tr><td colspan="9" style="border:none; height:6pt;"></td></tr>

    @foreach ($areas as $areaIdx => $area)

    {{-- Area header --}}
    <tr style="height:18pt;">
        <td colspan="9" class="area-header">&nbsp;{{ $area['name'] }}</td>
    </tr>

    {{-- Column headers --}}
    <tr style="height:32pt;">
        <th class="col-header" style="width:35pt;">No</th>
        <th class="col-header" style="width:130pt;">Sub Area</th>
        <th class="col-header" style="width:55pt;">Level</th>
        <th class="col-header" style="width:180pt;">Uraian</th>
        <th class="col-header" style="width:65pt;">Total Evidence</th>
        <th class="col-header" style="width:65pt;">Jumlah Evidence</th>
        <th class="col-header" style="width:55pt;">Bobot</th>
        <th class="col-header" style="width:55pt;">Hasil</th>
        <th class="col-header" style="width:60pt;">Score ML</th>
    </tr>

    @foreach ($area['sub_areas'] as $subIdx => $subArea)
    @php
        $levels     = $subArea['levels'];
        $levelCount = count($levels);

        $levelCalcs = [];
        foreach ($levels as $lvl) {
            $files   = json_decode($lvl['attachment_files'] ?? '[]', true) ?: [];
            $jumlah  = count($files);
            $totalEv = max(1, (int)($lvl['total_evidence'] ?? 1));
            $calc    = round($jumlah / $totalEv, 4);
            $levelCalcs[] = compact('lvl', 'files', 'jumlah', 'totalEv', 'calc');
        }

        $hasil   = round(array_sum(array_column($levelCalcs, 'calc')), 4);
        $scoreML = round($hasil * $bobot, 4);
        $grandML += $scoreML;

        $rowClass = ($subIdx % 2 === 0) ? 'even-row' : 'odd-row';
        $no = $subIdx + 1;
    @endphp

    @foreach ($levelCalcs as $idx => $lc)
    <tr class="{{ $rowClass }}">
        @if ($idx === 0)
        <td class="text-center val-top text-bold" rowspan="{{ $levelCount }}">{{ $no }}</td>
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

        <td class="text-center">{{ $lc['lvl']['level'] }}</td>
        <td style="white-space:normal;">{{ $lc['lvl']['description'] }}</td>
        <td class="text-center">{{ $lc['totalEv'] }}</td>
        <td class="text-center">{{ $lc['jumlah'] }}</td>

        @if ($idx === 0)
        <td class="num text-bold" rowspan="{{ $levelCount }}">{{ round($bobot, 4) }}</td>
        <td class="num text-bold" rowspan="{{ $levelCount }}">{{ $hasil }}</td>
        <td class="num text-bold" rowspan="{{ $levelCount }}">{{ $scoreML }}</td>
        @endif
    </tr>
    @endforeach

    @endforeach

    {{-- Spacer --}}
    <tr><td colspan="9" style="border:none; height:6pt;"></td></tr>

    @endforeach

    {{-- Grand Total --}}
    <tr style="height:18pt;">
        <td colspan="6" class="total-row">TOTAL KESELURUHAN</td>
        <td class="total-row num">{{ round($bobot * $totalSubAreas, 4) }}</td>
        <td class="total-row text-center">ML</td>
        <td class="total-row num">{{ round($grandML, 4) }}</td>
    </tr>
</table>

</body>
</html>
