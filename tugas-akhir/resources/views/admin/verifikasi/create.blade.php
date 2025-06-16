@extends('layouts.admin-app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Verifikasi Perizinan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('verifikasi.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Nama Karyawan</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama ?? $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hari</label>
                            <input type="text" name="hari" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <select name="keterangan" class="form-select" required>
                                <option value="">-- Pilih Keterangan --</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Detail</label>
                            <input type="text" name="detail" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Cuti (Opsional)</label>
                            <input type="date" name="tanggal_cuti" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Selesai Cuti (Opsional)</label>
                            <input type="date" name="selesai_cuti" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Hari Cuti (Opsional)</label>
                            <input type="number" name="jumlah_hari_cuti" class="form-control">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                            <a href="{{ route('verifikasi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
