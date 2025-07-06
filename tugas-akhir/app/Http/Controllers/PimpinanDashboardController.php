<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\BonusKaryawan;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


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
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
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
        $chartData = $this->getChartData($bulan, $tahun, $viewType);
        $pieChartData = $this->getPieChartData($bulan, $tahun, $viewType);

        // Data bonus
        $bonusData = BonusKaryawan::with('user')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        // Data aktivitas terbaru
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Daftar karyawan untuk modal bonus
        $employees = User::where('role', 'karyawan')
            ->orderBy('name', 'asc')
            ->get();

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
            $query = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);

            $hadir = (clone $query)->where('status', 'hadir')->count();
            $terlambat = (clone $query)->where('status', 'terlambat')->count();
            $izin = (clone $query)->where('status', 'izin')->count();
            $sakit = (clone $query)->where('status', 'sakit')->count();
            $totalAbsen = (clone $query)->count();

            $uniqueUsers = (clone $query)->distinct('user_id')->count('user_id');

            // Hitung jumlah hari kerja dalam bulan
            $startOfMonth = Carbon::create($tahun, $bulan, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $workingDays = $this->countWorkingDays($startOfMonth, $endOfMonth);

            return [
                'total_karyawan' => $totalKaryawan,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'total_absen' => $totalAbsen,
                'unique_users' => $uniqueUsers,
                'working_days' => $workingDays,
                'avg_per_day' => $workingDays > 0 ? round($totalAbsen / $workingDays, 1) : 0,
                'attendance_rate' => $totalAbsen > 0 ? round(($hadir / $totalAbsen) * 100, 1) : 0
            ];
        } else {
            $query = Absensi::whereDate('created_at', Carbon::today());

            $hadir = (clone $query)->where('status', 'hadir')->count();
            $terlambat = (clone $query)->where('status', 'terlambat')->count();
            $izin = (clone $query)->where('status', 'izin')->count();
            $sakit = (clone $query)->where('status', 'sakit')->count();

            $sudahAbsen = (clone $query)->distinct('user_id')->count('user_id');
            $belumAbsen = $totalKaryawan - $sudahAbsen;

            return [
                'total_karyawan' => $totalKaryawan,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'belum_absen' => $belumAbsen,
                'sudah_absen' => $sudahAbsen,
                'attendance_rate' => $totalKaryawan > 0 ? round(($sudahAbsen / $totalKaryawan) * 100, 1) : 0
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
            ->orderBy('created_at', 'desc');

        if ($nama) {
            $query->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        if ($status && $status !== 'belum_absen') {
            $query->where('status', $status);
        }

        $absensiData = $query->get();

        // Tambahkan yang belum absen jika tidak ada filter status atau filter status adalah 'belum_absen'
        if (!$status || $status === 'belum_absen') {
            $absenUserIds = $absensiData->pluck('user_id')->toArray();
            $usersBelumAbsen = User::where('role', 'karyawan')
                ->whereNotIn('id', $absenUserIds);

            if ($nama) {
                $usersBelumAbsen->where('name', 'like', '%' . $nama . '%');
            }

            $belumAbsenCollection = $usersBelumAbsen->get()->map(function ($user) {
                return (object)[
                    'id' => $user->id,
                    'user' => $user,
                    'created_at' => null,
                    'status' => 'belum_absen'
                ];
            });

            // Jika filter status adalah 'belum_absen', hanya tampilkan yang belum absen
            if ($status === 'belum_absen') {
                $absensiData = $belumAbsenCollection;
            } else {
                $absensiData = $absensiData->concat($belumAbsenCollection);
            }
        }

        return $absensiData;
    }

    private function getMonthlyAttendanceData($bulan, $tahun, $nama, $status)
    {
        $query = Absensi::query()
            ->select([
                'user_id',
                DB::raw('COUNT(*) as total_absen'),
                DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir'),
                DB::raw('SUM(CASE WHEN status = "terlambat" THEN 1 ELSE 0 END) as terlambat'),
                DB::raw('SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin'),
                DB::raw('SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit')
            ])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('user_id');

        if ($nama) {
            $query->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        if ($status) {
            $query->havingRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) > 0', [$status]);
        }

        $results = $query->get();

        // Load user data dan format hasil
        $formattedResults = $results->map(function ($item) {
            $user = User::find($item->user_id);

            return [
                'user_id' => $item->user_id,
                'name' => $user ? $user->name : 'Unknown',
                'total_absen' => $item->total_absen,
                'hadir' => $item->hadir,
                'terlambat' => $item->terlambat,
                'izin' => $item->izin,
                'sakit' => $item->sakit,
                'percentage' => $item->total_absen > 0 ? round(($item->hadir / $item->total_absen) * 100, 1) : 0
            ];
        });

        return $formattedResults->sortByDesc('total_absen');
    }

    private function getChartData($bulan, $tahun, $viewType)
    {
        if ($viewType === 'daily') {
            // Untuk daily view, tampilkan data per jam
            $chartRaw = Absensi::selectRaw('HOUR(created_at) as jam, COUNT(*) as total')
                ->whereDate('created_at', Carbon::today())
                ->groupBy('jam')
                ->orderBy('jam')
                ->get();

            $labels = [];
            $data = [];

            for ($i = 0; $i < 24; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $found = $chartRaw->firstWhere('jam', $i);
                $data[] = $found ? $found->total : 0;
            }

            return [
                'labels' => $labels,
                'data' => $data,
            ];
        } else {
            // Untuk monthly view, tampilkan data per hari
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
    }

    private function getPieChartData($bulan, $tahun, $viewType)
    {
        if ($viewType === 'monthly') {
            $query = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);

            $hadir = (clone $query)->where('status', 'hadir')->count();
            $terlambat = (clone $query)->where('status', 'terlambat')->count();
            $izin = (clone $query)->where('status', 'izin')->count();
            $sakit = (clone $query)->where('status', 'sakit')->count();

            $totalKaryawan = User::where('role', 'karyawan')->count();
            $uniqueUsers = (clone $query)->distinct('user_id')->count('user_id');
            $belum = $totalKaryawan - $uniqueUsers;
        } else {
            $query = Absensi::whereDate('created_at', Carbon::today());

            $hadir = (clone $query)->where('status', 'hadir')->count();
            $terlambat = (clone $query)->where('status', 'terlambat')->count();
            $izin = (clone $query)->where('status', 'izin')->count();
            $sakit = (clone $query)->where('status', 'sakit')->count();

            $totalKaryawan = User::where('role', 'karyawan')->count();
            $sudahAbsen = (clone $query)->distinct('user_id')->count('user_id');
            $belum = $totalKaryawan - $sudahAbsen;
        }

        return [
            'labels' => ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Belum Absen'],
            'data' => [$hadir, $terlambat, $izin, $sakit, $belum],
            'colors' => [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(108, 117, 125, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ]
        ];
    }

    private function countWorkingDays($startDate, $endDate)
    {
        $workingDays = 0;
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            // Hitung hari kerja (Senin-Jumat, kecuali ada logika khusus untuk hari libur)
            if ($current->isWeekday()) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }

    public function storeBonus(Request $request)
    {
        try {
            $request->validate([
                'employee_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:255'
            ]);

            $bulan = $request->get('bulan', now()->format('m'));
            $tahun = $request->get('tahun', now()->format('Y'));

            // Cek apakah bonus sudah ada untuk karyawan ini di bulan/tahun yang sama
            $existingBonus = BonusKaryawan::where('user_id', $request->employee_id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();

            if ($existingBonus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bonus untuk karyawan ini sudah ada di bulan ini.'
                ], 400);
            }

            // Ambil user login
            $currentUser = Auth::user();

            // Buat bonus baru
            BonusKaryawan::create([
                'user_id' => $request->employee_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'created_by' => $currentUser->id,
            ]);

            // Ambil nama karyawan untuk log
            $targetUser = User::find($request->employee_id);

            // Log aktivitas
            ActivityLog::create([
                'user_id' => $currentUser->id,
                'type' => 'bonus_added',
                'description' => 'Menambahkan bonus untuk karyawan ' . ($targetUser ? $targetUser->name : 'Unknown'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bonus berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function exportExcel(Request $request)
    {
        // Implementasi export Excel
        // Bisa menggunakan library seperti PhpSpreadsheet

        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));
        $viewType = $request->get('view_type', 'monthly');

        // Return file Excel
        return response()->streamDownload(function () use ($bulan, $tahun, $viewType) {
            // Logic untuk generate Excel file
        }, 'absensi_' . $bulan . '_' . $tahun . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        // Implementasi export PDF
        // Bisa menggunakan library seperti dompdf atau tcpdf

        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));
        $viewType = $request->get('view_type', 'monthly');

        // Return file PDF
        return response()->streamDownload(function () use ($bulan, $tahun, $viewType) {
            // Logic untuk generate PDF file
        }, 'absensi_' . $bulan . '_' . $tahun . '.pdf');
    }
}
