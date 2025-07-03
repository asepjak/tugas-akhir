<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\BonusKaryawan;
use Carbon\Carbon;

class PimpinanDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Parameter filter
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));
        $nama = $request->get('nama');
        $status = $request->get('status');
        $viewType = $request->get('view_type', 'monthly');

        // Daftar bulan dan tahun
        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        $tahunList = [];
        for ($i = now()->year - 2; $i <= now()->year + 1; $i++) {
            $tahunList[$i] = $i;
        }

        // Data statistik
        $statistik = $this->getStatistics($bulan, $tahun, $viewType);

        // Data absensi
        $absensiData = $this->getAttendanceData($bulan, $tahun, $nama, $status, $viewType);

        // Data bulanan per karyawan
        $monthlyAttendanceData = $this->getMonthlyAttendanceData($bulan, $tahun, $nama, $status);

        // Data chart
        $chartData = $this->getChartData($bulan, $tahun);
        $pieChartData = $this->getPieChartData($bulan, $tahun, $viewType);

        // Data bonus
        $bonusData = BonusKaryawan::with('user')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        // Data aktivitas terbaru
        $recentActivities = \App\Models\ActivityLog::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Daftar karyawan untuk modal bonus
        $employees = User::where('role', 'karyawan')->get();

        return view('dashboard.pimpinan', compact(
            'bulanList',
            'tahunList',
            'bulan',
            'tahun',
            'nama',
            'status',
            'viewType',
            'statistik',
            'absensiData',
            'monthlyAttendanceData',
            'chartData',
            'pieChartData',
            'bonusData',
            'recentActivities',
            'employees'
        ));
    }

    private function getStatistics($bulan, $tahun, $viewType)
    {
        $totalKaryawan = User::where('role', 'karyawan')->count();

        if ($viewType === 'monthly') {
            $hadir = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'hadir')
                ->count();

            $terlambat = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'terlambat')
                ->count();

            $izin = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'izin')
                ->count();

            $sakit = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'sakit')
                ->count();

            $totalAbsen = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count();

            $uniqueUsers = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->distinct('user_id')
                ->count('user_id');

            return [
                'total_karyawan' => $totalKaryawan,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'total_absen' => $totalAbsen,
                'unique_users' => $uniqueUsers
            ];
        } else {
            $hadir = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'hadir')
                ->count();

            $terlambat = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'terlambat')
                ->count();

            $izin = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'izin')
                ->count();

            $sakit = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'sakit')
                ->count();

            $sudahAbsen = Absensi::whereDate('created_at', Carbon::today())
                ->distinct('user_id')
                ->count('user_id');

            $belumAbsen = $totalKaryawan - $sudahAbsen;

            return [
                'total_karyawan' => $totalKaryawan,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'belum_absen' => $belumAbsen,
                'sudah_absen' => $sudahAbsen
            ];
        }
    }

    private function getAttendanceData($bulan, $tahun, $nama, $status, $viewType)
    {
        if ($viewType === 'monthly') {
            return collect(); // Tidak digunakan di view monthly
        }

        $query = Absensi::with('user')
            ->whereDate('created_at', Carbon::today())
            ->latest();

        if ($nama) {
            $query->whereHas('user', function($q) use ($nama) {
                $q->where('name', 'like', '%'.$nama.'%');
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $absensiData = $query->get();

        // Tambahkan yang belum absen jika tidak ada filter status
        if (!$status) {
            $absenUserIds = $absensiData->pluck('user_id')->toArray();
            $usersBelumAbsen = User::where('role', 'karyawan')
                ->whereNotIn('id', $absenUserIds);

            if ($nama) {
                $usersBelumAbsen->where('name', 'like', '%'.$nama.'%');
            }

            foreach ($usersBelumAbsen->get() as $user) {
                $absensiData->push((object)[
                    'user' => $user,
                    'created_at' => null,
                    'status' => 'belum absen'
                ]);
            }
        }

        return $absensiData;
    }

    private function getMonthlyAttendanceData($bulan, $tahun, $nama, $status)
    {
        $query = Absensi::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->selectRaw('user_id, COUNT(*) as total_absen,
                       SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir,
                       SUM(CASE WHEN status = "terlambat" THEN 1 ELSE 0 END) as terlambat,
                       SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin,
                       SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit')
            ->with('user')
            ->groupBy('user_id');

        if ($nama) {
            $query->whereHas('user', function($q) use ($nama) {
                $q->where('name', 'like', '%'.$nama.'%');
            });
        }

        if ($status) {
            $query->havingRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) > 0', [$status]);
        }

        return $query->orderByDesc('total_absen')->get();
    }

    private function getChartData($bulan, $tahun)
    {
        $chartRaw = Absensi::selectRaw('DAY(created_at) as hari, COUNT(*) as total')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        $labels = [];
        $data = [];

        // Isi semua tanggal dalam bulan
        $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = 'Tgl ' . $i;
            $found = $chartRaw->firstWhere('hari', $i);
            $data[] = $found ? $found->total : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getPieChartData($bulan, $tahun, $viewType)
    {
        if ($viewType === 'monthly') {
            $hadir = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'hadir')
                ->count();

            $terlambat = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'terlambat')
                ->count();

            $izin = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'izin')
                ->count();

            $sakit = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'sakit')
                ->count();

            $belum = User::where('role', 'karyawan')->count() -
                     Absensi::whereMonth('created_at', $bulan)
                     ->whereYear('created_at', $tahun)
                     ->distinct('user_id')
                     ->count('user_id');
        } else {
            $hadir = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'hadir')
                ->count();

            $terlambat = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'terlambat')
                ->count();

            $izin = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'izin')
                ->count();

            $sakit = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'sakit')
                ->count();

            $belum = User::where('role', 'karyawan')->count() -
                     Absensi::whereDate('created_at', Carbon::today())
                     ->distinct('user_id')
                     ->count('user_id');
        }

        return [
            'labels' => ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Belum Absen'],
            'data' => [$hadir, $terlambat, $izin, $sakit, $belum]
        ];
    }
}
