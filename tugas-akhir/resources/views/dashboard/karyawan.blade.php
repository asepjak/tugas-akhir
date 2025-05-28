@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-karyawan.css') }}?v={{ time() }}">
<style>
    .status-card {
        transition: transform 0.3s ease;
        border-radius: 15px;
    }

    .status-card:hover {
        transform: translateY(-5px);
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.75em;
        border-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5 fade-in">
    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase text-primary">Status Absensi Bulan Ini</h2>
        <form method="GET" action="{{ route('dashboard') }}" class="d-flex flex-wrap justify-content-center gap-3 mt-3">
            <select name="month" class="form-select w-auto">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                    </option>
                @endforeach
            </select>
            <input
                type="number"
                name="year"
                value="{{ $year }}"
                class="form-control w-auto"
                min="2000"
                max="{{ now()->year }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Filter
            </button>
        </form>
    </div>

    {{-- KARTU STATUS --}}
    <div class="row justify-content-center g-4">
        <div class="col-md-3">
            <div class="card status-card text-center shadow-sm border-0">
                <div class="card-body py-4">
                    <img src="{{ asset('assets/approval-stamp.png') }}" width="50" class="mb-3">
                    <h2 class="fw-bold text-success">{{ $hadir }}</h2>
                    <p class="text-muted mb-0">Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card status-card text-center shadow-sm border-0">
                <div class="card-body py-4">
                    <img src="{{ asset('assets/first-aid-kit.png') }}" width="50" class="mb-3">
                    <h2 class="fw-bold text-warning">{{ $sakit }}</h2>
                    <p class="text-muted mb-0">Sakit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card status-card text-center shadow-sm border-0">
                <div class="card-body py-4">
                    <img src="{{ asset('assets/off.png') }}" width="50" class="mb-3">
                    <h2 class="fw-bold text-info">{{ $izin }}</h2>
                    <p class="text-muted mb-0">Izin</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT IZIN --}}
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Riwayat Perizinan</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success fade-in">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive shadow-sm rounded fade-in">
            <table class="table table-striped table-hover align-middle" id="izinTable">
                <thead class="table-primary">
                    <tr>
                        <th>Keterangan</th>
                        <th>Alasan</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Status</th>
                        <th>Surat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permissions as $izin)
                        <tr>
                            <td>{{ $izin->keterangan }}</td>
                            <td>{{ $izin->alasan }}</td>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}</td>
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
                                    <a href="{{ asset('storage/' . $izin->file_surat) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-text"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data perizinan.</td>
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
