@php

$months = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

@endphp

<table>

    <!-- HEADER -->
    <tr>

        <td colspan="42"></td>

        <td colspan="10"
            style="border:1px solid #000; font-weight:bold;">
            No Dokumen : 
        </td>

    </tr>

    <tr>

        <td colspan="42"
            style="
                text-align:center;
                font-size:14pt;
                font-weight:bold;
            ">
            PT PLN NUSANTARA POWER
        </td>

        <td colspan="10"
            style="border:1px solid #000;">
            Bulan : {{ \Carbon\Carbon::parse($monthlyReport->report_date)->format('F Y') }}
        </td>

    </tr>

    <tr>

        <td colspan="42"
            style="
                text-align:center;
                font-weight:bold;
            ">
            FORMULIR PROGRAM KEAMANAN
        </td>

        <td colspan="10"
            style="border:1px solid #000;">
            Revisi : 00
        </td>

    </tr>

    <tr>

        <td colspan="42"></td>

        <td colspan="10"
            style="border:1px solid #000;">
            Halaman : 1 dari 1
        </td>

    </tr>

</table>

<br>

@foreach($programs as $group)

<table>

    <!-- PROGRAM TITLE -->
    <tr>

        <td colspan="52"
            style="
                border:1px solid #000;
                background:#D9EAD3;
                font-weight:bold;
                text-align:center;
            ">
            {{ $group->securityProgram->program_name }}
        </td>

    </tr>

    <!-- HEADER -->
    <tr>

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                background:#D9EAD3;
            ">
            No
        </th>

        <th rowspan="2"
            colspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                background:#D9EAD3;
            ">
            Program
        </th>

        @foreach($months as $month)

            <th colspan="4"
                style="
                    border:1px solid #000;
                    text-align:center;
                    background:#D9EAD3;
                ">
                {{ $month }}
            </th>

        @endforeach

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                background:#D9EAD3;
            ">
            Keterangan
        </th>

    </tr>

    <!-- WEEK -->
    <tr>

        @foreach(range(1,12) as $month)

            @foreach(range(1,4) as $week)

                <th style="
                    border:1px solid #000;
                    text-align:center;
                    background:#D9EAD3;
                ">
                    {{ $week }}
                </th>

            @endforeach

        @endforeach

    </tr>

    <!-- PROGRAM DATA -->
    @foreach($group->programs as $index => $program)

    @php

        $planningStartMonth = array_search(
            $program->mainProgram->start_month,
            $months
        );

        $planningEndMonth = array_search(
            $program->mainProgram->end_month,
            $months
        );

        $realisasiStartMonth = array_search(
            $program->start_month,
            $months
        );

        $realisasiEndMonth = array_search(
            $program->end_month,
            $months
        );

    @endphp

    <!-- PLANNING -->
    <tr>

        <td rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
            ">
            {{ $index + 1 }}
        </td>

        <td rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
            ">
            {{ $program->mainProgram->program_name }}
        </td>

        <td style="
                border:1px solid #000;
                text-align:center;
            ">
            Rencana
        </td>

        @foreach($months as $monthIndex => $month)

            @for($week=1; $week<=4; $week++)

                @php $active = in_array([$monthIndex + 1, $week], $program->planCells()); @endphp

                <td style="
                    border:1px solid #000;
                    {{ $active ? 'background:#dc3545;' : '' }}
                ">

                </td>

            @endfor

        @endforeach

        <td rowspan="2"
            style="
                border:1px solid #000;
                text-align:left;
                vertical-align:top;
            ">
            {{ $program->note }}
        </td>

    </tr>

    <!-- REALISASI -->
    <tr>

        <td style="
                border:1px solid #000;
                text-align:center;
            ">
            Realisasi
        </td>

        @foreach($months as $monthIndex => $month)

            @for($week=1; $week<=4; $week++)

                @php $active = in_array([$monthIndex + 1, $week], $program->actualCells()); @endphp

                <td style="
                    border:1px solid #000;
                    {{ $active ? 'background:#0d6efd;' : '' }}
                ">

                </td>

            @endfor

        @endforeach

    </tr>

    @endforeach

</table>

<br><br>

@endforeach