<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Permission;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.rekap.bulanan');
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $users = User::where('role', 'karyawan')->get();
        $data = [];

        // Hitung hari kerja (Senin - Jumat)
        $jumlahHariKerja = $this->hitungHariKerja($tahun, $bulan);

        foreach ($users as $user) {
            $rekapData = $this->getRekapDataUser($user->id, $bulan, $tahun, $jumlahHariKerja);

            if ($rekapData) {
                $dataItem = [
                    'user' => $user,
                    'jumlah_hadir' => $rekapData['hadir'],
                    'jumlah_izin' => $rekapData['izin'],
                    'jumlah_sakit' => $rekapData['sakit'],
                    'jumlah_terlambat' => $rekapData['terlambat'],
                    'jumlah_total' => $rekapData['total_hadir_efektif'], // Backward compatibility
                    'total_hadir_efektif' => $rekapData['total_hadir_efektif'], // Yang benar-benar masuk
                    'total_tidak_masuk' => $rekapData['total_tidak_masuk'], // Izin + Sakit
                    'total_keseluruhan' => $rekapData['total_keseluruhan'], // Total semua hari
                    'tanpa_keterangan' => $rekapData['tanpa_keterangan'],
                    'detail_izin' => $rekapData['detail_izin'],
                    'detail_sakit' => $rekapData['detail_sakit'],
                ];

                $data[] = $this->ensureDataIntegrity($dataItem);
            }
        }

        return view('admin.rekap.bulanan', compact('data', 'bulan', 'tahun', 'jumlahHariKerja'));
    }

    public function exportBulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $users = User::where('role', 'karyawan')->get();
        $data = [];

        // Hitung hari kerja
        $jumlahHariKerja = $this->hitungHariKerja($tahun, $bulan);

        foreach ($users as $user) {
            $rekapData = $this->getRekapDataUser($user->id, $bulan, $tahun, $jumlahHariKerja);

            if ($rekapData) {
                $data[] = [
                    'Nama' => $user->nama ?? $user->name,
                    'Hadir' => $rekapData['hadir'],
                    'Izin' => $rekapData['izin'],
                    'Sakit' => $rekapData['sakit'],
                    'Terlambat' => $rekapData['terlambat'],
                    'Total Hadir Efektif' => $rekapData['total_hadir_efektif'], // Yang benar-benar masuk
                    'Total Tidak Masuk' => $rekapData['total_tidak_masuk'], // Izin + Sakit
                    'Tanpa Keterangan' => $rekapData['tanpa_keterangan'],
                    'Total Keseluruhan' => $rekapData['total_keseluruhan'], // Total semua hari
                    'Detail Izin' => $rekapData['detail_izin_text'],
                    'Detail Sakit' => $rekapData['detail_sakit_text'],
                ];
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Absensi Bulan');

        $headers = ['Nama', 'Hadir', 'Izin', 'Sakit', 'Terlambat', 'Total Hadir Efektif', 'Total Tidak Masuk', 'Tanpa Keterangan', 'Total Keseluruhan', 'Detail Izin', 'Detail Sakit'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($data, null, 'A2');

        // Auto-size columns
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="rekap_absensi_' . $bulan . '_' . $tahun . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function print(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $users = User::where('role', 'karyawan')->get();
        $data = [];

        // Hitung hari kerja
        $jumlahHariKerja = $this->hitungHariKerja($tahun, $bulan);

        foreach ($users as $user) {
            $rekapData = $this->getRekapDataUser($user->id, $bulan, $tahun, $jumlahHariKerja);

            if ($rekapData) {
                $data[] = [
                    'user' => $user,
                    'hadir' => $rekapData['hadir'],
                    'izin' => $rekapData['izin'],
                    'sakit' => $rekapData['sakit'],
                    'terlambat' => $rekapData['terlambat'],
                    'total' => $rekapData['total_hadir_efektif'], // Backward compatibility
                    'total_hadir_efektif' => $rekapData['total_hadir_efektif'], // Yang benar-benar masuk
                    'total_tidak_masuk' => $rekapData['total_tidak_masuk'], // Izin + Sakit
                    'tanpa_keterangan' => $rekapData['tanpa_keterangan'],
                    'total_keseluruhan' => $rekapData['total_keseluruhan'], // Total semua hari
                    'detail_izin' => $rekapData['detail_izin'],
                    'detail_sakit' => $rekapData['detail_sakit'],
                ];
            }
        }

        return view('admin.rekap.print', compact('data', 'bulan', 'tahun', 'jumlahHariKerja'));
    }

    /**
     * Method untuk mendapatkan data detail izin dan sakit karyawan
     */
    public function getDetailIzinSakit(Request $request)
    {
        $userId = $request->get('user_id');
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        if (!$userId) {
            return response()->json(['error' => 'User ID required'], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $rekapData = $this->getRekapDataUser($userId, $bulan, $tahun, 0);

        return response()->json([
            'user' => $user,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'detail_izin' => $rekapData['detail_izin'],
            'detail_sakit' => $rekapData['detail_sakit'],
            'summary' => [
                'total_izin' => $rekapData['izin'],
                'total_sakit' => $rekapData['sakit'],
                'total_hadir_efektif' => $rekapData['total_hadir_efektif'],
                'total_tidak_masuk' => $rekapData['total_tidak_masuk'],
                'total_keseluruhan' => $rekapData['total_keseluruhan'],
            ]
        ]);
    }

    /**
     * Method untuk mendapatkan rekap data user
     */
    private function getRekapDataUser($userId, $bulan, $tahun, $jumlahHariKerja)
    {
        // Ambil data absensi dari tabel absensi
        $absensi = Absensi::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        // Ambil data izin dan sakit dari tabel permissions yang sudah disetujui
        $permissions = Permission::where('user_id', $userId)
            ->where('status', 'Disetujui')
            ->where(function($query) use ($bulan, $tahun) {
                $query->where(function($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal_mulai', $bulan)
                      ->whereYear('tanggal_mulai', $tahun);
                })->orWhere(function($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal_selesai', $bulan)
                      ->whereYear('tanggal_selesai', $tahun);
                })->orWhere(function($q) use ($bulan, $tahun) {
                    $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                    $endOfMonth = $startOfMonth->copy()->endOfMonth();
                    $q->where('tanggal_mulai', '<=', $startOfMonth)
                      ->where('tanggal_selesai', '>=', $endOfMonth);
                });
            })
            ->whereIn('keterangan', ['Izin', 'Sakit'])
            ->get();

        if ($absensi->count() == 0 && $permissions->count() == 0) {
            return null;
        }

        // Hitung data dari absensi
        $hadirTepat = $absensi->where('status', 'hadir')->count();
        $terlambat = $absensi->where('status', 'terlambat')->count();
        $hadir = $hadirTepat + $terlambat; // Total yang benar-benar hadir ke kantor
        $izinFromAbsensi = $absensi->where('status', 'izin')->count();
        $sakitFromAbsensi = $absensi->where('status', 'sakit')->count();

        // Hitung data dari permissions dan buat detail
        $izinFromPermissions = 0;
        $sakitFromPermissions = 0;
        $detailIzin = [];
        $detailSakit = [];

        foreach ($permissions as $permission) {
            $tanggalMulai = Carbon::parse($permission->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($permission->tanggal_selesai);

            // Hitung hari dalam bulan yang diminta
            $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $actualStart = $tanggalMulai->max($startOfMonth);
            $actualEnd = $tanggalSelesai->min($endOfMonth);

            if ($actualStart->lte($actualEnd)) {
                $jumlahHari = $actualStart->diffInDays($actualEnd) + 1;

                if ($permission->keterangan == 'Izin') {
                    $izinFromPermissions += $jumlahHari;
                    $detailIzin[] = [
                        'tanggal_mulai' => $actualStart->format('Y-m-d'),
                        'tanggal_selesai' => $actualEnd->format('Y-m-d'),
                        'jumlah_hari' => $jumlahHari,
                        'alasan' => $permission->alasan,
                        'original_start' => $permission->tanggal_mulai,
                        'original_end' => $permission->tanggal_selesai,
                    ];
                } elseif ($permission->keterangan == 'Sakit') {
                    $sakitFromPermissions += $jumlahHari;
                    $detailSakit[] = [
                        'tanggal_mulai' => $actualStart->format('Y-m-d'),
                        'tanggal_selesai' => $actualEnd->format('Y-m-d'),
                        'jumlah_hari' => $jumlahHari,
                        'alasan' => $permission->alasan,
                        'original_start' => $permission->tanggal_mulai,
                        'original_end' => $permission->tanggal_selesai,
                    ];
                }
            }
        }

        $totalIzin = $izinFromAbsensi + $izinFromPermissions;
        $totalSakit = $sakitFromAbsensi + $sakitFromPermissions;

        // Total absen = hadir + izin + sakit + tanpa keterangan
        // Tapi untuk perhitungan tanpa keterangan, izin dan sakit tidak dihitung sebagai kehadiran
        $totalHadirEfektif = $hadir; // Hanya yang benar-benar hadir (hadir + terlambat)
        $totalTidakMasuk = $totalIzin + $totalSakit; // Yang tidak masuk tapi ada keterangan
        $tanpaKeterangan = $jumlahHariKerja > 0 ? max(0, $jumlahHariKerja - $totalHadirEfektif - $totalTidakMasuk) : 0;

        // Total keseluruhan hari kerja yang tercatat
        $totalKeseluruhan = $totalHadirEfektif + $totalTidakMasuk + $tanpaKeterangan;

        return [
            'hadir' => $hadir,
            'izin' => $totalIzin,
            'sakit' => $totalSakit,
            'terlambat' => $terlambat,
            'total' => $totalHadirEfektif, // Backward compatibility (untuk view lama)
            'jumlah_total' => $totalHadirEfektif, // Backward compatibility (untuk view lama)
            'total_hadir_efektif' => $totalHadirEfektif, // Yang benar-benar masuk kerja
            'total_tidak_masuk' => $totalTidakMasuk, // Yang tidak masuk tapi ada keterangan
            'total_keseluruhan' => $totalKeseluruhan, // Total semua hari kerja
            'tanpa_keterangan' => $tanpaKeterangan,
            'detail_izin' => $detailIzin,
            'detail_sakit' => $detailSakit,
            'detail_izin_text' => $this->formatDetailText($detailIzin),
            'detail_sakit_text' => $this->formatDetailText($detailSakit),

            // Additional computed fields for convenience
            'persentase_kehadiran' => $this->hitungPersentaseKehadiran($totalHadirEfektif, $jumlahHariKerja),
            'jumlah_hari_kerja' => $jumlahHariKerja,
        ];
    }

    /**
     * Method untuk menghitung hari kerja
     */
    private function hitungHariKerja($tahun, $bulan)
    {
        $jumlahHariKerja = 0;
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekday()) {
                $jumlahHariKerja++;
            }
        }

        return $jumlahHariKerja;
    }

    /**
     * Method untuk format detail text untuk export
     */
    private function formatDetailText($details)
    {
        if (empty($details)) {
            return '-';
        }

        $text = '';
        foreach ($details as $detail) {
            $text .= $detail['tanggal_mulai'] . ' - ' . $detail['tanggal_selesai'] . ' (' . $detail['jumlah_hari'] . ' hari)';
            if (!empty($detail['alasan'])) {
                $text .= ': ' . $detail['alasan'];
            }
            $text .= '; ';
        }

        return rtrim($text, '; ');
    }

    /**
     * Method untuk menghitung persentase kehadiran
     */
    public function hitungPersentaseKehadiran($totalHadirEfektif, $jumlahHariKerja)
    {
        if ($jumlahHariKerja == 0) {
            return 0;
        }

        return round(($totalHadirEfektif / $jumlahHariKerja) * 100, 2);
    }

    /**
     * Method untuk mendapatkan statistik kehadiran
     */
    public function getStatistikKehadiran(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $users = User::where('role', 'karyawan')->get();
        $jumlahHariKerja = $this->hitungHariKerja($tahun, $bulan);

        $statistik = [
            'total_karyawan' => $users->count(),
            'total_hadir_efektif' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_tanpa_keterangan' => 0,
            'rata_rata_kehadiran' => 0,
        ];

        foreach ($users as $user) {
            $rekapData = $this->getRekapDataUser($user->id, $bulan, $tahun, $jumlahHariKerja);

            if ($rekapData) {
                $statistik['total_hadir_efektif'] += $rekapData['total_hadir_efektif'];
                $statistik['total_izin'] += $rekapData['izin'];
                $statistik['total_sakit'] += $rekapData['sakit'];
                $statistik['total_tanpa_keterangan'] += $rekapData['tanpa_keterangan'];
            }
        }

        if ($statistik['total_karyawan'] > 0 && $jumlahHariKerja > 0) {
            $totalHariKerjaSeharusnya = $statistik['total_karyawan'] * $jumlahHariKerja;
            $statistik['rata_rata_kehadiran'] = round(($statistik['total_hadir_efektif'] / $totalHariKerjaSeharusnya) * 100, 2);
        }

        return response()->json($statistik);
    }

    /**
     * Method untuk debugging - menampilkan struktur data
     * Route: GET /admin/rekap/debug-data?user_id=1&bulan=12&tahun=2024
     */
    public function debugRekapData(Request $request)
    {
        $userId = $request->get('user_id');
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        if (!$userId) {
            return response()->json(['error' => 'User ID required'], 400);
        }

        $jumlahHariKerja = $this->hitungHariKerja($tahun, $bulan);
        $rekapData = $this->getRekapDataUser($userId, $bulan, $tahun, $jumlahHariKerja);

        return response()->json([
            'user_id' => $userId,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jumlah_hari_kerja' => $jumlahHariKerja,
            'rekap_data' => $rekapData,
            'available_keys' => $rekapData ? array_keys($rekapData) : [],
        ]);
    }

    /**
     * Method untuk memastikan data array memiliki semua key yang diperlukan
     */
    private function ensureDataIntegrity($data)
    {
        $requiredKeys = [
            'user', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_terlambat',
            'jumlah_total', 'total_hadir_efektif', 'total_tidak_masuk', 'total_keseluruhan',
            'tanpa_keterangan', 'detail_izin', 'detail_sakit'
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                $data[$key] = 0; // Default value for numeric fields
                if (in_array($key, ['user', 'detail_izin', 'detail_sakit'])) {
                    $data[$key] = $key === 'user' ? null : [];
                }
            }
        }

        return $data;
    }
}
