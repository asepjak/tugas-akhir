@extends('layouts.pimpinan-app')

@section('title', 'Riwayat Pengajuan Cuti & Dinas')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Riwayat Pengajuan Cuti & Perjalanan Dinas</h4>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Periode</th>
                            <th>Alasan</th>
                            <th>Lampiran</th>
                            <th>Status</th>
                            <th>Waktu Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->user->nama ?? $item->user->name }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</td>
                                <td>{{ $item->alasan ?? '-' }}</td>
                                <td>
                                    @if ($item->file_surat)
                                        <a href="{{ asset('storage/' . $item->file_surat) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bi bi-file-earmark-text"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == 'Disetujui')
                                        <span class="badge bg-success">{{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td><small>{{ $item->created_at->format('d M Y H:i') }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data pengajuan yang disetujui atau ditolak.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
