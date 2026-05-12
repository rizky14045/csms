<table>

    <!-- HEADER -->
    <tr>

        <td colspan="6"></td>

        <td colspan="4" style="border:1px solid #000; font-weight:bold;">
            No Dokumen : 
        </td>

    </tr>

    <tr>

        <td colspan="6" style="text-align:center; font-size:14pt; font-weight:bold;">
            PT PLN NUSANTARA POWER
        </td>

        <td colspan="4" style="border:1px solid #000;">
            Bulan : {{ \Carbon\Carbon::parse($monthlyReport->report_date)->format('F Y') }}
        </td>

    </tr>

    <tr>

        <td colspan="6" style="text-align:center; font-weight:bold;">
            FORMULIR DATA AGHT
        </td>

        <td colspan="4" style="border:1px solid #000;">
            Revisi : 00
        </td>

    </tr>

    <tr>

        <td colspan="6"></td>

        <td colspan="4" style="border:1px solid #000;">
            Halaman : 1 dari 1
        </td>

    </tr>

    <!-- SPACER -->
    <tr>
        <td colspan="10"></td>
    </tr>

    <!-- TABLE HEADER -->
    <tr>

        <th rowspan="2" style="border:1px solid #000; background:#D9EAD3; text-align:center;">
            No
        </th>

        <th rowspan="2" style="border:1px solid #000; background:#D9EAD3; text-align:center;">
            Uraian Kegiatan
        </th>

        <th colspan="3" style="border:1px solid #000; background:#D9EAD3; text-align:center;">
            Waktu Kejadian Perkara
        </th>

        <th rowspan="2" style="border:1px solid #000; background:#D9EAD3; text-align:center;">
            Kerugian Akibat Yang Ditimbulkan
        </th>

        <th rowspan="2" style="border:1px solid #000; background:#D9EAD3; text-align:center;">
            Tindakan Pengamanan Setelah Kejadian
        </th>

        <th rowspan="2" style="border:1px solid #000; background:#D9EAD3; text-align:center;">
            Aparat Yang Dihubungi
        </th>

        <th rowspan="2" style="border:1px solid #000; background:#D9EAD3; text-align:center;">
            Keterangan
        </th>

    </tr>

    <tr>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Tanggal
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Pukul
        </th>

        <th style="border:1px solid #000; background:#D9EAD3;">
            Lokasi
        </th>

    </tr>

    <!-- DATA -->
    @foreach ($aghts as $aght)
        <tr>

            <td style="border:1px solid #000; text-align:center;">
                {{ $loop->iteration }}
            </td>

            <td style="border:1px solid #000;">
                {{ $aght->activity ?? '-' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $aght->incident_date ?? '-' }}
            </td>

            <td style="border:1px solid #000; text-align:center;">
                {{ $aght->incident_time ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $aght->incident_location ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $aght->loss ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $aght->after_incident ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $aght->officer_contacted ?? '-' }}
            </td>

            <td style="border:1px solid #000;">
                {{ $aght->note ?? '-' }}
            </td>

        </tr>
    @endforeach

</table>
