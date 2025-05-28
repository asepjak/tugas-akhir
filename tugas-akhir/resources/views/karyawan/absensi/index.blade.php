{{-- resources/views/karyawan/absensi/index.blade.php --}}

@extends('layouts.app')

@section('content')
<style>
    .absensi-container {
        max-width: 800px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .absensi-container h1 {
        text-align: center;
        margin-bottom: 25px;
    }
    .alert-success, .alert-error {
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    .alert-success { background-color: #d4edda; color: #155724; }
    .alert-error { background-color: #f8d7da; color: #721c24; }
    .btn-absen {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        margin-right: 10px;
    }
    .btn-masuk { background-color: #28a745; color: white; }
    .btn-keluar { background-color: #dc3545; color: white; }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }
    th {
        background-color: #f8f9fa;
    }
</style>

<div class="absensi-container">
    <h1>Absensi Karyawan</h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tombol --}}
    @php
        $absenHariIni = $absensi->firstWhere('tanggal', \Carbon\Carbon::now()->toDateString());
    @endphp

    <div style="margin-bottom: 20px;">
        @if(!$absenHariIni)
            <form method="POST" action="{{ route('absensi.store') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-absen btn-masuk">Absen Masuk</button>
            </form>
        @endif

        @if($absenHariIni && !$absenHariIni->jam_keluar)
            <form method="POST" action="{{ route('absensi.keluar') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-absen btn-keluar">Absen Keluar</button>
            </form>
        @endif
    </div>

    {{-- Tabel Riwayat --}}
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensi as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->jam)->format('H:i:s') }}</td>
                    <td>
                        @if($item->jam_keluar)
                            {{ \Carbon\Carbon::parse($item->jam_keluar)->format('H:i:s') }}
                        @else
                            <span style="color:#aaa;">Belum keluar</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Belum ada data absensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
