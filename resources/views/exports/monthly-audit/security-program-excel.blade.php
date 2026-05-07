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

        <td colspan="40"></td>

        <td colspan="10"
            style="border:1px solid #000; font-weight:bold;">
            No Dokumen : FM-SMP-KP-01-11-06
        </td>

    </tr>

    <tr>

        <td colspan="40"
            style="
                text-align:center;
                font-size:14pt;
                font-weight:bold;
            ">
            PT PLN NUSANTARA POWER
        </td>

        <td colspan="10"
            style="border:1px solid #000;">
            Tgl Terbit : 1 Agustus 2022
        </td>

    </tr>

    <tr>

        <td colspan="40"
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

        <td colspan="40"></td>

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

        <td colspan="51"
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
            colspan="2"
            style="
                border:1px solid #000;
                text-align:center;
            ">
            {{ $program->mainProgram->program_name }}
        </td>

        @foreach($months as $monthIndex => $month)

            @for($week=1; $week<=4; $week++)

                @php

                    $active = false;

                    if(
                        $monthIndex > $planningStartMonth &&
                        $monthIndex < $planningEndMonth
                    ){
                        $active = true;
                    }

                    elseif(
                        $monthIndex == $planningStartMonth &&
                        $monthIndex == $planningEndMonth
                    ){
                        $active =
                            $week >= $program->mainProgram->start_week &&
                            $week <= $program->mainProgram->end_week;
                    }

                    elseif($monthIndex == $planningStartMonth){
                        $active =
                            $week >= $program->mainProgram->start_week;
                    }

                    elseif($monthIndex == $planningEndMonth){
                        $active =
                            $week <= $program->mainProgram->end_week;
                    }

                @endphp

                <td style="
                    border:1px solid #000;
                    {{ $active ? 'background:#dc3545;' : '' }}
                ">

                </td>

            @endfor

        @endforeach

    </tr>

    <!-- REALISASI -->
    <tr>

        @foreach($months as $monthIndex => $month)

            @for($week=1; $week<=4; $week++)

                @php

                    $active = false;

                    if(
                        $monthIndex > $realisasiStartMonth &&
                        $monthIndex < $realisasiEndMonth
                    ){
                        $active = true;
                    }

                    elseif(
                        $monthIndex == $realisasiStartMonth &&
                        $monthIndex == $realisasiEndMonth
                    ){
                        $active =
                            $week >= $program->start_week &&
                            $week <= $program->end_week;
                    }

                    elseif($monthIndex == $realisasiStartMonth){
                        $active =
                            $week >= $program->start_week;
                    }

                    elseif($monthIndex == $realisasiEndMonth){
                        $active =
                            $week <= $program->end_week;
                    }

                @endphp

                <td style="
                    border:1px solid #000;
                    {{ $active ? 'background:#0dcaf0;' : '' }}
                ">

                </td>

            @endfor

        @endforeach

    </tr>

    @endforeach

</table>

<br><br>

@endforeach