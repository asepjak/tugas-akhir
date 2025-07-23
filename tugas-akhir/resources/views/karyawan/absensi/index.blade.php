@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --info-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .absensi-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #2d3748;
        }

        .absensi-container h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-left: 4px solid transparent;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-left-color: #22c55e;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border-left-color: #ef4444;
        }

        .clock-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.75rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: var(--border-radius);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .clock-section::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .clock-section h4 {
            margin: 0 0 0.5rem 0;
            opacity: 0.9;
            font-weight: 500;
            position: relative;
        }

        .clock-section h2 {
            margin: 0;
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 180px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.25);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #db2777;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(247, 37, 133, 0.25);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .status-card {
            background: #fff;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .status-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-card.complete {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left-color: #22c55e;
            text-align: center;
        }

        .status-card h5 {
            margin: 0 0 0.75rem 0;
            color: var(--dark-color);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-card p {
            margin: 0.5rem 0;
            font-size: 0.95rem;
            color: #4a5568;
        }

        .status-card .time-detail {
            display: flex;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .status-card .time-detail div {
            flex: 1;
            background: rgba(255, 255, 255, 0.7);
            padding: 0.75rem;
            border-radius: 8px;
            text-align: center;
        }

        .status-card .time-detail strong {
            display: block;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .info-box {
            background: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--info-color);
            display: flex;
            gap: 1rem;
        }

        .info-box .icon {
            font-size: 1.5rem;
            color: var(--info-color);
            flex-shrink: 0;
        }

        .info-box p {
            margin: 0;
            color: #4a5568;
        }

        .info-box strong {
            color: var(--dark-color);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 2rem;
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 1rem 1.25rem;
            text-align: center;
            border-bottom: 1px solid #edf2f7;
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        tr:not(:last-child) td {
            border-bottom: 1px solid #edf2f7;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        .late-time {
            color: var(--danger-color);
            font-weight: 600;
        }

        .on-time {
            color: #166534;
            font-weight: 500;
        }

        .no-data {
            text-align: center;
            color: #64748b;
            padding: 3rem;
            font-size: 0.95rem;
        }

        .no-data svg {
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .dev-tools {
            background: #f8f0ff;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 2rem;
            border-left: 4px solid #8b5cf6;
        }

        .dev-tools h6 {
            margin: 0 0 1rem 0;
            color: #7c3aed;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dev-tools .tools {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .absensi-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .clock-section h2 {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .status-card .time-detail {
                flex-direction: column;
                gap: 0.75rem;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 0.75rem 0.5rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .absensi-container > * {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(67, 97, 238, 0); }
            100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
        }
    </style>

    <div class="absensi-container">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
            </svg>
            Sistem Presensi Karyawan
        </h1>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @php
            $absenHariIni = $absensi->firstWhere('tanggal', \Carbon\Carbon::now()->toDateString());
            $jamBatas = '08:30:00';
        @endphp

        {{-- Jam realtime --}}
        <div class="clock-section">
            <h4 id="tanggal-hari-ini"></h4>
            <h4>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Waktu Sekarang
            </h4>
            <h2 id="clock"></h2>
        </div>

        {{-- Info jam kerja --}}
        <div class="info-box">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 8v4l3 3"></path>
                </svg>
            </div>
            <p>
                <strong>Informasi Jam Kerja:</strong> Absen masuk sebelum <strong>{{ \Carbon\Carbon::parse($jamBatas)->format('H:i') }}</strong> dianggap tepat waktu. Keterlambatan akan dicatat secara otomatis. Jam kerja normal adalah 08:00 - 17:00 dengan istirahat 12:00 - 13:00.
            </p>
        </div>

        {{-- Status absensi hari ini --}}
        @if ($absenHariIni)
            @if ($absenHariIni->jam_keluar)
                {{-- Jika sudah absen masuk dan keluar --}}
                <div class="status-card complete">
                    <h5>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Absensi Hari Ini Lengkap
                    </h5>
                    <div class="time-detail">
                        <div>
                            <strong>Jam Masuk</strong>
                            <span>{{ \Carbon\Carbon::parse($absenHariIni->jam)->format('H:i:s') }}</span>
                            @if ($absenHariIni->status === 'terlambat')
                                <div class="badge badge-danger mt-1">Terlambat</div>
                            @else
                                <div class="badge badge-success mt-1">Tepat Waktu</div>
                            @endif
                        </div>
                        <div>
                            <strong>Jam Keluar</strong>
                            <span>{{ \Carbon\Carbon::parse($absenHariIni->jam_keluar)->format('H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- Jika sudah absen masuk tapi belum keluar --}}
                <div class="status-card">
                    <h5>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Status Absensi Hari Ini
                    </h5>
                    <p><strong>Masuk:</strong> {{ \Carbon\Carbon::parse($absenHariIni->jam)->format('H:i:s') }}</p>
                    <p><strong>Status:</strong>
                        @if ($absenHariIni->status === 'terlambat')
                            <span class="badge badge-danger">Terlambat</span>
                        @else
                            <span class="badge badge-success">Tepat Waktu</span>
                        @endif
                    </p>
                    <p><strong>Keluar:</strong> <em>Belum melakukan absen keluar</em></p>
                </div>
            @endif
        @endif

        {{-- Development Tools (hanya tampil di environment local) --}}
        @if (app()->environment('local'))
            <div class="dev-tools">
                <h6>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                    Development Tools
                </h6>
                <div class="tools">
                    @if ($absenHariIni)
                        <form method="POST" action="{{ route('absensi.reset') }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" onclick="return confirm('Reset data absensi hari ini?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="1 4 1 10 7 10"></polyline>
                                    <polyline points="23 20 23 14 17 14"></polyline>
                                    <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                                </svg>
                                Reset Hari Ini
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('karyawan.absensi.check-ip') }}" target="_blank" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="4"></circle>
                            <line x1="4.93" y1="4.93" x2="9.17" y2="9.17"></line>
                            <line x1="14.83" y1="14.83" x2="19.07" y2="19.07"></line>
                            <line x1="14.83" y1="9.17" x2="19.07" y2="4.93"></line>
                            <line x1="14.83" y1="9.17" x2="18.36" y2="5.64"></line>
                            <line x1="4.93" y1="19.07" x2="9.17" y2="14.83"></line>
                        </svg>
                        Cek IP
                    </a>
                </div>
            </div>
        @endif

        @php
            use Carbon\Carbon;
            $today = Carbon::today()->format('Y-m-d');
            $absenHariIni = $absensi->first(function ($absen) use ($today) {
                return \Carbon\Carbon::parse($absen->tanggal)->format('Y-m-d') === $today;
            });
        @endphp

        <div class="action-buttons">
            @if (!$absenHariIni)
                {{-- Belum absen masuk sama sekali --}}
                <form method="POST" action="{{ route('absensi.store') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Absen Masuk
                    </button>
                </form>
            @elseif (is_null($absenHariIni->jam_keluar))
                {{-- Sudah absen masuk tapi belum keluar --}}
                <form method="POST" action="{{ route('absensi.keluar') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 3H6a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h4M16 17l5-5-5-5M19.8 12H9"></path>
                        </svg>
                        Absen Keluar
                    </button>
                </form>
            @else
                {{-- Sudah absen masuk dan keluar --}}
                <button class="btn btn-secondary" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Absensi Hari Ini Selesai
                </button>
            @endif
        </div>

        {{-- Tabel Riwayat --}}
        <table>
            <thead>
                <tr>
                    <th>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Tanggal
                    </th>
                    <th>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Jam Masuk
                    </th>
                    <th>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Jam Keluar
                    </th>
                    <th>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"></path>
                            <path d="M8.5 8.5v.01"></path>
                            <path d="M16 15.5v.01"></path>
                            <path d="M12 12v.01"></path>
                            <path d="M11 17v.01"></path>
                            <path d="M7 14v.01"></path>
                        </svg>
                        Status
                    </th>
                    <th>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 8v4l3 3"></path>
                        </svg>
                        Keterlambatan
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensi as $item)
                    @php
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

                        $statusFromDB = $item->status ?? ($isTerlambat ? 'terlambat' : 'hadir');
                    @endphp

                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="{{ $isTerlambat ? 'late-time' : 'on-time' }}">
                            {{ \Carbon\Carbon::parse($item->jam)->format('H:i:s') }}
                        </td>
                        <td>
                            @if ($item->jam_keluar)
                                {{ \Carbon\Carbon::parse($item->jam_keluar)->format('H:i:s') }}
                            @else
                                <span style="color:#94a3b8; font-style: italic;">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($statusFromDB === 'terlambat')
                                <span class="badge badge-danger">Terlambat</span>
                            @else
                                <span class="badge badge-success">Hadir</span>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <div>Belum ada data absensi</div>
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
            const keluarBtn = document.querySelector('button[type="submit"].btn-danger');
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
