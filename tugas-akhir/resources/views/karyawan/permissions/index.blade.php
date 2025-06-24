@extends('layouts.app')

@section('title', 'Ajuan Karyawan')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-uppercase text-center mb-4">Ajuan Karyawan</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('karyawan.permission.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="keterangan" class="form-select" required>
                            <option value="">-- Pilih Keterangan --</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Cuti">Cuti</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="alasan" class="form-control" placeholder="Alasan" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="file" name="file_surat" class="form-control" accept=".pdf,.jpg,.png">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success mt-2">Kirim Ajuan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Riwayat Izin --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Riwayat Izin</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Alasan</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Lampiran</th>
                            <th>Cetak</th>
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
                                @elseif ($izin->status == 'Disetujui')
                                    <span class="badge bg-success">Diterima</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if ($izin->file_surat)
                                    <a href="{{ asset('storage/' . $izin->file_surat) }}" target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                       <i class="bi bi-file-earmark-text"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('karyawan.permissions.print', $izin->id) }}" target="_blank"
                                   class="btn btn-sm btn-outline-secondary">
                                    <img src="{{ asset('assets/printer.png') }}" width="20"> Cetak
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data ajuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
