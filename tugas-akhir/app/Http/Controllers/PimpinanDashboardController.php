<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Permission;
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

        $notifications = $this->getNotifications($bulan, $tahun);

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
            'employees',
            'notifications'
        ));
    }

    private function getStatistics($bulan, $tahun, $viewType)
    {
        $totalKaryawan = User::where('role', 'karyawan')->count();

        if ($viewType === 'monthly') {
            // Query untuk absensi
            $absensiQuery = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);

            // Query untuk izin/sakit dari Permission
            $permissionQuery = Permission::where('status', 'Disetujui')
                ->where(function ($query) use ($bulan, $tahun) {
                    $query->where(function ($q) use ($bulan, $tahun) {
                        $q->whereMonth('tanggal_mulai', $bulan)
                            ->whereYear('tanggal_mulai', $tahun);
                    })->orWhere(function ($q) use ($bulan, $tahun) {
                        $q->whereMonth('tanggal_selesai', $bulan)
                            ->whereYear('tanggal_selesai', $tahun);
                    });
                });

            // Hitung dari absensi
            $hadirTepat = (clone $absensiQuery)->where('status', 'hadir')->count();
            $terlambat = (clone $absensiQuery)->where('status', 'terlambat')->count();
            $izinAbsensi = (clone $absensiQuery)->where('status', 'izin')->count();
            $sakitAbsensi = (clone $absensiQuery)->where('status', 'sakit')->count();

            // Hitung dari Permission
            $izinPermission = (clone $permissionQuery)->where('keterangan', 'Izin')->count();
            $sakitPermission = (clone $permissionQuery)->where('keterangan', 'Sakit')->count();
            $cutiPermission = (clone $permissionQuery)->where('keterangan', 'Cuti')->count();

            // Total
            $hadirTotal = $hadirTepat + $terlambat;
            $izinTotal = $izinAbsensi + $izinPermission + $cutiPermission;
            $sakitTotal = $sakitAbsensi + $sakitPermission;
            $totalAbsen = $hadirTotal + $izinTotal + $sakitTotal;

            $uniqueUsers = (clone $absensiQuery)->distinct('user_id')->count('user_id');
            $uniquePermissionUsers = (clone $permissionQuery)->distinct('user_id')->count('user_id');
            $totalUniqueUsers = $uniqueUsers + $uniquePermissionUsers;

            // Hitung jumlah hari kerja dalam bulan
            $startOfMonth = Carbon::create($tahun, $bulan, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $workingDays = $this->countWorkingDays($startOfMonth, $endOfMonth);

            return [
                'total_karyawan' => $totalKaryawan,
                'hadir_total' => $hadirTotal,
                'hadir_tepat' => $hadirTepat,
                'hadir' => $hadirTotal,
                'terlambat' => $terlambat,
                'izin' => $izinTotal,
                'sakit' => $sakitTotal,
                'total_absen' => $totalAbsen,
                'unique_users' => $totalUniqueUsers,
                'working_days' => $workingDays,
                'avg_per_day' => $workingDays > 0 ? round($totalAbsen / $workingDays, 1) : 0,
                'attendance_rate' => $totalAbsen > 0 ? round(($hadirTotal / $totalAbsen) * 100, 1) : 0
            ];
        } else {
            // Query untuk absensi hari ini
            $absensiQuery = Absensi::whereDate('created_at', Carbon::today());

            // Query untuk izin/sakit hari ini dari Permission
            $permissionQuery = Permission::where('status', 'Disetujui')
                ->whereDate('tanggal_mulai', '<=', Carbon::today())
                ->whereDate('tanggal_selesai', '>=', Carbon::today());

            // Hitung dari absensi
            $hadirTepat = (clone $absensiQuery)->where('status', 'hadir')->count();
            $terlambat = (clone $absensiQuery)->where('status', 'terlambat')->count();
            $izinAbsensi = (clone $absensiQuery)->where('status', 'izin')->count();
            $sakitAbsensi = (clone $absensiQuery)->where('status', 'sakit')->count();

            // Hitung dari Permission
            $izinPermission = (clone $permissionQuery)->where('keterangan', 'Izin')->count();
            $sakitPermission = (clone $permissionQuery)->where('keterangan', 'Sakit')->count();
            $cutiPermission = (clone $permissionQuery)->where('keterangan', 'Cuti')->count();

            // Total
            $hadirTotal = $hadirTepat + $terlambat;
            $izinTotal = $izinAbsensi + $izinPermission + $cutiPermission;
            $sakitTotal = $sakitAbsensi + $sakitPermission;

            $sudahAbsen = (clone $absensiQuery)->distinct('user_id')->count('user_id');
            $sudahPermission = (clone $permissionQuery)->distinct('user_id')->count('user_id');
            $totalSudahAbsen = $sudahAbsen + $sudahPermission;
            $belumAbsen = $totalKaryawan - $totalSudahAbsen;

            return [
                'total_karyawan' => $totalKaryawan,
                'hadir_total' => $hadirTotal,
                'hadir_tepat' => $hadirTepat,
                'hadir' => $hadirTotal,
                'terlambat' => $terlambat,
                'izin' => $izinTotal,
                'sakit' => $sakitTotal,
                'belum_absen' => $belumAbsen,
                'sudah_absen' => $totalSudahAbsen,
                'attendance_rate' => $totalKaryawan > 0 ? round(($totalSudahAbsen / $totalKaryawan) * 100, 1) : 0
            ];
        }
    }

    private function getAttendanceData($bulan, $tahun, $nama, $status, $viewType)
    {
        if ($viewType === 'monthly') {
            return collect(); // Tidak digunakan di view monthly
        }

        // Query absensi hari ini
        $absensiQuery = Absensi::with('user')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc');

        // Query permission hari ini
        $permissionQuery = Permission::with('user')
            ->where('status', 'Disetujui')
            ->whereDate('tanggal_mulai', '<=', Carbon::today())
            ->whereDate('tanggal_selesai', '>=', Carbon::today());

        if ($nama) {
            $absensiQuery->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
            $permissionQuery->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        if ($status && $status !== 'belum_absen') {
            if ($status === 'hadir') {
                $absensiQuery->whereIn('status', ['hadir', 'terlambat']);
            } elseif ($status === 'izin') {
                $absensiQuery->where('status', 'izin');
                $permissionQuery->whereIn('keterangan', ['Izin', 'Cuti']);
            } elseif ($status === 'sakit') {
                $absensiQuery->where('status', 'sakit');
                $permissionQuery->where('keterangan', 'Sakit');
            } else {
                $absensiQuery->where('status', $status);
            }
        }

        $absensiData = $absensiQuery->get();

        // Tambahkan data dari Permission
        if (!$status || in_array($status, ['izin', 'sakit'])) {
            $permissionData = $permissionQuery->get()->map(function ($permission) {
                return (object)[
                    'id' => 'perm_' . $permission->id,
                    'user' => $permission->user,
                    'created_at' => $permission->tanggal_mulai,
                    'status' => strtolower($permission->keterangan),
                    'keterangan' => $permission->alasan,
                    'tanggal_mulai' => $permission->tanggal_mulai,
                    'tanggal_selesai' => $permission->tanggal_selesai,
                    'is_permission' => true
                ];
            });

            $absensiData = $absensiData->concat($permissionData);
        }

        // Tambahkan yang belum absen jika tidak ada filter status atau filter status adalah 'belum_absen'
        if (!$status || $status === 'belum_absen') {
            $absenUserIds = $absensiData->pluck('user.id')->toArray();
            $permissionUserIds = $permissionQuery->pluck('user_id')->toArray();
            $allAbsenUserIds = array_merge($absenUserIds, $permissionUserIds);

            $usersBelumAbsen = User::where('role', 'karyawan')
                ->whereNotIn('id', $allAbsenUserIds);

            if ($nama) {
                $usersBelumAbsen->where('name', 'like', '%' . $nama . '%');
            }

            $belumAbsenCollection = $usersBelumAbsen->get()->map(function ($user) {
                return (object)[
                    'id' => 'belum_' . $user->id,
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
        // Query absensi
        $absensiQuery = Absensi::query()
            ->select([
                'user_id',
                DB::raw('COUNT(*) as total_absen'),
                DB::raw('SUM(CASE WHEN status IN ("hadir", "terlambat") THEN 1 ELSE 0 END) as hadir_total'),
                DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir_tepat'),
                DB::raw('SUM(CASE WHEN status = "terlambat" THEN 1 ELSE 0 END) as terlambat'),
                DB::raw('SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin_absensi'),
                DB::raw('SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit_absensi')
            ])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('user_id');

        // Query permission
        $permissionQuery = Permission::query()
            ->select([
                'user_id',
                DB::raw('SUM(CASE WHEN keterangan IN ("Izin", "Cuti") THEN 1 ELSE 0 END) as izin_permission'),
                DB::raw('SUM(CASE WHEN keterangan = "Sakit" THEN 1 ELSE 0 END) as sakit_permission')
            ])
            ->where('status', 'Disetujui')
            ->where(function ($query) use ($bulan, $tahun) {
                $query->where(function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal_mulai', $bulan)
                        ->whereYear('tanggal_mulai', $tahun);
                })->orWhere(function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal_selesai', $bulan)
                        ->whereYear('tanggal_selesai', $tahun);
                });
            })
            ->groupBy('user_id');

        if ($nama) {
            $absensiQuery->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
            $permissionQuery->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        $absensiResults = $absensiQuery->get()->keyBy('user_id');
        $permissionResults = $permissionQuery->get()->keyBy('user_id');

        // Gabungkan semua user_id yang ada
        $allUserIds = $absensiResults->keys()->merge($permissionResults->keys())->unique();

        // Format hasil
        $formattedResults = $allUserIds->map(function ($userId) use ($absensiResults, $permissionResults) {
            $user = User::find($userId);
            $absensi = $absensiResults->get($userId);
            $permission = $permissionResults->get($userId);

            $hadirTotal = $absensi ? $absensi->hadir_total : 0;
            $hadirTepat = $absensi ? $absensi->hadir_tepat : 0;
            $terlambat = $absensi ? $absensi->terlambat : 0;
            $izinTotal = ($absensi ? $absensi->izin_absensi : 0) + ($permission ? $permission->izin_permission : 0);
            $sakitTotal = ($absensi ? $absensi->sakit_absensi : 0) + ($permission ? $permission->sakit_permission : 0);
            $totalAbsen = $hadirTotal + $izinTotal + $sakitTotal;

            return [
                'user_id' => $userId,
                'name' => $user ? $user->name : 'Unknown',
                'total_absen' => $totalAbsen,
                'hadir_total' => $hadirTotal,
                'hadir_tepat' => $hadirTepat,
                'hadir' => $hadirTotal,
                'terlambat' => $terlambat,
                'izin' => $izinTotal,
                'sakit' => $sakitTotal,
                'percentage' => $totalAbsen > 0 ? round(($hadirTotal / $totalAbsen) * 100, 1) : 0
            ];
        });

        // Filter berdasarkan status jika diperlukan
        if ($status) {
            $formattedResults = $formattedResults->filter(function ($item) use ($status) {
                if ($status === 'hadir') {
                    return $item['hadir_total'] > 0;
                } elseif ($status === 'izin') {
                    return $item['izin'] > 0;
                } elseif ($status === 'sakit') {
                    return $item['sakit'] > 0;
                } elseif ($status === 'terlambat') {
                    return $item['terlambat'] > 0;
                }
                return true;
            });
        }

        return $formattedResults->sortByDesc('total_absen');
    }

    private function getChartData($bulan, $tahun, $viewType)
    {
        if ($viewType === 'daily') {
            // Untuk daily view, tampilkan data per jam
            $chartRaw = Absensi::selectRaw('HOUR(created_at) as jam, COUNT(*) as total')
                ->whereDate('created_at', Carbon::today())
                ->groupByRaw('HOUR(created_at)')
                ->orderByRaw('HOUR(created_at)')
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
            // Untuk monthly view, tampilkan data per hari (gabungan absensi dan permission)
            $absensiRaw = Absensi::selectRaw('DAY(created_at) as hari, COUNT(*) as total')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->groupByRaw('DAY(created_at)')
                ->orderByRaw('DAY(created_at)')
                ->get();

            $permissionRaw = Permission::selectRaw('DAY(tanggal_mulai) as hari, COUNT(*) as total')
                ->where('status', 'Disetujui')
                ->whereMonth('tanggal_mulai', $bulan)
                ->whereYear('tanggal_mulai', $tahun)
                ->groupByRaw('DAY(tanggal_mulai)')
                ->orderByRaw('DAY(tanggal_mulai)')
                ->get();

            $labels = [];
            $data = [];

            // Isi semua tanggal dalam bulan
            $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = 'Tgl ' . $i;
                $absensiFound = $absensiRaw->firstWhere('hari', $i);
                $permissionFound = $permissionRaw->firstWhere('hari', $i);
                $total = ($absensiFound ? $absensiFound->total : 0) + ($permissionFound ? $permissionFound->total : 0);
                $data[] = $total;
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
            // Query absensi
            $absensiQuery = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);

            // Query permission
            $permissionQuery = Permission::where('status', 'Disetujui')
                ->where(function ($query) use ($bulan, $tahun) {
                    $query->where(function ($q) use ($bulan, $tahun) {
                        $q->whereMonth('tanggal_mulai', $bulan)
                            ->whereYear('tanggal_mulai', $tahun);
                    })->orWhere(function ($q) use ($bulan, $tahun) {
                        $q->whereMonth('tanggal_selesai', $bulan)
                            ->whereYear('tanggal_selesai', $tahun);
                    });
                });

            // Hitung dari absensi
            $hadirTepat = (clone $absensiQuery)->where('status', 'hadir')->count();
            $terlambat = (clone $absensiQuery)->where('status', 'terlambat')->count();
            $izinAbsensi = (clone $absensiQuery)->where('status', 'izin')->count();
            $sakitAbsensi = (clone $absensiQuery)->where('status', 'sakit')->count();

            // Hitung dari permission
            $izinPermission = (clone $permissionQuery)->whereIn('keterangan', ['Izin', 'Cuti'])->count();
            $sakitPermission = (clone $permissionQuery)->where('keterangan', 'Sakit')->count();

            // Total
            $izinTotal = $izinAbsensi + $izinPermission;
            $sakitTotal = $sakitAbsensi + $sakitPermission;

            $totalKaryawan = User::where('role', 'karyawan')->count();
            $uniqueAbsensiUsers = (clone $absensiQuery)->distinct('user_id')->count('user_id');
            $uniquePermissionUsers = (clone $permissionQuery)->distinct('user_id')->count('user_id');
            $uniqueUsers = $uniqueAbsensiUsers + $uniquePermissionUsers;
            $belum = $totalKaryawan - $uniqueUsers;
        } else {
            // Query absensi hari ini
            $absensiQuery = Absensi::whereDate('created_at', Carbon::today());

            // Query permission hari ini
            $permissionQuery = Permission::where('status', 'Disetujui')
                ->whereDate('tanggal_mulai', '<=', Carbon::today())
                ->whereDate('tanggal_selesai', '>=', Carbon::today());

            // Hitung dari absensi
            $hadirTepat = (clone $absensiQuery)->where('status', 'hadir')->count();
            $terlambat = (clone $absensiQuery)->where('status', 'terlambat')->count();
            $izinAbsensi = (clone $absensiQuery)->where('status', 'izin')->count();
            $sakitAbsensi = (clone $absensiQuery)->where('status', 'sakit')->count();

            // Hitung dari permission
            $izinPermission = (clone $permissionQuery)->whereIn('keterangan', ['Izin', 'Cuti'])->count();
            $sakitPermission = (clone $permissionQuery)->where('keterangan', 'Sakit')->count();

            // Total
            $izinTotal = $izinAbsensi + $izinPermission;
            $sakitTotal = $sakitAbsensi + $sakitPermission;

            $totalKaryawan = User::where('role', 'karyawan')->count();
            $sudahAbsen = (clone $absensiQuery)->distinct('user_id')->count('user_id');
            $sudahPermission = (clone $permissionQuery)->distinct('user_id')->count('user_id');
            $totalSudahAbsen = $sudahAbsen + $sudahPermission;
            $belum = $totalKaryawan - $totalSudahAbsen;
        }

        return [
            'labels' => ['Hadir Tepat', 'Terlambat', 'Izin', 'Sakit', 'Belum Absen'],
            'data' => [$hadirTepat, $terlambat, $izinTotal, $sakitTotal, $belum],
            'colors' => [
                'rgba(40, 167, 69, 0.8)',   // Hijau untuk hadir tepat
                'rgba(255, 193, 7, 0.8)',   // Kuning untuk terlambat
                'rgba(23, 162, 184, 0.8)',  // Biru untuk izin
                'rgba(108, 117, 125, 0.8)', // Abu-abu untuk sakit
                'rgba(220, 53, 69, 0.8)'    // Merah untuk belum absen
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
    private function getNotifications($bulan, $tahun)
    {
        // Notifikasi karyawan baru (1 bulan terakhir)
        $newEmployees = User::where('role', 'karyawan')
            ->where('created_at', '>=', now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'new_employee',
                    'title' => 'Karyawan Baru',
                    'message' => $user->name . ' bergabung sebagai karyawan baru',
                    'time' => Carbon::parse($user->created_at),
                    'icon' => 'fas fa-user-plus text-success'
                ];
            });

        // Notifikasi pengajuan izin/cuti/sakit/dinas
        $permissions = Permission::with('user')
            ->where('status', 'Disetujui')
            ->where(function ($query) use ($bulan, $tahun) {
                $query->where(function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal_mulai', $bulan)
                        ->whereYear('tanggal_mulai', $tahun);
                })->orWhere(function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal_selesai', $bulan)
                        ->whereYear('tanggal_selesai', $tahun);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($permission) {
                return [
                    'type' => 'permission',
                    'title' => 'Pengajuan ' . $permission->keterangan,
                    'message' => $permission->user->name . ' mengajukan ' . strtolower($permission->keterangan) .
                        ' dari ' . Carbon::parse($permission->tanggal_mulai)->format('d M') .
                        ' sampai ' . Carbon::parse($permission->tanggal_selesai)->format('d M'),
                    'time' => Carbon::parse($permission->created_at),
                    'icon' => $this->getPermissionIcon($permission->keterangan) // This now calls the method correctly
                ];
            });

        return $newEmployees->merge($permissions)
            ->sortByDesc('time')
            ->take(10);
    }
    private function getPermissionIcon($type)
    {
        switch (strtolower($type)) {
            case 'izin':
                return 'fas fa-calendar-check text-info';
            case 'cuti':
                return 'fas fa-umbrella-beach text-warning';
            case 'sakit':
                return 'fas fa-heartbeat text-danger';
            case 'perjalanan dinas':
                return 'fas fa-plane text-primary';
            default:
                return 'fas fa-info-circle text-secondary';
        }
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
