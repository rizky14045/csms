<table>
    <!-- HEADER DOKUMEN: Total 13 Kolom (A-M) -->
    <tr>
        <td colspan="9"></td>
        <td colspan="4" style="border: 1px solid #000; font-weight: bold;">No Dokumen : FM-SMP-KP-01-11-01</td>
    </tr>
    <tr>
        <td colspan="9" style="text-align: center; font-weight: bold; font-size: 14pt;">PT PLN NUSANTARA POWER</td>
        <td colspan="4" style="border: 1px solid #000;">Tgl Terbit : 1 Agustus 2022</td>
    </tr>
    <tr>
        <td colspan="9" style="text-align: center; font-weight: bold;">FORMULIR PENANGGUNG JAWAB KEAMANAN & PERSONIL KEAMANAN EKSTERNAL</td>
        <td colspan="4" style="border: 1px solid #000;">Revisi : 00</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="4" style="border: 1px solid #000;">Halaman : 1 dari 1</td>
    </tr>

    <!-- Spacer -->
    <tr><td colspan="13"></td></tr>

    <!-- A. DATA PENANGGUNG JAWAB -->
    <tr>
        <td style="width: 5px;"></td>
        <td colspan="12" style="font-weight: bold; background-color: #E2EFDA; border: 1px solid #000;">A. DATA PENANGGUNG JAWAB PENGAMANAN</td>
    </tr>
    <tr>
        <td></td>
        <th rowspan="3" style="border: 1px solid #000; background-color: #f2f2f2; text-align: center; font-weight: bold; vertical-align: center;">NO</th>
        <th rowspan="3" style="border: 1px solid #000; background-color: #f2f2f2; text-align: center; font-weight: bold; vertical-align: center;">NAMA</th>
        <th rowspan="3" style="border: 1px solid #000; background-color: #f2f2f2; text-align: center; font-weight: bold; vertical-align: center;">JABATAN</th>
        <th rowspan="3" style="border: 1px solid #000; background-color: #f2f2f2; text-align: center; font-weight: bold; vertical-align: center;">UNIT KERJA</th>
        <th colspan="7" style="border: 1px solid #000; background-color: #f2f2f2; text-align: center; font-weight: bold;">PELATIHAN / KOMPETENSI PENGAMANAN</th>
        <th rowspan="3" style="border: 1px solid #000; background-color: #f2f2f2; text-align: center; font-weight: bold; vertical-align: center;">KETERANGAN</th>
    </tr>
    <tr>
        <td></td>
        <th colspan="7" style="border: 1px solid #000; text-align: center; font-weight: bold;">KUALIFIKASI</th>
    </tr>
    <tr>
        <td></td>
        <th style="border: 1px solid #000; text-align: center; font-size: 9pt;">PELATIHAN SMP</th>
        <th style="border: 1px solid #000; text-align: center; font-size: 9pt;">AUDITOR SMP</th>
        <th style="border: 1px solid #000; text-align: center; font-size: 9pt;">UTAMA</th>
        <th style="border: 1px solid #000; text-align: center; font-size: 9pt;">INVESTIGASI</th>
        <th style="border: 1px solid #000; text-align: center; font-size: 9pt;">MANSRISK</th>
        <th style="border: 1px solid #000; text-align: center; font-size: 9pt;">STAKEHOLDER</th>
        <th style="border: 1px solid #000; text-align: center; font-size: 9pt;">PENDIDIKAN TERAKHIR</th>
    </tr>
    @foreach ($persons as $index => $p)
    <tr>
        <td></td>
        <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="border: 1px solid #000;">{{ $p->person->name ?? '-' }}</td>
        <td style="border: 1px solid #000;">{{ $p->person->position ?? '-' }}</td>
        <td style="border: 1px solid #000;">{{ $p->person->work_unit ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $p->person->training_smp ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $p->person->auditor_smp ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $p->person->main ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $p->person->investigation ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $p->person->mansrisk ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $p->person->stackholder_management ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $p->person->last_education ?? '-' }}</td>
        <td style="border: 1px solid #000;">{{ $p->person->note ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Spacer -->
    <tr><td colspan="13"></td></tr>

    <!-- B. DATA PERSONIL EKSTERNAL -->
    <tr>
        <td></td>
        <td colspan="12" style="font-weight: bold; background-color: #DDEBF7; border: 1px solid #000;">B. DATA PERSONIL KEAMANAN EKSTERNAL</td>
    </tr>
    <tr>
        <td></td>
        <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">NO</th>
        <th colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">NAMA</th>
        <th colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">INSTANSI</th>
        <th colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">SATUAN WILAYAH</th>
        <th colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">NO SURAT PERINTAH</th>
        <th colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">KETERANGAN</th>
    </tr>
    @foreach ($securities as $index => $s)
    <tr>
        <td></td>
        <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $s->security->name ?? '-' }}</td>
        <td colspan="3" style="border: 1px solid #000;">{{ $s->security->instansi ?? '-' }}</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $s->security->regional_unit ?? '-' }}</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $s->security->warrant_number ?? '-' }}</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $s->security->note ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Spacer -->
    <tr><td colspan="13"></td></tr>

    <!-- C. DATA PERJANJIAN KERJASAMA -->
    <tr>
        <td></td>
        <td colspan="12" style="font-weight: bold; background-color: #FFF2CC; border: 1px solid #000;">C. DATA PERJANJIAN KERJASAMA EKSTERNAL</td>
    </tr>
    <tr>
        <td></td>
        <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">NO</th>
        <th colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">INSTANSI</th>
        <th colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">NAMA</th>
        <th colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">NO PKT</th>
        <th colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">JUDUL PKT</th>
        <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">MASA BERLAKU</th>
        <th style="border: 1px solid #000; background-color: #f2f2f2; font-weight: bold; text-align: center;">KETERANGAN</th>
    </tr>
    @foreach ($agreements as $index => $a)
    <tr>
        <td></td>
        <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $a->agreement->instansi ?? '-' }}</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $a->agreement->name ?? '-' }}</td>
        <td colspan="2" style="border: 1px solid #000;">{{ $a->agreement->pkt_number ?? '-' }}</td>
        <td colspan="3" style="border: 1px solid #000;">{{ $a->agreement->pkt_title ?? '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $a->agreement->expired_date ?? '-' }}</td>
        <td style="border: 1px solid #000;">{{ $a->agreement->note ?? '-' }}</td>
    </tr>
    @endforeach
</table>