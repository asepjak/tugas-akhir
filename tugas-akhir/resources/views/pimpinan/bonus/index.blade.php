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
                    <th>Aksi</th>
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
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-warning"
                                        onclick="editBonus({{ $bonus->id }}, '{{ $bonus->user->name }}', '{{ $bonus->bulan }}', '{{ $bonus->tahun }}', {{ $bonus->jumlah_bonus }}, '{{ $bonus->keterangan }}')"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditBonus">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                        onclick="deleteBonus({{ $bonus->id }}, '{{ $bonus->user->name }}')"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeleteBonus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data bonus</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Bonus -->
<div class="modal fade" id="modalTambahBonus" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('pimpinan.bonus.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Bonus Karyawan</h5>
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

<!-- Modal Edit Bonus -->
<div class="modal fade" id="modalEditBonus" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEditBonus" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalEditLabel">Edit Bonus Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_user_name" class="form-label">Karyawan</label>
                            <input type="text" id="edit_user_name" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="edit_bulan" class="form-select" required>
                                @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $key => $bln)
                                    <option value="{{ $key }}">{{ $bln }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="edit_tahun" class="form-select" required>
                                @for ($i = now()->year - 3; $i <= now()->year + 1; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_jumlah_bonus" class="form-label">Jumlah Bonus (Rp)</label>
                            <input type="number"
                                   class="form-control"
                                   name="jumlah_bonus"
                                   id="edit_jumlah_bonus"
                                   required
                                   min="0"
                                   step="1000"
                                   placeholder="Contoh: 500000">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_keterangan" class="form-label">Keterangan (opsional)</label>
                            <textarea name="keterangan"
                                      id="edit_keterangan"
                                      class="form-control"
                                      rows="2"
                                      placeholder="Contoh: Bonus prestasi kerja"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete Bonus -->
<div class="modal fade" id="modalDeleteBonus" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formDeleteBonus" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalDeleteLabel">Hapus Bonus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus bonus untuk karyawan <strong id="delete_user_name"></strong>?</p>
                    <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
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

<script>
function editBonus(id, userName, bulan, tahun, jumlahBonus, keterangan) {
    // Set form action
    document.getElementById('formEditBonus').action = `/pimpinan/bonus/${id}`;

    // Fill form fields
    document.getElementById('edit_user_name').value = userName;
    document.getElementById('edit_bulan').value = bulan;
    document.getElementById('edit_tahun').value = tahun;
    document.getElementById('edit_jumlah_bonus').value = jumlahBonus;
    document.getElementById('edit_keterangan').value = keterangan || '';
}

function deleteBonus(id, userName) {
    // Set form action
    document.getElementById('formDeleteBonus').action = `/pimpinan/bonus/${id}`;

    // Set user name in confirmation message
    document.getElementById('delete_user_name').textContent = userName;
}
</script>
@endsection
