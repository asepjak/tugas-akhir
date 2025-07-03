@extends('layouts.pimpinan-app')

@section('title', 'Data Bonus Karyawan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Bonus Karyawan</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBonus">
            <i class="fas fa-plus"></i> Tambah Bonus
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Bulan & Tahun -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select">
                @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $key => $bln)
                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $bln }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="tahun" class="form-label">Tahun</label>
            <select name="tahun" id="tahun" class="form-select">
                @for ($i = now()->year - 3; $i <= now()->year + 1; $i++)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-secondary w-100">
                <i class="fas fa-filter"></i> Tampilkan
            </button>
        </div>
    </form>

    <!-- Tabel Bonus -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Karyawan</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Jumlah Bonus</th>
                    <th>Keterangan</th>
                    <th>Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bonusData as $bonus)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bonus->user->name }}</td>
                        <td>{{ \Carbon\Carbon::create()->month($bonus->bulan)->locale('id')->monthName }}</td>
                        <td>{{ $bonus->tahun }}</td>
                        <td>Rp{{ number_format($bonus->jumlah_bonus, 0, ',', '.') }}</td>
                        <td>{{ $bonus->keterangan ?? '-' }}</td>
                        <td>{{ $bonus->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data bonus</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Bonus -->
<div class="modal fade" id="modalTambahBonus" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('pimpinan.bonus.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel">Tambah Bonus Karyawan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="user_id" class="form-label">Pilih Karyawan</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="bulan_input" class="form-label">Bulan</label>
                            <select name="bulan" id="bulan_input" class="form-select @error('bulan') is-invalid @enderror" required>
                                @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $key => $bln)
                                    <option value="{{ $key }}" {{ old('bulan', now()->format('m')) == $key ? 'selected' : '' }}>
                                        {{ $bln }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="tahun_input" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun_input" class="form-select @error('tahun') is-invalid @enderror" required>
                                @for ($i = now()->year - 3; $i <= now()->year + 1; $i++)
                                    <option value="{{ $i }}" {{ old('tahun', now()->format('Y')) == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jumlah_bonus" class="form-label">Jumlah Bonus (Rp)</label>
                            <input type="number"
                                   class="form-control @error('jumlah_bonus') is-invalid @enderror"
                                   name="jumlah_bonus"
                                   id="jumlah_bonus"
                                   value="{{ old('jumlah_bonus') }}"
                                   required
                                   min="0"
                                   step="1000"
                                   placeholder="Contoh: 500000">
                            @error('jumlah_bonus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
                            <textarea name="keterangan"
                                      id="keterangan"
                                      class="form-control @error('keterangan') is-invalid @enderror"
                                      rows="2"
                                      placeholder="Contoh: Bonus prestasi kerja">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalTambahBonus'));
        modal.show();
    });
</script>
@endif
@endsection
