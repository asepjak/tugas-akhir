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
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));
        $nama = $request->get('nama');
        $statusFilter = $request->get('status');
        $filterType = $request->get('filter_type', 'hari_ini'); // 'hari_ini' atau 'bulanan'

        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        $tahunList = [];
        for ($i = 2020; $i <= now()->year + 1; $i++) {
            $tahunList[$i] = $i;
        }

        // Tentukan periode berdasarkan filter type
        if ($filterType === 'bulanan') {
            // Data absensi per bulan
            $absensiQuery = Absensi::with('user')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->latest();
        } else {
            // Data absensi hari ini (default)
            $absensiQuery = Absensi::with('user')
                ->whereDate('created_at', Carbon::today())
                ->latest();
        }

        // Filter berdasarkan nama
        if ($nama) {
            $absensiQuery->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        // Filter berdasarkan status
        if ($statusFilter === 'sudah') {
            $absensiData = $absensiQuery->get();
        } elseif ($statusFilter === 'belum') {
            // Ambil user yang belum absen
            if ($filterType === 'bulanan') {
                $absenUserIds = Absensi::whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun)
                    ->pluck('user_id')
                    ->unique()
                    ->toArray();
            } else {
                $absenUserIds = Absensi::whereDate('created_at', Carbon::today())
                    ->pluck('user_id')
                    ->toArray();
            }

            $usersBelumAbsenQuery = User::where('role', 'karyawan')
                ->whereNotIn('id', $absenUserIds);

            if ($nama) {
                $usersBelumAbsenQuery->where('name', 'like', '%' . $nama . '%');
            }

            $usersBelumAbsen = $usersBelumAbsenQuery->get();
            $absensiData = collect();

            foreach ($usersBelumAbsen as $u) {
                $absensiData->push((object)[
                    'user' => $u,
                    'created_at' => null,
                    'status' => 'belum absen'
                ]);
            }
        } else {
            $absensiData = $absensiQuery->get();

            // Tambahkan user yang belum absen
            if ($filterType === 'bulanan') {
                $absenUserIds = Absensi::whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun)
                    ->pluck('user_id')
                    ->unique()
                    ->toArray();
            } else {
                $absenUserIds = $absensiData->pluck('user_id')->toArray();
            }

            $usersBelumAbsenQuery = User::where('role', 'karyawan')
                ->whereNotIn('id', $absenUserIds);

            if ($nama) {
                $usersBelumAbsenQuery->where('name', 'like', '%' . $nama . '%');
            }

            $usersBelumAbsen = $usersBelumAbsenQuery->get();

            foreach ($usersBelumAbsen as $u) {
                $absensiData->push((object)[
                    'user' => $u,
                    'created_at' => null,
                    'status' => 'belum absen'
                ]);
            }
        }

        // Hitung statistik berdasarkan filter type
        $totalKaryawan = User::where('role', 'karyawan')->count();

        if ($filterType === 'bulanan') {
            // Statistik bulanan
            $hadirPeriode = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'hadir')
                ->count();

            $terlambatPeriode = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'terlambat')
                ->count();

            $izinPeriode = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'izin')
                ->count();

            $sakitPeriode = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'sakit')
                ->count();

            $sudahAbsenPeriode = Absensi::whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->distinct('user_id')
                ->count('user_id');

            $totalAbsenPeriode = $hadirPeriode + $terlambatPeriode + $izinPeriode + $sakitPeriode;

            $statistik = [
                'total_karyawan' => $totalKaryawan,
                'hadir' => $hadirPeriode,
                'terlambat' => $terlambatPeriode,
                'izin' => $izinPeriode,
                'sakit' => $sakitPeriode,
                'total_absen' => $totalAbsenPeriode,
                'unique_users' => $sudahAbsenPeriode
            ];
        } else {
            // Statistik hari ini
            $hadirHariIni = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'hadir')
                ->count();

            $terlambatHariIni = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'terlambat')
                ->count();

            $izinHariIni = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'izin')
                ->count();

            $sakitHariIni = Absensi::whereDate('created_at', Carbon::today())
                ->where('status', 'sakit')
                ->count();

            $sudahAbsenHariIni = Absensi::whereDate('created_at', Carbon::today())
                ->distinct('user_id')
                ->count();

            $belumAbsenHariIni = $totalKaryawan - $sudahAbsenHariIni;

            $statistik = [
                'total_karyawan' => $totalKaryawan,
                'hadir' => $hadirHariIni,
                'terlambat' => $terlambatHariIni,
                'izin' => $izinHariIni,
                'sakit' => $sakitHariIni,
                'belum_absen' => $belumAbsenHariIni,
                'sudah_absen' => $sudahAbsenHariIni
            ];
        }

        // Grafik batang: absensi per tanggal dalam bulan
        $chartRaw = Absensi::selectRaw('DAY(created_at) as hari, COUNT(*) as total')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        $labels = [];
        $data = [];
        foreach ($chartRaw as $row) {
            $labels[] = 'Tgl ' . $row->hari;
            $data[] = $row->total;
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        // Grafik pie chart status per orang
        $queryPie = Absensi::with('user')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        if ($nama) {
            $queryPie->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        $absensiFiltered = $queryPie->get();

        $pieChart = [
            'hadir' => $absensiFiltered->where('status', 'hadir')->count(),
            'izin' => $absensiFiltered->where('status', 'izin')->count(),
            'sakit' => $absensiFiltered->where('status', 'sakit')->count(),
            'terlambat' => $absensiFiltered->where('status', 'terlambat')->count(),
        ];

        // Ambil bonus karyawan
        $bonus = BonusKaryawan::with('user')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        return view('dashboard.pimpinan', compact(
            'absensiData',
            'chartData',
            'bulanList',
            'tahunList',
            'bulan',
            'tahun',
            'nama',
            'pieChart',
            'statistik',
            'statusFilter',
            'filterType',
            'bonus'
        ));
    }
}
