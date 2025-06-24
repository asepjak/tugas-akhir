@extends('layouts.admin-app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Edit Akun Karyawan</h4>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.users.form', ['user' => $user])
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
