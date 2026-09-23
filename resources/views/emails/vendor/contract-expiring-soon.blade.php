<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <p>Yth. Bapak/Ibu,</p>

    <p>
        Kami informasikan bahwa kontrak BUJP / Vendor berikut akan
        <strong>berakhir dalam 6 (enam) bulan ke depan</strong>:
    </p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Nama BUJP / Vendor</strong></td>
            <td style="border: 1px solid #ddd;">{{ $vendorName }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Nomor Kontrak</strong></td>
            <td style="border: 1px solid #ddd;">{{ $contractNumber }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Tanggal Berakhir Kontrak</strong></td>
            <td style="border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</td>
        </tr>
    </table>

    <p>
        Mohon segera lakukan <strong>perpanjangan kontrak</strong> dengan BUJP / Vendor tersebut,
        atau mulai proses <strong>pengadaan BUJP / Vendor baru</strong> sebagai penggantinya,
        agar tidak terjadi kekosongan layanan pengamanan pada unit Anda.
    </p>

    <p>Terima kasih atas perhatian dan kerja samanya.</p>

</body>
</html>
