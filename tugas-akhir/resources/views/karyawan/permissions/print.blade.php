<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin Karyawan</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            padding: 40px;
            line-height: 1.6;
            color: #000;
        }

        .kop {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .kop h2, .kop p {
            margin: 0;
        }

        .judul {
            text-align: center;
            margin: 30px 0;
        }

        .isi {
            margin-bottom: 40px;
        }

        .ttd {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .ttd .ttd-block {
            text-align: center;
        }

        @media print {
            @page { margin: 20mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="kop">
        <h2>PT. Cargomas Cakrawala</h2>
        <p>Jl. Contoh Alamat No. 123, Pontianak, Kalimantan Barat</p>
    </div>

    <div class="judul">
        <h3><u>SURAT IZIN</u></h3>
        <p>Nomor: {{ 'IZIN/' . $permission->id . '/' . date('Y') }}</p>
    </div>

    <div class="isi">
        <p>Yang bertanda tangan di bawah ini:</p>
        <p>Nama : <strong>{{ $permission->user->nama ?? $permission->user->name }}</strong></p>
        <p>Dengan ini mengajukan permohonan izin dengan rincian sebagai berikut:</p>

        <p>Keterangan : <strong>{{ $permission->keterangan }}</strong></p>
        <p>Alasan     : {{ $permission->alasan }}</p>
        <p>Mulai Izin : {{ \Carbon\Carbon::parse($permission->tanggal_mulai)->translatedFormat('d F Y') }}</p>
        <p>Selesai Izin : {{ \Carbon\Carbon::parse($permission->tanggal_selesai)->translatedFormat('d F Y') }}</p>
    </div>

    <div class="ttd">
        <div class="ttd-block">
            <p>Pontianak, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Hormat Saya,</p>
            <br><br><br>
            <strong>{{ $permission->user->nama ?? $permission->user->name }}</strong>
        </div>
    </div>
</body>
</html>
