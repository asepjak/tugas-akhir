@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Profil</h4>
    <div class="row">
        <div class="col-md-4 text-center">
            <div class="border bg-light p-5">
                @if ($user->foto)
                    <img id="fotoPreview" src="{{ asset('storage/' . $user->foto) }}" class="img-fluid mb-3" alt="Foto Profil">
                @else
                    <img id="fotoPreview" src="{{ asset('default-profile.png') }}" class="img-fluid mb-3" alt="Foto Profil">
                @endif

                <input type="file" name="foto" id="fotoInput" class="form-control mb-3" accept="image/*" style="display:none;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('fotoInput').click()">Ambil Foto / Pilih Foto</button>
            </div>
        </div>
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Nama</label>
                        <input class="form-control" name="nama" value="{{ old('nama', $user->nama) }}">
                        <label class="mt-3">Alamat</label>
                        <input class="form-control" name="alamat" value="{{ old('alamat', $user->alamat) }}">
                        <label class="mt-3">Email</label>
                        <input class="form-control" name="email" value="{{ old('email', $user->email) }}">
                        <label class="mt-3">No. HP</label>
                        <input class="form-control" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
                    </div>
                    <div class="col-md-6">
                        <label>Jabatan</label>
                        <input class="form-control" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                        <label class="mt-3">Status</label>
                        <input class="form-control" name="status" value="{{ old('status', $user->status) }}">
                        <label class="mt-3">Username</label>
                        <input class="form-control" name="username" value="{{ old('username', $user->username) }}">
                        <label class="mt-3">Password (kosongkan jika tidak ingin ganti)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                <button class="btn btn-primary mt-4">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('fotoInput').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('fotoPreview');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
