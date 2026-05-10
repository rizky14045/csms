<table>

    <!-- HEADER -->
    <tr>
        <td colspan="11"></td>

        <td colspan="4" style="border:1px solid #000; font-weight:bold;">
            No Dokumen : FM-SMP-KP-01-11-02
        </td>
    </tr>

    <tr>

        <td colspan="11" style="text-align:center; font-size:14pt; font-weight:bold;">
            PT PLN NUSANTARA POWER
        </td>

        <td colspan="4" style="border:1px solid #000;">
            Bulan : {{ \Carbon\Carbon::parse($monthlyReport->report_date)->format('F Y') }}
        </td>

    </tr>

    <tr>

        <td colspan="11" style="text-align:center; font-weight:bold;">
            FORMULIR DATA PERSONIL SATUAN PENGAMANAN
        </td>

        <td colspan="4" style="border:1px solid #000;">
            Revisi : 00
        </td>

    </tr>

    <tr>

        <td colspan="11"></td>

        <td colspan="4" style="border:1px solid #000;">
            Halaman : 1 dari 1
        </td>

    </tr>

    <!-- SPACER -->
    <tr>
        <td colspan="15"></td>
    </tr>

    <!-- TABLE HEADER -->
    <tr>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            No
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            Nama Anggota
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            Unit Kerja
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            NID
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            No Registrasi KTA
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            KTA Berlaku
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            Jabatan
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            Tempat, Tanggal Lahir
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            Umur
        </th>

        <th colspan="3" style="border:1px solid #000; background-color:#D9EAD3; text-align:center;">
            Kualifikasi
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            Pendidikan Umum Terakhir
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            Keterangan
        </th>

        <th rowspan="2"
            style="border:1px solid #000; background-color:#D9EAD3; text-align:center; vertical-align:center;">
            File Upload
        </th>

    </tr>

    <tr>

        <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center;">
            Pratama
        </th>

        <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center;">
            Madya
        </th>

        <th style="border:1px solid #000; background-color:#D9EAD3; text-align:center;">
            Utama
        </th>

    </tr>

    <!-- DATA -->
    @foreach ($forms as $form)
        <tr>

            <td style="border:1px solid #000; text-align:center;">
                {{ $loop->iteration }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->name ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->unit_work ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->nid ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->registration_number ?? '-' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $form->security->expired_card_date ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->position ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->birth_place ?? '-' }},
                {{ $form->security->birth_date ?? '-' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ !empty($form->security->birth_date) ? \Carbon\Carbon::parse($form->security->birth_date)->age : '-' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $form->security->qualification == 'Pratama' ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $form->security->qualification == 'Madya' ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $form->security->qualification == 'Utama' ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->last_education ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->security->note ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $form->attachment_file ?? '-' }}
            </td>

        </tr>
    @endforeach

</table>
