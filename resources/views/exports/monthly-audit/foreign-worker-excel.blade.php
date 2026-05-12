<table>

    <!-- HEADER -->
    <tr>

        <td colspan="16"></td>

        <td colspan="6" style="border:1px solid #000; font-weight:bold;">
            No Dokumen : 
        </td>

    </tr>

    <tr>

        <td colspan="16"
            style="
                text-align:center;
                font-size:14pt;
                font-weight:bold;
            ">
            PT PLN NUSANTARA POWER
        </td>

        <td colspan="6" style="border:1px solid #000;">
            Bulan : {{ \Carbon\Carbon::parse($monthlyReport->report_date)->format('F Y') }}
        </td>

    </tr>

    <tr>

        <td colspan="16" style="
                text-align:center;
                font-weight:bold;
            ">
            FORMULIR DATA TENAGA KERJA ASING
        </td>

        <td colspan="6" style="border:1px solid #000;">
            Revisi : 00
        </td>

    </tr>

    <tr>

        <td colspan="16"></td>

        <td colspan="6" style="border:1px solid #000;">
            Halaman : 1 dari 1
        </td>

    </tr>

    <!-- SPACER -->
    <tr>
        <td colspan="22"></td>
    </tr>

    <!-- HEADER TABLE -->
    <tr>

        <th rowspan="4" style="border:1px solid #000; background:#D9EAD3;">
            No
        </th>

        <th rowspan="4" style="border:1px solid #000; background:#D9EAD3;">
            Nama
        </th>

        <th rowspan="4" style="border:1px solid #000; background:#D9EAD3;">
            Kebangsaan
        </th>

        <th rowspan="4" style="border:1px solid #000; background:#D9EAD3;">
            Perusahaan
        </th>

        <th rowspan="4" style="border:1px solid #000; background:#D9EAD3;">
            Jabatan / Keahlian
        </th>

        <th colspan="12" style="border:1px solid #000; background:#D9EAD3;">
            Kategori
        </th>

        <th colspan="2" rowspan="3" style="border:1px solid #000; background:#D9EAD3;">
            Tanggal
        </th>

        <th rowspan="4" style="border:1px solid #000; background:#D9EAD3;">
            Keterangan
        </th>

    </tr>

    <tr>

        <th colspan="4" style="border:1px solid #000; background:#D9EAD3;">
            Tamu
        </th>

        <th colspan="8" style="border:1px solid #000; background:#D9EAD3;">
            Pekerja
        </th>

    </tr>

    <tr>

        <th colspan="2" style="border:1px solid #000; background:#D9EAD3;">
            Paspor
        </th>

        <th colspan="2" style="border:1px solid #000; background:#D9EAD3;">
            Visa
        </th>

        <th colspan="2" style="border:1px solid #000; background:#D9EAD3;">
            Paspor
        </th>

        <th colspan="2" style="border:1px solid #000; background:#D9EAD3;">
            Vitas
        </th>

        <th colspan="2" style="border:1px solid #000; background:#D9EAD3;">
            Kitas
        </th>

        <th colspan="2" style="border:1px solid #000; background:#D9EAD3;">
            RPTKA
        </th>

    </tr>

    <tr>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Paspor
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            File
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Visa
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            File
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Paspor
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            File
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Vitas
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            File
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Kitas
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            File
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            RPTKA
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            File
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Datang
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Kembali
        </th>

    </tr>

    <!-- DATA -->
    @foreach ($foreigns as $foreign)
        <tr>

            <td style="border:1px solid #000; text-align:center;">
                {{ $loop->iteration }}
            </td>

            <td style="border:1px solid #000;">
                {{ $foreign->name }}
            </td>

            <td style="border:1px solid #000;">
                {{ $foreign->nationality }}
            </td>

            <td style="border:1px solid #000;">
                {{ $foreign->company }}
            </td>

            <td style="border:1px solid #000;">
                {{ $foreign->position }}
            </td>

            <!-- TAMU -->
            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->category == 'Tamu' && $foreign->paspor == 1 ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->paspor_file ? 'Ada' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->category == 'Tamu' && $foreign->visa == 1 ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->visa_file ? 'Ada' : '' }}
            </td>

            <!-- PEKERJA -->
            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->category == 'Pekerja' && $foreign->paspor == 1 ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->paspor_file ? 'Ada' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->vitas == 1 ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->vitas_file ? 'Ada' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->kitas == 1 ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->kitas_file ? 'Ada' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->rptka == 1 ? 'V' : '' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $foreign->rptka_file ? 'Ada' : '' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $foreign->arrived_date }}
            </td>

            <td style="border:1px solid #000;">
                {{ $foreign->return_date }}
            </td>

            <td style="border:1px solid #000;">
                {{ $foreign->note }}
            </td>

        </tr>
    @endforeach

</table>
