<table>
    <tr>
        <td colspan="4" style="text-align:center; font-size:14pt; font-weight:bold;">
            Hasil Assesment {{ $assesment->vendor->name ?? '-' }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align:center; font-weight:bold;">
            Triwulan Ke {{ $assesment->triwulan }} Tahun {{ $assesment->year }}
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align:center;">
            Unit: {{ $assesment->unit->name ?? '-' }}
        </td>
    </tr>
    <tr><td colspan="4"></td></tr>

    {{-- ===================== DETAIL PENILAIAN UNIT ===================== --}}
    <tr>
        <td colspan="4" style="text-align:center; font-size:12pt; font-weight:bold;">
            Detail Penilaian Unit per Indikator
        </td>
    </tr>
    <tr><td colspan="4"></td></tr>

    @foreach ($categories as $category)
        <tr>
            <td colspan="4" style="border:1px solid #000; background-color:#4472C4; color:#fff; font-weight:bold;">
                {{ $category->category_name }}
            </td>
        </tr>
        <tr>
            <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;">No</th>
            <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;">Indikator</th>
            <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;">Level Penilaian</th>
            <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;">Nilai</th>
        </tr>
        @foreach ($category->questions as $question)
            <tr>
                <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
                <td style="border:1px solid #000;">{{ $question->indicator }}</td>
                <td style="border:1px solid #000;">
                    @forelse ($question->levels as $level)
                        Level {{ $level->level }}: {{ $level->level_description }}@if(!$loop->last)<br>@endif
                    @empty
                        -
                    @endforelse
                </td>
                <td style="border:1px solid #000; text-align:center;">{{ $question->evaluation_unit ?: '-' }}</td>
            </tr>
        @endforeach
        <tr><td colspan="4"></td></tr>
    @endforeach

    {{-- ===================== AREA GRAFIK ===================== --}}
    <tr>
        <td colspan="4" style="font-weight:bold;">Grafik Hasil Assesment per Proses Bisnis</td>
    </tr>
    @for ($i = 0; $i < 16; $i++)
        <tr><td colspan="4"></td></tr>
    @endfor

    {{-- ===================== RINGKASAN / REPORT ===================== --}}
    <tr>
        <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;">No</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;">Proses Bisnis</th>
        <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center; font-weight:bold;">Nilai</th>
    </tr>

    @foreach ($categories as $category)
        <tr>
            <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
            <td style="border:1px solid #000;">{{ $category->category_name }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $category->average }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="2" style="border:1px solid #000; font-weight:bold; background-color:#DDEBF7;">Skor Maturity</td>
        <td style="border:1px solid #000; text-align:center; font-weight:bold; background-color:#DDEBF7;">
            {{ number_format($categories->avg('average'), 2) }}
        </td>
    </tr>

    <tr><td colspan="4"></td></tr>
    <tr><td colspan="4"></td></tr>

    {{-- TANDA TANGAN --}}
    <tr><td colspan="4" style="height:60px;"></td></tr>
    <tr><td colspan="4" style="height:60px;"></td></tr>
    <tr>
        <td colspan="2" style="text-align:center; font-weight:bold; text-decoration:underline;">{{ $signer1 }}</td>
        <td style="text-align:center; font-weight:bold; text-decoration:underline;">{{ $signer2 }}</td>
    </tr>
</table>
