@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-uppercase text-center mb-4">Ajukan Perizinan</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
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
</div>
@endsection
