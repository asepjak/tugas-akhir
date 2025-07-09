@extends('layouts.admin-app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">📊 Rekap Absensi Bulanan</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.rekap.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success">
                <i class="bi bi-download"></i> Export Excel
            </a>
            <a href="{{ route('admin.rekap.bulanan.print', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank"
                class="btn btn-outline-secondary">
                <img src="{{ asset('assets/printer.png') }}" width="24" class="me-1"> Cetak
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.rekap.bulanan') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                        {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="number" name="tahun" id="tahun" class="form-control" value="{{ $tahun }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Tampilkan
            </button>
        </div>
    </form>

    @if (count($data) > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Terlambat</th>
                        <th class="text-danger">Tanpa Keterangan</th>
                        <th>Total Absen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $item['user']->nama ?? $item['user']->name }}</td>
                            <td class="text-center">{{ $item['jumlah_hadir'] }}</td>
                            <td class="text-center">{{ $item['jumlah_izin'] }}</td>
                            <td class="text-center">{{ $item['jumlah_sakit'] }}</td>
                            <td class="text-center">{{ $item['jumlah_terlambat'] }}</td>
                            <td class="text-center text-danger">{{ $item['tanpa_keterangan'] }}</td>
                            <td class="text-center fw-bold">{{ $item['jumlah_total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning mt-3">
            <i class="bi bi-info-circle"></i> Tidak ada data absensi bulan ini.
        </div>
    @endif
</div>
@endsection
