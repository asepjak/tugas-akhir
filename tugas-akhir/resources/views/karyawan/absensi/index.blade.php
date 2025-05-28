@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
<div class="container py-5">

    <h2 class="mb-4 fw-bold">Absensi Hari Ini</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <button class="btn btn-success btn-lg" type="submit">🕒 Absen Sekarang</button>
    </form>

    <hr class="my-5">

    <h4 class="fw-bold">Riwayat Absensi</h4>
    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensi as $data)
                <tr>
                    <td>{{ $data->tanggal }}</td>
                    <td>{{ $data->jam }}</td>
                    <td>{{ $data->ip_address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
