@extends('layouts.layouts-error')

@section('title', '404 Not Found')

@section('content')
<div class="text-center my-5">
    <h1 class="display-1">404</h1>
    <p class="lead">Halaman tidak ditemukan atau sedang dalam perbaikan.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
