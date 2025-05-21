@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Profil</h4>
    <div class="row">
        <div class="col-md-4 text-center">
            <div class="border bg-light p-5">
                <img src="{{ asset('default-profile.png') }}" class="img-fluid mb-3" alt="Foto Profil">
                <button class="btn btn-secondary">ambil foto</button>
            </div>
        </div>
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Nama</label>
                        <input class="form-control" name="nama" value="{{ $user->nama }}">
                        <label class="mt-3">Alamat</label>
                        <input class="form-control" name="alamat" value="{{ $user->alamat }}">
                        <label class="mt-3">Email</label>
                        <input class="form-control" name="email" value="{{ $user->email }}">
                        <label class="mt-3">No. HP</label>
                        <input class="form-control" name="no_hp" value="{{ $user->no_hp }}">
                    </div>
                    <div class="col-md-6">
                        <label>Jabatan</label>
                        <input class="form-control" name="jabatan" value="{{ $user->jabatan }}">
                        <label class="mt-3">Status</label>
                        <input class="form-control" name="status" value="{{ $user->status }}">
                        <label class="mt-3">Username</label>
                        <input class="form-control" name="username" value="{{ $user->username }}">
                        <label class="mt-3">Password (kosongkan jika tidak ingin ganti)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                <button class="btn btn-primary mt-4">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
