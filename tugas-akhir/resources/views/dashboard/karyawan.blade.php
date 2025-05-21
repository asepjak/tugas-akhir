@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h4 class="fw-bold text-uppercase">Status Absensi Bulan Ini</h4>

            <!-- Form filter bulan dan tahun -->
            <form method="GET" action="{{ route('dashboard') }}" class="d-flex justify-content-center gap-3 mb-3">
                <select name="month" class="form-select" style="width: auto;">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>

                <input
                    type="number"
                    name="year"
                    value="{{ $year }}"
                    class="form-control"
                    style="width: 100px;"
                    min="2000" max="{{ now()->year }}"
                >

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <div class="row justify-content-center text-center">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <img src="{{ asset('assets/approval-stamp.png') }}" alt="Hadir" width="40" class="mb-2">
                    <h2 class="fw-bold text-primary">{{ $hadir }}</h2>
                    <p class="text-muted mb-0">Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <img src="{{ asset('assets/first-aid-kit.png') }}" alt="Sakit" width="40" class="mb-2">
                    <h2 class="fw-bold text-warning">{{ $sakit }}</h2>
                    <p class="text-muted mb-0">Sakit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <img src="{{ asset('assets/off.png') }}" alt="Izin" width="40" class="mb-2">
                    <h2 class="fw-bold text-info">{{ $izin }}</h2>
                    <p class="text-muted mb-0">Izin</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
