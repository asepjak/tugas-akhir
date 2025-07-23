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
        <!-- Informasi Hari Kerja -->
        <div class="alert alert-info mb-3">
            <i class="bi bi-calendar-week"></i>
            <strong>Jumlah Hari Kerja (Senin-Jumat):</strong> {{ $jumlahHariKerja }} hari
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th class="text-success">Hadir</th>
                        <th class="text-warning">Izin</th>
                        <th class="text-info">Sakit</th>
                        <th class="text-primary">Terlambat</th>
                        <th class="text-danger">Tanpa Keterangan</th>
                        <th class="bg-success text-white">Total Absen</th>
                        <th>Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $item['user']->nama ?? $item['user']->name }}</td>
                            <td class="text-center text-success fw-bold">{{ $item['jumlah_hadir'] }}</td>
                            <td class="text-center text-warning">{{ $item['jumlah_izin'] }}</td>
                            <td class="text-center text-info">{{ $item['jumlah_sakit'] }}</td>
                            <td class="text-center text-primary">{{ $item['jumlah_terlambat'] }}</td>
                            <td class="text-center text-danger fw-bold">{{ $item['tanpa_keterangan'] }}</td>
                            <td class="text-center bg-success text-white fw-bold">{{ $item['total_hadir_efektif'] }}</td>
                            <td class="text-center">
                                @php
                                    $persentase = $jumlahHariKerja > 0 ? round(($item['total_hadir_efektif'] / $jumlahHariKerja) * 100, 1) : 0;
                                @endphp
                                <span class="badge
                                    @if($persentase >= 90) bg-success
                                    @elseif($persentase >= 75) bg-warning
                                    @else bg-danger
                                    @endif">
                                    {{ $persentase }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2" class="text-center">TOTAL</th>
                        <th class="text-center text-success">{{ collect($data)->sum('jumlah_hadir') }}</th>
                        <th class="text-center text-warning">{{ collect($data)->sum('jumlah_izin') }}</th>
                        <th class="text-center text-info">{{ collect($data)->sum('jumlah_sakit') }}</th>
                        <th class="text-center text-primary">{{ collect($data)->sum('jumlah_terlambat') }}</th>
                        <th class="text-center text-danger">{{ collect($data)->sum('tanpa_keterangan') }}</th>
                        <th class="text-center bg-success text-white">{{ collect($data)->sum('total_hadir_efektif') }}</th>
                        <th class="text-center">
                            @php
                                $totalKaryawan = count($data);
                                $totalHariKerjaSeharusnya = $totalKaryawan * $jumlahHariKerja;
                                $totalHadirEfektif = collect($data)->sum('total_hadir_efektif');
                                $rataRataPersentase = $totalHariKerjaSeharusnya > 0 ? round(($totalHadirEfektif / $totalHariKerjaSeharusnya) * 100, 1) : 0;
                            @endphp
                            <span class="badge
                                @if($rataRataPersentase >= 90) bg-success
                                @elseif($rataRataPersentase >= 75) bg-warning
                                @else bg-danger
                                @endif">
                                {{ $rataRataPersentase }}%
                            </span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Keterangan -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Keterangan</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><span class="badge bg-success me-2">Total Absen</span> = Jumlah hari hadir + terlambat</li>
                            <li><span class="badge bg-warning me-2">Izin</span> = Tidak menambah total absen</li>
                            <li><span class="badge bg-info me-2">Sakit</span> = Tidak menambah total absen</li>
                            <li><span class="badge bg-danger me-2">Tanpa Keterangan</span> = Hari kerja - (absen + izin + sakit)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-graph-up"></i> Kriteria Persentase</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><span class="badge bg-success me-2">≥90%</span> Sangat Baik</li>
                            <li><span class="badge bg-warning me-2">75-89%</span> Cukup Baik</li>
                            <li><span class="badge bg-danger me-2">&lt;75%</span> Perlu Perbaikan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning mt-3">
            <i class="bi bi-info-circle"></i> Tidak ada data absensi untuk bulan {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}.
        </div>
    @endif
</div>

<style>
.table th {
    white-space: nowrap;
}
.badge {
    font-size: 0.8em;
}
</style>
@endsection
