@extends('layouts.admin-app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Tambah Rekap Absensi</h4>

    <form method="POST" action="{{ route('admin.rekap.store') }}" class="card shadow-sm p-4">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">Nama Karyawan</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Pilih --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->nama ?? $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hari" class="form-label">Hari</label>
            <input type="text" name="hari" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.rekap.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
