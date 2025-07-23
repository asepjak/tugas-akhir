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
                    'total_hadir_efektif' => $rekapData['total_hadir_efektif'],
                    'total_tidak_masuk' => $rekapData['total_tidak_masuk'],
                    'total_keseluruhan' => $rekapData['total_keseluruhan'],
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
                    'Total Hadir Efektif' => $rekapData['total_hadir_efektif'],
                    'Total Tidak Masuk' => $rekapData['total_tidak_masuk'],
                    'Tanpa Keterangan' => $rekapData['tanpa_keterangan'],
                    'Total Keseluruhan' => $rekapData['total_keseluruhan'],
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
                    'total_hadir_efektif' => $rekapData['total_hadir_efektif'],
                    'total_tidak_masuk' => $rekapData['total_tidak_masuk'],
                    'tanpa_keterangan' => $rekapData['tanpa_keterangan'],
                    'total_keseluruhan' => $rekapData['total_keseluruhan'],
                    'detail_izin' => $rekapData['detail_izin'],
                    'detail_sakit' => $rekapData['detail_sakit'],
                ];
            }
        }

        return view('admin.rekap.print', compact('data', 'bulan', 'tahun', 'jumlahHariKerja'));
    }

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
            ->whereIn('keterangan', ['Izin', 'Sakit'])
            ->get();

        // Filter permissions berdasarkan periode bulan/tahun yang diminta
        $filteredPermissions = $permissions->filter(function($permission) use ($bulan, $tahun) {
            return $this->isPermissionInMonth($permission, $bulan, $tahun);
        });

        if ($absensi->count() == 0 && $filteredPermissions->count() == 0) {
            return null;
        }

        // Hitung data dari absensi
        $hadirTepat = $absensi->where('status', 'hadir')->count();
        $terlambat = $absensi->where('status', 'terlambat')->count();
        $hadir = $hadirTepat + $terlambat;
        $izinFromAbsensi = $absensi->where('status', 'izin')->count();
        $sakitFromAbsensi = $absensi->where('status', 'sakit')->count();

        // Hitung data dari permissions dan buat detail
        $izinDays = [];
        $sakitDays = [];
        $detailIzin = [];
        $detailSakit = [];

        // Dapatkan tanggal yang sudah tercatat di tabel absensi untuk menghindari duplikasi
        $tanggalAbsensi = $absensi->pluck('tanggal')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        foreach ($filteredPermissions as $permission) {
            $processedData = $this->processPermissionData($permission, $bulan, $tahun, $tanggalAbsensi);

            if ($permission->keterangan == 'Izin') {
                foreach ($processedData['dates'] as $date) {
                    if (!in_array($date, $izinDays)) {
                        $izinDays[] = $date;
                    }
                }
                $detailIzin = array_merge($detailIzin, $processedData['details']);
            } elseif ($permission->keterangan == 'Sakit') {
                foreach ($processedData['dates'] as $date) {
                    if (!in_array($date, $sakitDays)) {
                        $sakitDays[] = $date;
                    }
                }
                $detailSakit = array_merge($detailSakit, $processedData['details']);
            }
        }

        // Hitung total izin dan sakit berdasarkan unique days
        $izinFromPermissions = count($izinDays);
        $sakitFromPermissions = count($sakitDays);

        $totalIzin = $izinFromAbsensi + $izinFromPermissions;
        $totalSakit = $sakitFromAbsensi + $sakitFromPermissions;

        // PERBAIKAN: Logika perhitungan total absen
        // Total absen dimulai dari 0, lalu:
        // - Ditambah 1 untuk setiap kehadiran (hadir + terlambat)
        // - Izin dan sakit TIDAK menambah total absen (mereka tetap dihitung terpisah)

        // Total hadir efektif = jumlah hari yang benar-benar hadir (hadir + terlambat)
        $totalHadirEfektif = $hadir; // ini adalah jumlah hari hadir + terlambat

        // Total tidak masuk = jumlah hari izin + sakit
        $totalTidakMasuk = $totalIzin + $totalSakit;

        // Total keseluruhan = total hadir efektif + total tidak masuk
        // (ini menunjukkan total hari yang tercatat dalam sistem)
        $totalKeseluruhan = $totalHadirEfektif + $totalTidakMasuk;

        // Tanpa keterangan = hari kerja - total keseluruhan
        $tanpaKeterangan = $jumlahHariKerja > 0 ? max(0, $jumlahHariKerja - $totalKeseluruhan) : 0;

        // Update total keseluruhan dengan menambahkan tanpa keterangan
        $totalKeseluruhan = $totalKeseluruhan + $tanpaKeterangan;

        return [
            'hadir' => $hadir,
            'izin' => $totalIzin,
            'sakit' => $totalSakit,
            'terlambat' => $terlambat,
            'total_hadir_efektif' => $totalHadirEfektif, // Hanya kehadiran yang menambah total absen
            'total_tidak_masuk' => $totalTidakMasuk,
            'total_keseluruhan' => $totalKeseluruhan,
            'tanpa_keterangan' => $tanpaKeterangan,
            'detail_izin' => $detailIzin,
            'detail_sakit' => $detailSakit,
            'detail_izin_text' => $this->formatDetailText($detailIzin),
            'detail_sakit_text' => $this->formatDetailText($detailSakit),
            'persentase_kehadiran' => $this->hitungPersentaseKehadiran($totalHadirEfektif, $jumlahHariKerja),
            'jumlah_hari_kerja' => $jumlahHariKerja,
        ];
    }

    private function isPermissionInMonth($permission, $bulan, $tahun)
    {
        $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $tanggalMulai = Carbon::parse($permission->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($permission->tanggal_selesai);

        return !($tanggalSelesai->lt($startOfMonth) || $tanggalMulai->gt($endOfMonth));
    }

    private function processPermissionData($permission, $bulan, $tahun, $tanggalAbsensi = [])
    {
        $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $tanggalMulai = Carbon::parse($permission->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($permission->tanggal_selesai);

        // Ambil tanggal yang overlap dengan bulan yang diminta
        $actualStart = $tanggalMulai->max($startOfMonth);
        $actualEnd = $tanggalSelesai->min($endOfMonth);

        $dates = [];
        $details = [];

        // Hitung hari kerja (Senin-Jumat) dalam periode tersebut
        for ($date = $actualStart->copy(); $date->lte($actualEnd); $date->addDay()) {
            // Hanya hitung hari kerja (Senin-Jumat)
            if ($date->isWeekday()) {
                $dateString = $date->format('Y-m-d');

                // Periksa apakah tanggal ini sudah ada di tabel absensi
                if (!in_array($dateString, $tanggalAbsensi)) {
                    $dates[] = $dateString;
                }
            }
        }

        if (count($dates) > 0) {
            $details[] = [
                'tanggal_mulai' => $actualStart->format('Y-m-d'),
                'tanggal_selesai' => $actualEnd->format('Y-m-d'),
                'jumlah_hari' => count($dates),
                'alasan' => $permission->alasan,
                'original_start' => $permission->tanggal_mulai,
                'original_end' => $permission->tanggal_selesai,
                'keterangan' => $permission->keterangan,
                'dates' => $dates,
            ];
        }

        return [
            'dates' => $dates,
            'details' => $details
        ];
    }

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

    public function hitungPersentaseKehadiran($totalHadirEfektif, $jumlahHariKerja)
    {
        if ($jumlahHariKerja == 0) {
            return 0;
        }

        return round(($totalHadirEfektif / $jumlahHariKerja) * 100, 2);
    }

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

        $absensi = Absensi::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $permissions = Permission::where('user_id', $userId)
            ->where('status', 'Disetujui')
            ->whereIn('keterangan', ['Izin', 'Sakit'])
            ->get();

        return response()->json([
            'user_id' => $userId,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jumlah_hari_kerja' => $jumlahHariKerja,
            'rekap_data' => $rekapData,
            'debug_info' => [
                'absensi_count' => $absensi->count(),
                'permissions_count' => $permissions->count(),
                'absensi_data' => $absensi->toArray(),
                'permissions_data' => $permissions->toArray(),
            ],
            'available_keys' => $rekapData ? array_keys($rekapData) : [],
        ]);
    }

    private function ensureDataIntegrity($data)
    {
        $requiredKeys = [
            'user', 'jumlah_hadir', 'jumlah_izin', 'jumlah_sakit', 'jumlah_terlambat',
            'total_hadir_efektif', 'total_tidak_masuk', 'total_keseluruhan',
            'tanpa_keterangan', 'detail_izin', 'detail_sakit'
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                $data[$key] = 0;
                if (in_array($key, ['user', 'detail_izin', 'detail_sakit'])) {
                    $data[$key] = $key === 'user' ? null : [];
                }
            }
        }

        return $data;
    }
}
