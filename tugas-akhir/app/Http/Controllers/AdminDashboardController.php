<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = now()->format('Y');
        $nama = $request->get('nama');
        $statusFilter = $request->get('status');

        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        // Data absensi hari ini
        $absensiQuery = Absensi::with('user')
            ->whereDate('created_at', Carbon::today())
            ->latest();

        if ($statusFilter === 'sudah') {
            $absensiHariIni = $absensiQuery->get();
        } elseif ($statusFilter === 'belum') {
            $absenUserIds = Absensi::whereDate('created_at', Carbon::today())->pluck('user_id')->toArray();
            $usersBelumAbsen = User::whereNotIn('id', $absenUserIds)->get();
            $absensiHariIni = collect();
            foreach ($usersBelumAbsen as $u) {
                $absensiHariIni->push((object)[
                    'user' => $u,
                    'created_at' => null,
                    'status' => 'belum absen'
                ]);
            }
        } else {
            $absensiHariIni = $absensiQuery->get();
        }

        $totalKaryawan = User::count();
        $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
        $terlambatHariIni = $absensiHariIni->where('status', 'terlambat')->count();
        $belumAbsenHariIni = $totalKaryawan - $absensiHariIni->whereNotNull('created_at')->count();

        // Grafik batang absensi per hari dalam bulan
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
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($nama) {
            $queryPie->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%')
                  ->orWhere('nama', 'like', '%' . $nama . '%');
            });
        }

        $absensiFiltered = $queryPie->get();

        $pieChart = [
            'hadir' => $absensiFiltered->where('status', 'hadir')->count(),
            'izin' => $absensiFiltered->where('status', 'izin')->count(),
            'sakit' => $absensiFiltered->where('status', 'sakit')->count(),
            'terlambat' => $absensiFiltered->where('status', 'terlambat')->count(),
        ];

        return view('admin.dashboard', compact(
            'absensiHariIni',
            'chartData',
            'bulanList',
            'bulan',
            'nama',
            'pieChart',
            'totalKaryawan',
            'hadirHariIni',
            'terlambatHariIni',
            'belumAbsenHariIni',
            'statusFilter'
        ));
    }
}
