@extends('layouts.admin-app')

@section('content')
    <div class="container py-4">
        <h4 class="mb-3">Data Izin Karyawan</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Alasan</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                <th>Surat</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permissions as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->user->nama ?? $item->user->name }}</td>
                                    <td><span class="badge bg-info">{{ $item->keterangan }}</span></td>
                                    <td>{{ $item->alasan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($item->status === 'Menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif ($item->status === 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->file_surat)
                                            <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">Lihat Surat</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status === 'Menunggu')
                                            <form action="{{ route('permissions.updateStatus', $item->id) }}" method="POST"
                                                class="d-flex gap-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Disetujui">
                                                <button class="btn btn-sm btn-success" type="submit">Setujui</button>
                                            </form>
                                            <form action="{{ route('permissions.updateStatus', $item->id) }}" method="POST"
                                                class="d-flex gap-1 mt-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button class="btn btn-sm btn-danger" type="submit">Tolak</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Sudah {{ $item->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada data izin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
