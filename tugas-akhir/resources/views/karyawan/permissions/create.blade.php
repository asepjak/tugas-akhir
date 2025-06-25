@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Unggah Perizinan</h4>
    <form action="{{ route('karyawan.permission.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label>Keterangan</label>
                <select name="keterangan" class="form-control" required>
                    <option disabled selected>Pilih Keterangan</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Cuti">Cuti</option>
                </select>

                <label class="mt-3">Tanggal Izin</label>
                <input type="date" name="tanggal_mulai" class="form-control" required>

                <label class="mt-3">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" required>

                <label class="mt-3">Alasan</label>
                <textarea name="alasan" class="form-control" rows="3" required></textarea>

                <label class="mt-3">Upload Surat (jpg/pdf)</label>
                <input type="file" name="file_surat" class="form-control">
                <small class="text-danger">*upload file jpg, pdf</small>

                <button class="btn btn-primary mt-4">Upload</button>
            </div>
        </div>
    </form>
</div>
@endsection
