<table>
    <!-- Logo Space -->
    <tr><td colspan="4" height="65"></td></tr>

    <!-- Title Header -->
    <tr>
        <td colspan="4" style="text-align: center; font-size: 14pt; font-weight: bold;">
            LAPORAN BULANAN KEAMANAN
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; font-weight: bold;">
            BULAN : {{ \Carbon\Carbon::parse($monthlyReport->report_date)->format('F Y') }}
        </td>
    </tr>
    <tr><td colspan="4"></td></tr>

    <!-- SECTION 1: PEGAWAI PLN -->
    <tr>
        <th colspan="4" style="background-color: #E2EFDA; font-weight: bold; border: 1px solid #000;">
            1. JUMLAH PEGAWAI PT PLN NUSANTARA POWER
        </th>
    </tr>
    <tr style="background-color: #F2F2F2; text-align: center; font-weight: bold;">
        <td style="border: 1px solid #000;">Personil</td>
        <td style="border: 1px solid #000;">Total Orang</td>
        <td style="border: 1px solid #000;">Pria</td>
        <td style="border: 1px solid #000;">Wanita</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000;">1.1 Pegawai Tetap</td>
        <td style="border: 1px solid #000; text-align: center;">{{ ($employee->employee_man ?? 0) + ($employee->employee_woman ?? 0) }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $employee->employee_man ?? 0 }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $employee->employee_woman ?? 0 }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000;">1.2 Siswa OJT</td>
        <td style="border: 1px solid #000; text-align: center;">{{ ($employee->student_man ?? 0) + ($employee->student_woman ?? 0) }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $employee->student_man ?? 0 }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $employee->student_woman ?? 0 }}</td>
    </tr>

    <!-- SECTION 2: OUTSOURCING -->
    <tr><td colspan="4"></td></tr>
    <tr>
        <th colspan="4" style="background-color: #E2EFDA; font-weight: bold; border: 1px solid #000;">
            2. JUMLAH KARYAWAN OUTSOURCING
        </th>
    </tr>
    @foreach ($outsources as $index => $outsource)
    <tr>
        <td style="border: 1px solid #000;">2.{{ $index + 1 }} {{ $outsource->name }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $outsource->total }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $outsource->man }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $outsource->woman }}</td>
    </tr>
    @endforeach

    <!-- SECTION 3: SATPAM -->
    <tr><td colspan="4"></td></tr>
    <tr>
        <th colspan="2" style="background-color: #E2EFDA; font-weight: bold; border: 1px solid #000;">
            3. JUMLAH PERSONIL SATPAM
        </th>
        <th colspan="2" style="background-color: #E2EFDA; font-weight: bold; border: 1px solid #000; text-align: center;">
            TOTAL
        </th>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">3.1 Komandan Regu</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $securities->where('position', 'Komandan')->count() }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">3.2 Anggota Satuan</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $securities->where('position', 'Anggota')->count() }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">3.3 Chief Satpam</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $securities->where('position', 'Chief')->count() }}</td>
    </tr>

    <!-- SECTION 4 & 5: JASA PENGAMANAN & TKA -->
    <tr><td colspan="4"></td></tr>
    <tr>
        <th colspan="2" style="background-color: #DDEBF7; font-weight: bold; border: 1px solid #000;">4. JASA PENGAMANAN (POLRI/TNI)</th>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $securityExternal }}</td>
    </tr>
    <tr>
        <th colspan="2" style="background-color: #DDEBF7; font-weight: bold; border: 1px solid #000;">5. JUMLAH TENAGA KERJA ASING (TKA)</th>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $foreign }}</td>
    </tr>

    <!-- SECTION 6: GANGGUAN -->
    <tr><td colspan="4"></td></tr>
    <tr>
        <th colspan="2" style="background-color: #FCE4D6; font-weight: bold; border: 1px solid #000;">6. GANGGUAN YANG TERJADI</th>
        <th colspan="2" style="background-color: #FCE4D6; font-weight: bold; border: 1px solid #000; text-align: center;">JUMLAH</th>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">6.1 Bersifat Kriminal</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $gangguan->kriminal ?? 0 }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">6.2 Bersifat Politis</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $gangguan->politis ?? 0 }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">6.3 Kebakaran</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $gangguan->kebakaran ?? 0 }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">6.4 Bencana Alam</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $gangguan->bencana_alam ?? 0 }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border: 1px solid #000;">6.5 Lain-lain</td>
        <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $gangguan->other ?? 0 }}</td>
    </tr>

    <!-- FINAL TOTAL SUMMARY -->
    <tr><td colspan="4"></td></tr>
    <tr>
        <td colspan="4" style="border: 2px solid #000; background-color: #FFC000; font-weight: bold; text-align: center; height: 30px;">
            JUMLAH TOTAL (1+2+3+4+5): {{ $totalAll }} Orang 
            (Pria: {{ $totalAllMan }} | Wanita: {{ $totalAllWoman }})
        </td>
    </tr>
</table>