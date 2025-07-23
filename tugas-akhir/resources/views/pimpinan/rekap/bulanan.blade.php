@extends('layouts.pimpinan-app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">📊 Rekap Absensi Bulanan</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('pimpinan.rekap.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export Excel
            </a>
            <a href="{{ route('pimpinan.rekap.print', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank"
                class="btn btn-outline-secondary">
                <i class="fas fa-print"></i> Cetak
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('pimpinan.rekap.bulanan') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select">
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                        {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="number" name="tahun" id="tahun" class="form-control" value="{{ $tahun }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Tampilkan
            </button>
        </div>
    </form>

    @if (count($data) > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Terlambat</th>
                        <th class="text-danger">Tanpa Keterangan</th>
                        <th>Total Absen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $item['user']->nama ?? $item['user']->name }}</td>
                            <td class="text-center">{{ $item['jumlah_hadir'] }}</td>
                            <td class="text-center">{{ $item['jumlah_izin'] }}</td>
                            <td class="text-center">{{ $item['jumlah_sakit'] }}</td>
                            <td class="text-center">{{ $item['jumlah_terlambat'] }}</td>
                            <td class="text-center text-danger">{{ $item['tanpa_keterangan'] }}</td>
                            <td class="text-center fw-bold">{{ $item['total_keseluruhan'] }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info detail-btn"
                                        data-user-id="{{ $item['user']->id }}"
                                        data-bulan="{{ $bulan }}"
                                        data-tahun="{{ $tahun }}">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning mt-3">
            <i class="fas fa-info-circle"></i> Tidak ada data absensi bulan ini.
        </div>
    @endif
</div>

<!-- Modal for Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Absensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Nama Karyawan: <span id="detail-nama"></span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6>Periode: <span id="detail-periode"></span></h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Detail Izin</h6>
                            </div>
                            <div class="card-body" id="detail-izin">
                                <!-- Content will be loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Detail Sakit</h6>
                            </div>
                            <div class="card-body" id="detail-sakit">
                                <!-- Content will be loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Ringkasan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p>Total Hadir Efektif: <span id="summary-hadir" class="fw-bold"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p>Total Tidak Masuk: <span id="summary-tidak-masuk" class="fw-bold"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p>Total Keseluruhan: <span id="summary-total" class="fw-bold"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle detail button click
    $('.detail-btn').click(function() {
        const userId = $(this).data('user-id');
        const bulan = $(this).data('bulan');
        const tahun = $(this).data('tahun');

        // Show loading state
        $('#detail-nama').text('Memuat...');
        $('#detail-periode').text(`${bulan}/${tahun}`);
        $('#detail-izin').html('<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</p>');
        $('#detail-sakit').html('<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</p>');

        // Fetch data via AJAX
        $.ajax({
            url: '{{ route("pimpinan.rekap.detail") }}',
            method: 'GET',
            data: {
                user_id: userId,
                bulan: bulan,
                tahun: tahun
            },
            success: function(response) {
                // Update modal content
                $('#detail-nama').text(response.user.nama || response.user.name);
                $('#detail-periode').text(`${response.bulan}/${response.tahun}`);

                // Update izin details
                if (response.detail_izin.length > 0) {
                    let izinHtml = '<ul class="list-group list-group-flush">';
                    response.detail_izin.forEach(item => {
                        izinHtml += `
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>${item.tanggal_mulai} - ${item.tanggal_selesai}</span>
                                    <span class="badge bg-primary">${item.jumlah_hari} hari</span>
                                </div>
                                <div class="text-muted mt-1">${item.alasan || 'Tidak ada keterangan'}</div>
                            </li>
                        `;
                    });
                    izinHtml += '</ul>';
                    $('#detail-izin').html(izinHtml);
                } else {
                    $('#detail-izin').html('<p class="text-muted">Tidak ada data izin</p>');
                }

                // Update sakit details
                if (response.detail_sakit.length > 0) {
                    let sakitHtml = '<ul class="list-group list-group-flush">';
                    response.detail_sakit.forEach(item => {
                        sakitHtml += `
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>${item.tanggal_mulai} - ${item.tanggal_selesai}</span>
                                    <span class="badge bg-warning text-dark">${item.jumlah_hari} hari</span>
                                </div>
                                <div class="text-muted mt-1">${item.alasan || 'Tidak ada keterangan'}</div>
                            </li>
                        `;
                    });
                    sakitHtml += '</ul>';
                    $('#detail-sakit').html(sakitHtml);
                } else {
                    $('#detail-sakit').html('<p class="text-muted">Tidak ada data sakit</p>');
                }

                // Update summary
                $('#summary-hadir').text(response.summary.total_hadir_efektif);
                $('#summary-tidak-masuk').text(response.summary.total_tidak_masuk);
                $('#summary-total').text(response.summary.total_keseluruhan);

                // Show modal
                $('#detailModal').modal('show');
            },
            error: function() {
                alert('Gagal memuat data detail');
            }
        });
    });
});
</script>
@endpush
