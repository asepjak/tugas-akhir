@extends('layouts.admin-app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Tambah Akun Karyawan</h4>
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.users.form')
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
