<table>

    {{-- HEADER --}}
    <tr>
        <td colspan="6"></td>
        <td colspan="2"
            style="border:1px solid black; font-weight:bold;">
            No Dokumen : 
        </td>
    </tr>

    <tr>
        <td colspan="6"
            style="text-align:center; font-weight:bold; font-size:16px;">
            PT PLN NUSANTARA POWER
        </td>

        <td colspan="2"
            style="border:1px solid black;">
            Tgl Terbit : {{ \Carbon\Carbon::parse($monthlyReport->report_date)->format('F Y') }}
        </td>
    </tr>

    <tr>
        <td colspan="6"
            style="text-align:center; font-weight:bold;">
            FORMULIR PENYERAPAN ANGGARAN
        </td>

        <td colspan="2"
            style="border:1px solid black;">
            Revisi : 00
        </td>
    </tr>

    <tr>
        <td colspan="6"></td>

        <td colspan="2"
            style="border:1px solid black;">
            Halaman : 1 dari 1
        </td>
    </tr>

    <tr>
        <td colspan="8"></td>
    </tr>

    {{-- BIAYA PEMELIHARAAN --}}
    <tr>
        <td colspan="8"
            style="
                font-weight:bold;
                background-color:#D9EAD3;
                border:1px solid black;
            ">
            A. BIAYA PEMELIHARAAN
        </td>
    </tr>

    <tr>
        <th style="border:1px solid black; background:#F2F2F2;">No</th>
        <th style="border:1px solid black; background:#F2F2F2;">Kode Aktifitas</th>
        <th style="border:1px solid black; background:#F2F2F2;">Kode PRK</th>
        <th style="border:1px solid black; background:#F2F2F2;">Deskripsi Kegiatan</th>
        <th style="border:1px solid black; background:#F2F2F2;">Jumlah Anggaran</th>
        <th style="border:1px solid black; background:#F2F2F2;">Penyerapan Anggaran</th>
        <th style="border:1px solid black; background:#F2F2F2;">Prosentase Penyerapan</th>
        <th style="border:1px solid black; background:#F2F2F2;">Keterangan</th>
    </tr>

    @forelse ($pemeliharaan as $item)
        <tr>
            <td style="border:1px solid black; text-align:center;">
                {{ $loop->iteration }}
            </td>

            <td style="border:1px solid black;">
                {{ $item->kode_aktifitas }}
            </td>

            <td style="border:1px solid black;">
                {{ $item->kode_prk }}
            </td>

            <td style="border:1px solid black;">
                {{ $item->deskripsi_kegiatan }}
            </td>

            <td style="border:1px solid black;">
                {{ number_format($item->jumlah_anggaran, 2, ',', '.') }}
            </td>

            <td style="border:1px solid black;">
                {{ number_format($item->penyerapan_anggaran, 2, ',', '.') }}
            </td>

            <td style="border:1px solid black; text-align:center;">
                {{ number_format($item->prosentase_penyerapan, 2, ',', '.') }}%
            </td>

            <td style="border:1px solid black;">
                {{ $item->keterangan }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8"
                style="border:1px solid black; text-align:center;">
                Tidak ada data
            </td>
        </tr>
    @endforelse

    <tr>
        <td colspan="8"></td>
    </tr>

    {{-- BIAYA ADMINISTRASI --}}
    <tr>
        <td colspan="8"
            style="
                font-weight:bold;
                background-color:#CFE2F3;
                border:1px solid black;
            ">
            B. BIAYA ADMINISTRASI
        </td>
    </tr>

    <tr>
        <th style="border:1px solid black; background:#F2F2F2;">No</th>
        <th style="border:1px solid black; background:#F2F2F2;">Kode Aktifitas</th>
        <th style="border:1px solid black; background:#F2F2F2;">Kode PRK</th>
        <th style="border:1px solid black; background:#F2F2F2;">Deskripsi Kegiatan</th>
        <th style="border:1px solid black; background:#F2F2F2;">Jumlah Anggaran</th>
        <th style="border:1px solid black; background:#F2F2F2;">Penyerapan Anggaran</th>
        <th style="border:1px solid black; background:#F2F2F2;">Prosentase Penyerapan</th>
        <th style="border:1px solid black; background:#F2F2F2;">Keterangan</th>
    </tr>

    @forelse ($administrasi as $item)
        <tr>
            <td style="border:1px solid black; text-align:center;">
                {{ $loop->iteration }}
            </td>

            <td style="border:1px solid black;">
                {{ $item->kode_aktifitas }}
            </td>

            <td style="border:1px solid black;">
                {{ $item->kode_prk }}
            </td>

            <td style="border:1px solid black;">
                {{ $item->deskripsi_kegiatan }}
            </td>

            <td style="border:1px solid black;">
                {{ number_format($item->jumlah_anggaran, 2, ',', '.') }}
            </td>

            <td style="border:1px solid black;">
                {{ number_format($item->penyerapan_anggaran, 2, ',', '.') }}
            </td>

            <td style="border:1px solid black; text-align:center;">
                {{ number_format($item->prosentase_penyerapan, 2, ',', '.') }}%
            </td>

            <td style="border:1px solid black;">
                {{ $item->keterangan }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8"
                style="border:1px solid black; text-align:center;">
                Tidak ada data
            </td>
        </tr>
    @endforelse

</table>