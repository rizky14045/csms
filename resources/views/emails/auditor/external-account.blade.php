<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Akun Auditor External SIdak</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <p>Yth. <strong>{{ $auditorName }}</strong>,</p>

    <p>
        @if ($isReset)
            Akses Anda sebagai auditor external pada sistem SIdak PLN Nusantara Power telah diperbarui.
        @else
            Anda telah didaftarkan sebagai auditor external pada sistem SIdak PLN Nusantara Power
            untuk unit <strong>{{ $unitName }}</strong>.
        @endif
        Berikut adalah detail akun Anda:
    </p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Username (Email)</strong></td>
            <td style="border: 1px solid #ddd;">{{ $email }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Password</strong></td>
            <td style="border: 1px solid #ddd;">{{ $password }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Akses Berlaku Sampai</strong></td>
            <td style="border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($expiredAt)->format('d-m-Y') }}</td>
        </tr>
    </table>

    @if (count($audits))
        <p>Data audit SMP yang dapat Anda akses:</p>
        <ul>
            @foreach ($audits as $audit)
                <li>{{ $audit }}</li>
            @endforeach
        </ul>
    @endif

    <p>
        Silakan login melalui halaman berikut:<br>
        <a href="{{ route('login') }}">{{ route('login') }}</a>
    </p>

    <p style="color:#b02a37;">
        Setelah melewati tanggal berakhir, akun ini tidak dapat digunakan lagi.
        Demi keamanan, segera ganti password Anda setelah login pertama.
    </p>

    <p>Terima kasih.</p>

</body>
</html>
