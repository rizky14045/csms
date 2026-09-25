<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Validasi Pusat {{ $typeLabel }} Selesai</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <p>Yth. Bapak/Ibu,</p>

    <p>
        Validasi Pusat untuk <strong>{{ $typeLabel }}</strong> unit <strong>{{ $unitName }}</strong>
        ({{ $periodLabel }}) pada sistem SIdak PLN Nusantara Power telah <strong>selesai</strong>.
    </p>

    <p>
        Unit diberikan waktu untuk mengajukan <strong>sanggahan</strong> paling lambat sampai
        <strong>{{ \Carbon\Carbon::parse($deadline)->format('d-m-Y') }}</strong> (maksimal 7 hari sejak validasi selesai).
    </p>

    <ul>
        <li>Sanggahan dapat diajukan melalui tombol <em>Sanggah</em> pada halaman {{ $typeLabel }} unit.</li>
        <li>Hanya data yang belum dicentang (divalidasi) oleh Pusat yang dapat diubah.</li>
        <li>Sanggahan hanya dapat dilakukan satu kali dan dikirim kembali ke MMRK untuk diteruskan ke Pusat.</li>
        <li>Setelah batas waktu terlewati, sanggahan tidak dapat diajukan lagi.</li>
    </ul>

    <p>Silakan login melalui <a href="{{ route('login') }}">{{ route('login') }}</a>.</p>

    <p>Terima kasih.</p>

</body>
</html>
