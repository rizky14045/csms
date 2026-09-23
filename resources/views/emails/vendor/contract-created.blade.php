<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <p>Yth. <strong>{{ $vendorName }}</strong>,</p>

    <p>
        Kontrak baru untuk BUJP / Vendor Anda telah berhasil ditambahkan pada sistem
        Compliance Security Management System (CSMS) PLN Nusantara Power.
        Berikut adalah detail kontrak Anda:
    </p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Nomor Kontrak</strong></td>
            <td style="border: 1px solid #ddd;">{{ $contractNumber }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Tanggal Mulai</strong></td>
            <td style="border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Tanggal Berakhir</strong></td>
            <td style="border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</td>
        </tr>
    </table>

    <p>
        Silakan login menggunakan akun Anda yang sudah terdaftar sebelumnya untuk melihat detail kontrak ini:<br>
        <a href="{{ route('login') }}">{{ route('login') }}</a>
    </p>

    <p>Terima kasih.</p>

</body>
</html>
