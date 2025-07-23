<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #000;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        .sub-title {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
        }
        table th {
            background-color: #f0f0f0;
        }
        @media print {
            @page { margin: 25mm; }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>REKAP ABSENSI KARYAWAN</h2>
    <div class="sub-title">
        Bulan {{ \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Terlambat</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item['user']->nama ?? $item['user']->name }}</td>
                    <td>{{ $item['hadir'] }}</td>
                    <td>{{ $item['izin'] }}</td>
                    <td>{{ $item['sakit'] }}</td>
                    <td>{{ $item['terlambat'] }}</td>
                    <td>{{ $item['total'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
