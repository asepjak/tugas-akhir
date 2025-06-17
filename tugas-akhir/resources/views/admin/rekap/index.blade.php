@extends('layouts.admin-app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Rekap Absensi Karyawan</h4>
    <a href="{{ route('admin.rekap.create') }}" class="btn btn-success mb-3">+ Tambah Rekap</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $i => $r)
                    <tr>
                        <td>{{ $rekap->firstItem() + $i }}</td>
                        <td>{{ $r->user->nama ?? $r->user->name }}</td>
                        <td>{{ $r->tanggal }}</td>
                        <td>{{ $r->hari }}</td>
                        <td>{{ $r->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $rekap->links() }}
    </div>
</div>
@endsection
