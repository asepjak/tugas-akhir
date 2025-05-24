@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-karyawan.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase">Status Absensi Bulan Ini</h2>
        <form method="GET" action="{{ route('dashboard') }}" class="d-flex justify-content-center gap-3 mt-3">
            <select name="month" class="form-select w-auto">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>
            <input
                type="number"
                name="year"
                value="{{ $year }}"
                class="form-control w-auto"
                min="2000"
                max="{{ now()->year }}"
            >

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Filter
            </button>
        </form>
    </div>

    {{-- KARTU STATUS --}}
    <div class="row justify-content-center g-4">
        <div class="col-md-3">
            <div class="card shadow-lg border-0 text-center hover-shadow transition">
                <div class="card-body py-4">
                    <img src="{{ asset('assets/approval-stamp.png') }}" width="50" class="mb-3 fade-in">
                    <h2 class="fw-bold text-primary">{{ $hadir }}</h2>
                    <p class="text-muted">Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-0 text-center hover-shadow transition">
                <div class="card-body py-4">
                    <img src="{{ asset('assets/first-aid-kit.png') }}" width="50" class="mb-3 fade-in">
                    <h2 class="fw-bold text-warning">{{ $sakit }}</h2>
                    <p class="text-muted">Sakit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-0 text-center hover-shadow transition">
                <div class="card-body py-4">
                    <img src="{{ asset('assets/off.png') }}" width="50" class="mb-3 fade-in">
                    <h2 class="fw-bold text-info">{{ $izin }}</h2>
                    <p class="text-muted">Izin</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT IZIN --}}
    <div class="mt-5 fade-in">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 fw-bold">Riwayat Perizinan</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success fade-in">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle" id="izinTable">
                <thead class="table-light">
                    <tr>
                        <th>Keterangan</th>
                        <th>Alasan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Surat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permissions as $izin)
                    <tr>
                        <td>{{ $izin->keterangan }}</td>
                        <td>{{ $izin->alasan }}</td>
                        <td>{{ $izin->tanggal_mulai }}</td>
                        <td>{{ $izin->tanggal_selesai }}</td>
                        <td>
                            @if ($izin->status == 'Menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif ($izin->status == 'Diterima')
                                <span class="badge bg-success">Diterima</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if ($izin->file_surat)
                                <a href="{{ asset('storage/' . $izin->file_surat) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada perizinan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#izinTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ditemukan data yang cocok",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    });
</script>
@endpush
