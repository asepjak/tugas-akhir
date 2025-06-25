@extends('layouts.admin-app')

@section('content')
    <style>
        .absensi-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .absensi-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-weight: 600;
        }

        .alert-success,
        .alert-error {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .clock-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }

        .clock-section h4 {
            margin: 0 0 10px 0;
            opacity: 0.9;
        }

        .clock-section h2 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-absen {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 12px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-masuk {
            background-color: #28a745;
            color: white;
        }

        .btn-masuk:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .btn-keluar {
            background-color: #dc3545;
            color: white;
        }

        .btn-keluar:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .btn-dev {
            background-color: #6f42c1;
            color: white;
            font-size: 12px;
            padding: 8px 16px;
        }

        .btn-dev:hover {
            background-color: #5a32a3;
        }

        .status-today {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .status-today h5 {
            margin: 0 0 8px 0;
            color: #495057;
        }

        .status-today p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .complete-status {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 14px 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .late-time {
            color: #dc3545;
            font-weight: bold;
            font-size: 12px;
        }

        .on-time {
            color: #28a745;
            font-size: 12px;
            font-weight: 500;
        }

        .time-late {
            color: #dc3545;
            font-weight: bold;
        }

        .time-normal {
            color: #28a745;
            font-weight: 500;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px;
        }

        .dev-tools {
            background: #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #6f42c1;
        }

        .dev-tools h6 {
            margin: 0 0 10px 0;
            color: #6f42c1;
            font-weight: 600;
        }

        .working-hours {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #856404;
        }

        /* Tambahan untuk debug */
        .debug-info {
            background: #f8f9fa;
            border: 2px solid #6c757d;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #495057;
        }

        @media (max-width: 768px) {
            .absensi-container {
                margin: 10px;
                padding: 20px;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px 6px;
            }

            .btn-absen {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>

    <div class="absensi-container">
        <h1>📋 Sistem Presensi Karyawan</h1>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        @php
            $absenHariIni = $absensi->firstWhere('tanggal', \Carbon\Carbon::now()->toDateString());
            $jamBatas = '08:30:00';
        @endphp

        {{-- Debug Info (hanya tampil di environment local) --}}
        {{-- @if (app()->environment('local'))
            <div class="debug-info">
                <strong>🐛 Debug Info:</strong><br>
                Tanggal hari ini: {{ \Carbon\Carbon::now()->toDateString() }}<br>
                Absen hari ini ada: {{ $absenHariIni ? 'Ya' : 'Tidak' }}<br>
                @if ($absenHariIni)
                    Jam masuk: {{ $absenHariIni->jam ?? 'null' }}<br>
                    Jam keluar: {{ $absenHariIni->jam_keluar ?? 'null' }}<br>
                    Status: {{ $absenHariIni->status ?? 'null' }}
                @endif
            </div>
        @endif --}}

        {{-- Jam realtime --}}
        <div class="clock-section">
            <h4 id="tanggal-hari-ini"></h4>
            <h4>⏰ Waktu Sekarang</h4>
            <h2 id="clock"></h2>
        </div>

        {{-- Info jam kerja --}}
        <div class="working-hours">
            <strong>ℹ️ Informasi:</strong> Jam masuk kerja adalah sebelum
            <strong>{{ \Carbon\Carbon::parse($jamBatas)->format('H:i') }}</strong>.
            Keterlambatan akan dicatat secara otomatis.
        </div>

        {{-- Status absensi hari ini --}}
        @if ($absenHariIni)
            @if ($absenHariIni->jam_keluar)
                {{-- Jika sudah absen masuk dan keluar --}}
                <div class="complete-status">
                    ✅ Anda sudah menyelesaikan absensi hari ini
                    <br>
                    <small>
                        Masuk: {{ \Carbon\Carbon::parse($absenHariIni->jam)->format('H:i:s') }} |
                        Keluar: {{ \Carbon\Carbon::parse($absenHariIni->jam_keluar)->format('H:i:s') }}
                    </small>
                </div>
            @else
                {{-- Jika sudah absen masuk tapi belum keluar --}}
                <div class="status-today">
                    <h5>📅 Status Absensi Hari Ini</h5>
                    <p>
                        <strong>Masuk:</strong> {{ \Carbon\Carbon::parse($absenHariIni->jam)->format('H:i:s') }}
                        @if ($absenHariIni->status === 'terlambat')
                            <span class="badge bg-danger">Terlambat</span>
                        @else
                            <span class="badge bg-success">Tepat Waktu</span>
                        @endif
                    </p>
                    <p><strong>Keluar:</strong> <em>Belum absen keluar</em></p>
                </div>
            @endif
        @endif

        {{-- Development Tools (hanya tampil di environment local) --}}
        @if (app()->environment('local'))
            <div class="dev-tools">
                <h6>🔧 Development Tools</h6>
                @if ($absenHariIni)
                    <form method="POST" action="{{ route('admin.absensi.reset') }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-absen btn-dev"
                            onclick="return confirm('Reset data absensi hari ini?')">Reset Hari Ini</button>
                    </form>
                @endif
                <a href="{{ route('admin.absensi.check-ip') }}" target="_blank" class="btn-absen btn-dev">Cek IP</a>
            </div>
        @endif

        @php
            use Carbon\Carbon;
            $today = Carbon::today()->format('Y-m-d');
            $absenHariIni = $absensi->first(function ($absen) use ($today) {
                return \Carbon\Carbon::parse($absen->tanggal)->format('Y-m-d') === $today;
            });
        @endphp
        <div style="margin-bottom: 25px; text-align: center;">
            @if (!$absenHariIni)
                {{-- Belum absen masuk sama sekali --}}
                <p style="margin-bottom: 15px; color: #495057; font-weight: 500;">
                    🚪 Silakan lakukan absen masuk
                </p>
                <form method="POST" action="{{ route('absensi.store') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-absen btn-masuk">
                        🚪 Waktu Masuk
                    </button>
                </form>
            @elseif (is_null($absenHariIni->jam_keluar))
                {{-- Sudah absen masuk tapi belum keluar --}}
                <p style="margin-bottom: 15px; color: #495057; font-weight: 500;">
                    🏠 Anda sudah absen masuk, silakan absen keluar
                </p>
                <form method="POST" action="{{ route('absensi.keluar') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-absen btn-keluar">
                        🏠 Waktu Pulang
                    </button>
                </form>
            @else
                {{-- Sudah absen masuk dan keluar --}}
                <p style="margin-bottom: 15px; color: #28a745; font-weight: 500;">
                    ✅ Absensi hari ini sudah lengkap
                </p>
            @endif
        </div>
        {{-- Tabel Riwayat --}}
        <table>
            <thead>
                <tr>
                    <th>📅 Tanggal</th>
                    <th>🕐 Jam Masuk</th>
                    <th>🕔 Jam Keluar</th>
                    <th>📊 Status</th>
                    <th>⏱️ Keterlambatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensi as $item)
                    @php
                        // Perbaikan logika keterlambatan di view
                        $tanggal = \Carbon\Carbon::parse($item->tanggal)->toDateString();
                        $jamMasuk = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $tanggal . ' ' . $item->jam);
                        $jamBatas = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $tanggal . ' ' . '08:30:00');
                        $isTerlambat = $jamMasuk->gt($jamBatas);

                        $jamBatas = '08:30:00';

                        $jamBatasCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $jamBatas);
                        $jamMasukCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $item->jam);
                        $isTerlambat = $jamMasukCarbon->gt($jamBatasCarbon);

                        $durasiTerlambat = 'Tepat waktu';
                        if ($isTerlambat) {
                            $selisih = $jamMasukCarbon->diff($jamBatasCarbon);

                            if ($selisih->h > 0) {
                                $durasiTerlambat = $selisih->format('%h jam %i menit');
                            } else {
                                $durasiTerlambat = $selisih->format('%i menit');
                            }
                        }

                        // Gunakan status dari database jika ada, atau hitung ulang
                        $statusFromDB = $item->status ?? ($isTerlambat ? 'terlambat' : 'hadir');
                    @endphp

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="{{ $isTerlambat ? 'time-late' : 'time-normal' }}">
                            {{ \Carbon\Carbon::parse($item->jam)->format('H:i:s') }}
                        </td>
                        <td>
                            @if ($item->jam_keluar)
                                {{ \Carbon\Carbon::parse($item->jam_keluar)->format('H:i:s') }}
                            @else
                                <span style="color:#6c757d; font-style: italic;">Belum keluar</span>
                            @endif
                        </td>
                        <td>
                            @if ($statusFromDB === 'terlambat')
                                <span class="badge bg-danger">Terlambat</span>
                            @else
                                <span class="badge bg-success">Hadir</span>
                            @endif
                        </td>
                        <td>
                            @if ($statusFromDB === 'terlambat')
                                <span class="late-time">{{ $item->durasi_terlambat ?? $durasiTerlambat }}</span>
                            @else
                                <span class="on-time">{{ $durasiTerlambat }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-data">
                            📝 Belum ada data absensi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- JavaScript untuk jam dan fitur tambahan --}}
    <script>
        function updateClock() {
            const now = new Date();
            const jam = now.getHours().toString().padStart(2, '0');
            const menit = now.getMinutes().toString().padStart(2, '0');
            const detik = now.getSeconds().toString().padStart(2, '0');

            document.getElementById('clock').textContent = `${jam}:${menit}:${detik}`;

            const hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const bulan = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            const tanggal = `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;
            document.getElementById("tanggal-hari-ini").textContent = tanggal;
        }

        // Update clock setiap detik
        setInterval(updateClock, 1000);
        updateClock();

        // Auto refresh halaman setiap 5 menit untuk sinkronisasi data
        setTimeout(() => {
            location.reload();
        }, 300000); // 5 menit

        // Konfirmasi sebelum absen keluar
        document.addEventListener('DOMContentLoaded', function() {
            const keluarBtn = document.querySelector('button[type="submit"].btn-keluar');
            if (keluarBtn) {
                keluarBtn.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin melakukan absen keluar?')) {
                        e.preventDefault();
                    }
                });
            }
        });

        // Notifikasi browser untuk reminder absen
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Cek apakah sudah absen masuk hari ini
        @if (!$absenHariIni)
            const now = new Date();
            const jam = now.getHours();
            const menit = now.getMinutes();

            // Reminder jika sudah lewat jam 08:30 tapi belum absen
            if ((jam > 8 || (jam === 8 && menit >= 30)) && jam < 12) {
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Reminder Absensi', {
                        body: 'Jangan lupa untuk melakukan absen masuk!',
                        icon: '/favicon.ico'
                    });
                }
            }
        @endif
    </script>
@endsection
