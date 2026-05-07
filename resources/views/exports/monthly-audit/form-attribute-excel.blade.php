<table>

    <!-- HEADER -->
    <tr>
        <td colspan="6"></td>

        <td colspan="4"
            style="border:1px solid #000; font-weight:bold;">
            No Dokumen : FM-SMP-KP-01-11-04
        </td>
    </tr>

    <tr>

        <td colspan="6"
            style="text-align:center; font-size:14pt; font-weight:bold;">
            PT PLN NUSANTARA POWER
        </td>

        <td colspan="4"
            style="border:1px solid #000;">
            Tgl Terbit : 1 Agustus 2022
        </td>

    </tr>

    <tr>

        <td colspan="6"
            style="text-align:center; font-weight:bold;">
            FORMULIR ATRIBUT DAN PERALATAN
        </td>

        <td colspan="4"
            style="border:1px solid #000;">
            Revisi : 00
        </td>

    </tr>

    <tr>

        <td colspan="6"></td>

        <td colspan="4"
            style="border:1px solid #000;">
            Halaman : 1 dari 1
        </td>

    </tr>

</table>

<br>

<!-- ================================================= -->
<!-- FUNCTION TABLE -->
<!-- ================================================= -->

@php

function renderTable($title, $datas, $color)
{

echo '

<table>

    <!-- SECTION TITLE -->
    <tr>
        <td colspan="10"
            style="
                border:1px solid #000;
                font-weight:bold;
                background-color:'.$color.';
            ">
            '.$title.'
        </td>
    </tr>

    <!-- TABLE HEADER -->
    <tr>

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                vertical-align:center;
                background-color:'.$color.';
            ">
            No
        </th>

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                vertical-align:center;
                background-color:'.$color.';
            ">
            Uraian
        </th>

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                vertical-align:center;
                background-color:'.$color.';
            ">
            Status Kepemilikan
        </th>

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                vertical-align:center;
                background-color:'.$color.';
            ">
            Satuan
        </th>

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                vertical-align:center;
                background-color:'.$color.';
            ">
            Jumlah Standar Kontrak
        </th>

        <th colspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                background-color:'.$color.';
            ">
            Kondisi
        </th>

        <th colspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                background-color:'.$color.';
            ">
            Masa Berlaku
        </th>

        <th rowspan="2"
            style="
                border:1px solid #000;
                text-align:center;
                vertical-align:center;
                background-color:'.$color.';
            ">
            Keterangan
        </th>

    </tr>

    <!-- SUB HEADER -->
    <tr>

        <th style="
            border:1px solid #000;
            text-align:center;
            background-color:'.$color.';
        ">
            Ada
        </th>

        <th style="
            border:1px solid #000;
            text-align:center;
            background-color:'.$color.';
        ">
            Tidak
        </th>

        <th style="
            border:1px solid #000;
            text-align:center;
            background-color:'.$color.';
        ">
            Operasi / Berlaku
        </th>

        <th style="
            border:1px solid #000;
            text-align:center;
            background-color:'.$color.';
        ">
            Rusak / Kadaluarsa
        </th>

    </tr>

';

foreach($datas as $index => $item){

echo '

<tr>

    <td style="
        border:1px solid #000;
        text-align:center;
    ">
        '.($index + 1).'
    </td>

    <td style="border:1px solid #000;">
        '.($item->name ?? '-').'
    </td>

    <td style="border:1px solid #000;">
        '.($item->status_ownership ?? '-').'
    </td>

    <td style="
        border:1px solid #000;
        text-align:center;
    ">
        '.($item->unit ?? '-').'
    </td>

    <td style="
        border:1px solid #000;
        text-align:center;
    ">
        '.($item->standard_contract ?? '-').'
    </td>

    <td style="
        border:1px solid #000;
        text-align:center;
    ">
        '.($item->condition == 1 ? 'V' : '').'
    </td>

    <td style="
        border:1px solid #000;
        text-align:center;
    ">
        '.($item->condition == 0 ? 'V' : '').'
    </td>

    <td style="
        border:1px solid #000;
        text-align:center;
    ">
        '.($item->status_item == 1 ? 'V' : '').'
    </td>

    <td style="
        border:1px solid #000;
        text-align:center;
    ">
        '.($item->status_item == 0 ? 'V' : '').'
    </td>

    <td style="border:1px solid #000;">
        '.($item->note ?? '-').'
    </td>

</tr>

';

}

echo '

</table>

<br><br>

';

}

@endphp

<!-- ================================================= -->
<!-- A. ATRIBUT -->
<!-- ================================================= -->

@php

renderTable(
    'A. ATRIBUT DAN PERALATAN',
    $attributes,
    '#D9EAD3'
);

@endphp

<!-- ================================================= -->
<!-- B. ADMINISTRASI -->
<!-- ================================================= -->

@php

renderTable(
    'B. ADMINISTRASI',
    $administrations,
    '#DDEBF7'
);

@endphp

<!-- ================================================= -->
<!-- C. SARANA -->
<!-- ================================================= -->

@php

renderTable(
    'C. SARANA DAN PRASARANA',
    $saranas,
    '#FFF2CC'
);

@endphp