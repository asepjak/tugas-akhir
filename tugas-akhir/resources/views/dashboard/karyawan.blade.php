@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h4 class="fw-bold text-uppercase">Status Absensi Bulan Ini</h4>
            <p class="text-muted">Lorem ipsum dolor sit amet consectetur nunc nunc sit velit eget sollicitudin sit posuere augue vestibulum eget turpis lobortis donec sapien integer.</p>
        </div>
    </div>

    <div class="row justify-content-center text-center">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <img src="{{ asset('assets/approval-stamp.png') }}" alt="Hadir" width="40" class="mb-2">
                    <h2 class="fw-bold text-primary">20</h2>
                    <p class="text-muted mb-0">Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <img src="{{ asset('assets/first-aid-kit.png') }}" alt="Hadir" width="40" class="mb-2">
                    <h2 class="fw-bold text-warning">1</h2>
                    <p class="text-muted mb-0">Sakit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <img src="{{ asset('assets/off.png') }}" alt="Hadir" width="40" class="mb-2">
                    <h2 class="fw-bold text-info">9</h2>
                    <p class="text-muted mb-0">Izin</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
