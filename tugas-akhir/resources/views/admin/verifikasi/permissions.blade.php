@extends('layouts.admin-app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="fas fa-user-clock me-2"></i>Data Izin Karyawan</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="bg-dark text-white text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis Izin</th>
                                <th>Alasan</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                <th>Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permissions as $item)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $item->user->nama ?? $item->user->name }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $item->keterangan }}</span></td>
                                    <td class="text-start">{{ $item->alasan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</td>
                                    <td>
                                        @if ($item->status === 'Menunggu')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                        @elseif ($item->status === 'Disetujui')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Disetujui</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->file_surat)
                                            <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-alt me-1"></i>Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status === 'Menunggu')
                                            <div class="d-flex flex-column gap-1">
                                                <form action="{{ route('permissions.updateStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Disetujui">
                                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                                        <i class="fas fa-check me-1"></i>Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('permissions.updateStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Ditolak">
                                                    <button type="submit" class="btn btn-sm btn-danger w-100">
                                                        <i class="fas fa-times me-1"></i>Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted"><i class="fas fa-check-circle me-1"></i>{{ $item->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-1"></i>Belum ada data izin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
