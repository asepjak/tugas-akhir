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
                                        type="button"
                                        data-user-id="{{ $item['user']->id }}"
                                        data-bulan="{{ $bulan }}"
                                        data-tahun="{{ $tahun }}"
                                        title="Lihat Detail Absensi">
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
                        <h6>Nama Karyawan: <span id="detail-nama">-</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6>Periode: <span id="detail-periode">-</span></h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-info text-white">
                                <strong>Detail Izin</strong>
                            </div>
                            <div class="card-body p-2" id="detail-izin">
                                <div class="text-center text-muted">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <strong>Detail Sakit</strong>
                            </div>
                            <div class="card-body p-2" id="detail-sakit">
                                <div class="text-center text-muted">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <strong>Ringkasan Kehadiran</strong>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Total Hadir Efektif:</dt>
                            <dd class="col-sm-8" id="summary-hadir">-</dd>

                            <dt class="col-sm-4">Total Tidak Masuk:</dt>
                            <dd class="col-sm-8" id="summary-tidak-masuk">-</dd>

                            <dt class="col-sm-4">Total Hari Kerja:</dt>
                            <dd class="col-sm-8" id="summary-total">-</dd>
                        </dl>
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

@push('styles')
<style>
.detail-btn {
    cursor: pointer;
    transition: all 0.3s ease;
}

.detail-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.detail-btn:active {
    transform: translateY(0);
}

.modal-dialog {
    max-width: 90%;
}

@media (min-width: 992px) {
    .modal-dialog {
        max-width: 900px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');

    // Pastikan Bootstrap dan jQuery tersedia
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
        return;
    }

    // Event listener untuk tombol detail
    document.addEventListener('click', function(e) {
        // Cek apakah yang diklik adalah tombol detail atau elemen di dalamnya
        const detailBtn = e.target.closest('.detail-btn');

        if (detailBtn) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Detail button clicked');

            const userId = detailBtn.getAttribute('data-user-id');
            const bulan = detailBtn.getAttribute('data-bulan');
            const tahun = detailBtn.getAttribute('data-tahun');

            console.log('Data:', {userId, bulan, tahun});

            if (!userId || !bulan || !tahun) {
                alert('Data tidak lengkap');
                return;
            }

            // Reset modal content
            document.getElementById('detail-nama').textContent = 'Memuat...';
            document.getElementById('detail-periode').textContent = `${bulan}/${tahun}`;

            const loadingHtml = '<div class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>';
            document.getElementById('detail-izin').innerHTML = loadingHtml;
            document.getElementById('detail-sakit').innerHTML = loadingHtml;

            document.getElementById('summary-hadir').textContent = '-';
            document.getElementById('summary-tidak-masuk').textContent = '-';
            document.getElementById('summary-total').textContent = '-';

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();

            // Fetch data
            fetchDetailData(userId, bulan, tahun);
        }
    });

    function fetchDetailData(userId, bulan, tahun) {
        // Buat URL dengan parameter - sesuaikan dengan route yang ada
        const baseUrl = '{{ url('/') }}';
        const url = new URL(baseUrl + '/pimpinan/rekap/detail');
        url.searchParams.append('user_id', userId);
        url.searchParams.append('bulan', bulan);
        url.searchParams.append('tahun', tahun);

        console.log('Fetching URL:', url.toString());

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            // Clone response to read as text for debugging
            return response.clone().text().then(text => {
                console.log('Raw response:', text);

                // Try to parse as JSON
                try {
                    const data = JSON.parse(text);
                    if (!response.ok) {
                        throw new Error(data.message || `HTTP error! status: ${response.status}`);
                    }
                    return data;
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.error('Response text:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            console.log('Parsed response data:', data);

            if (data.success) {
                updateModalContent(data);
            } else {
                throw new Error(data.message || 'Server returned success: false');
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);

            const errorHtml = `<p class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${error.message}</p>`;
            document.getElementById('detail-izin').innerHTML = errorHtml;
            document.getElementById('detail-sakit').innerHTML = errorHtml;

            // Show more user-friendly error message
            if (error.message.includes('404')) {
                alert('Data tidak ditemukan atau route belum terdaftar');
            } else if (error.message.includes('500')) {
                alert('Server error - silakan coba lagi atau hubungi administrator');
            } else {
                alert('Gagal memuat data: ' + error.message);
            }
        });
    }

    function updateModalContent(response) {
        // Update nama dan periode
        document.getElementById('detail-nama').textContent = response.user.nama || response.user.name || 'N/A';
        document.getElementById('detail-periode').textContent = `${response.bulan}/${response.tahun}`;

        // Update detail izin
        updateDetailSection('detail-izin', response.detail_izin, 'primary', 'Tidak ada data izin');

        // Update detail sakit
        updateDetailSection('detail-sakit', response.detail_sakit, 'warning', 'Tidak ada data sakit', 'text-dark');

        // Update summary
        if (response.summary) {
            document.getElementById('summary-hadir').textContent = (response.summary.total_hadir_efektif || 0) + ' hari';
            document.getElementById('summary-tidak-masuk').textContent = (response.summary.total_tidak_masuk || 0) + ' hari';
            document.getElementById('summary-total').textContent = (response.summary.total_keseluruhan || 0) + ' hari';
        }
    }

    function updateDetailSection(elementId, data, badgeClass, emptyMessage, textClass = '') {
        const element = document.getElementById(elementId);

        if (data && data.length > 0) {
            let html = '<ul class="list-group list-group-flush">';

            data.forEach(item => {
                html += `
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-medium">${item.tanggal_mulai} - ${item.tanggal_selesai}</span>
                        <span class="badge bg-${badgeClass} ${textClass}">${item.jumlah_hari} hari</span>
                    </div>
                    <div class="text-muted small mt-1">${item.alasan || 'Tidak ada keterangan'}</div>
                </li>`;
            });

            html += '</ul>';
            element.innerHTML = html;
        } else {
            element.innerHTML = `<p class="text-muted mb-0 text-center py-3">${emptyMessage}</p>`;
        }
    }
});
</script>
@endpush
