<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <p>Yth. Bapak/Ibu,</p>

    <p>
        Kami informasikan bahwa Kartu Tanda Anggota (KTA) Satuan Pengamanan berikut akan
        <strong>berakhir masa berlakunya dalam 3 (tiga) bulan ke depan</strong>:
    </p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Nama Anggota</strong></td>
            <td style="border: 1px solid #ddd;">{{ $memberName }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Unit Kerja</strong></td>
            <td style="border: 1px solid #ddd;">{{ $unitWork }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>No. Registrasi KTA</strong></td>
            <td style="border: 1px solid #ddd;">{{ $registrationNumber }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Tanggal Berakhir KTA</strong></td>
            <td style="border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($expiredDate)->format('d-m-Y') }}</td>
        </tr>
    </table>

    <p>
        Mohon segera lakukan <strong>perpanjangan KTA anggota tersebut</strong> sebelum masa
        berlakunya habis, agar tidak mengganggu kelengkapan dokumen personil satuan pengamanan
        pada unit Anda.
    </p>

    <p>Terima kasih atas perhatian dan kerja samanya.</p>

</body>
</html>
