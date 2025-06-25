<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Izin</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.6; }
    </style>
</head>
<body>
    <h3 style="text-align:center;">Surat Izin {{ $izin->keterangan }}</h3>
    <p>Yang bertanda tangan di bawah ini:</p>
    <p>Nama: {{ $izin->user->name }}</p>
    <p>Alasan Izin: {{ $izin->alasan }}</p>
    <p>Mulai: {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }}</p>
    <p>Selesai: {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}</p>

    @if($izin->perjalanan_keluar_kota)
        <p>Perjalanan Keluar Kota: {{ $izin->perjalanan_keluar_kota }}</p>
    @endif

    <p>Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
</body>
</html>
