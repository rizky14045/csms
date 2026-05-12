@php
    function safe($value)
    {
        $value = $value ?? '-';
        $value = str_replace('&', 'dan', $value);
        return e($value);
    }
@endphp

<table>

    <!-- HEADER -->
    <tr>
        <th colspan="2" rowspan="4"></th>

        <th colspan="10" style="font-weight:bold; text-align:left;">
            SISTEM MANAJEMEN PENGAMANAN
        </th>

        <th colspan="3">No Dokumen</th>
        <th colspan="3">:</th>
    </tr>

    <tr>
        <th colspan="10" style="font-weight:bold; text-align:left;">
            PT PLN NUSANTARA POWER KANTOR PUSAT
        </th>

        <th colspan="3">Bulan</th>
        <th colspan="3">: {{ \Carbon\Carbon::parse($monthlyReport->report_date)->format('F Y') }}</th>
    </tr>

    <tr>
        <th colspan="10" style="font-weight:bold; text-align:left;">
            FORMULIR PENANGGUNG JAWAB KEAMANAN DAN PERSONIL KEAMANAN EKSTERNAL
        </th>

        <th colspan="3">Revisi</th>
        <th colspan="3">: 00</th>
    </tr>

    <tr>
        <th colspan="10"></th>

        <th colspan="3">Halaman</th>
        <th colspan="3">: 1 dari 1</th>
    </tr>

    <tr>
        <td colspan="18"></td>
    </tr>

    <!-- A -->
    <tr>
        <th colspan="18" style="font-weight:bold; text-align:left;">
            A. DATA PENANGGUNG JAWAB PENGAMANAN
        </th>
    </tr>

    <tr>
        <th rowspan="3" style="background:#D9D9D9; border:1px solid #000;">NO</th>
        <th rowspan="3" style="background:#D9D9D9; border:1px solid #000;">NAMA</th>
        <th rowspan="3" style="background:#D9D9D9; border:1px solid #000;">JABATAN</th>
        <th rowspan="3" style="background:#D9D9D9; border:1px solid #000;">UNIT KERJA</th>
        <th colspan="7" style="background:#A9D08E; border:1px solid #000;"> PELATIHAN DAN KOMPETENSI PENGAMANAN </th>
        <th rowspan="3" style="background:#D9D9D9; border:1px solid #000;">KETERANGAN</th>
    </tr>
    <tr>
        <th colspan="7" style="background:#C6E0B4; border:1px solid #000;"> KUALIFIKASI </th>
    </tr>
    <tr>
        <th style="background:#E2EFDA; border:1px solid #000;">SMP</th>
        <th style="background:#E2EFDA; border:1px solid #000;">AUDITOR</th>
        <th style="background:#E2EFDA; border:1px solid #000;">UTAMA</th>
        <th style="background:#E2EFDA; border:1px solid #000;">INVESTIGASI</th>
        <th style="background:#E2EFDA; border:1px solid #000;">MANSRISK</th>
        <th style="background:#E2EFDA; border:1px solid #000;">STAKEHOLDER</th>
        <th style="background:#E2EFDA; border:1px solid #000;">PENDIDIKAN</th>
    </tr>
    @foreach ($persons as $person)
        <tr>
            <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
            <td style="border:1px solid #000;">{{ safe($person->person->name) }}</td>
            <td style="border:1px solid #000;">{{ safe($person->person->position) }}</td>
            <td style="border:1px solid #000;">{{ safe($person->person->work_unit) }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($person->person->training_smp) }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($person->person->auditor_smp) }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($person->person->main) }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($person->person->investigation) }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($person->person->mansrisk) }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($person->person->stackholder_management) }}
            </td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($person->person->last_education) }}</td>
            <td style="border:1px solid #000;">{{ safe($person->person->note) }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="18"></td>
    </tr>

    <!-- B -->
    <tr>
        <th colspan="18" style="font-weight:bold; text-align:left;">
            B. DATA PERSONIL KEAMANAN EKSTERNAL
        </th>
    </tr>

    <tr>
        <th style="background:#D9D9D9; border:1px solid #000;">NO</th>
        <th colspan="2" style="background:#D9D9D9; border:1px solid #000;">NAMA</th>
        <th colspan="3" style="background:#D9D9D9; border:1px solid #000;">INSTANSI</th>
        <th colspan="2" style="background:#D9D9D9; border:1px solid #000;">SATUAN</th>
        <th colspan="2" style="background:#D9D9D9; border:1px solid #000;">SURAT</th>
        <th colspan="2" style="background:#D9D9D9; border:1px solid #000;">KETERANGAN</th>
    </tr>

    @foreach ($securities as $security)
        <tr>
            <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
            <td colspan="2" style="border:1px solid #000;">{{ safe($security->security->name) }}</td>
            <td colspan="3" style="border:1px solid #000;">{{ safe($security->security->instansi) }}</td>
            <td colspan="2" style="border:1px solid #000;">{{ safe($security->security->regional_unit) }}</td>
            <td colspan="2" style="border:1px solid #000;">{{ safe($security->security->warrant_number) }}</td>
            <td colspan="2" style="border:1px solid #000;">{{ safe($security->security->note) }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="18"></td>
    </tr>

    <!-- C -->
    <tr>
        <th colspan="18" style="font-weight:bold; text-align:left;">
            C. DATA PERJANJIAN KERJASAMA EKSTERNAL
        </th>
    </tr>

    <tr>
        <th style="background:#D9D9D9; border:1px solid #000;">NO</th>
        <th colspan="2" style="background:#D9D9D9; border:1px solid #000;">INSTANSI</th>
        <th colspan="2" style="background:#D9D9D9; border:1px solid #000;">NAMA</th>
        <th colspan="2" style="background:#D9D9D9; border:1px solid #000;">NO PKT</th>
        <th colspan="3" style="background:#D9D9D9; border:1px solid #000;">JUDUL</th>
        <th style="background:#D9D9D9; border:1px solid #000;">MASA</th>
        <th style="background:#D9D9D9; border:1px solid #000;">KETERANGAN</th>
    </tr>

    @foreach ($agreements as $agreement)
        <tr>
            <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
            <td colspan="2" style="border:1px solid #000;">{{ safe($agreement->agreement->instansi) }}</td>
            <td colspan="2" style="border:1px solid #000;">{{ safe($agreement->agreement->name) }} /
                {{ safe($agreement->agreement->regional_unit) }}</td>
            <td colspan="2" style="border:1px solid #000;">{{ safe($agreement->agreement->pkt_number) }}</td>
            <td colspan="3" style="border:1px solid #000;">{{ safe($agreement->agreement->pkt_title) }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ safe($agreement->agreement->expired_date) }}</td>
            <td style="border:1px solid #000;">{{ safe($agreement->agreement->note) }}</td>
        </tr>
    @endforeach

</table>
