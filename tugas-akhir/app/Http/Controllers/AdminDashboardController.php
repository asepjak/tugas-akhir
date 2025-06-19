<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = now()->format('Y');
        $nama = $request->get('nama');

        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        // ✅ Absensi hari ini
        $absensiHariIni = Absensi::with('user')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        // ✅ Grafik batang absensi harian di bulan tertentu
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

        // ✅ Grafik pie per orang (hadir, sakit, izin, terlambat)
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
            'labels' => ['Hadir', 'Izin', 'Sakit', 'Terlambat'],
            'data' => [
                $absensiFiltered->where('status', 'hadir')->count(),
                $absensiFiltered->where('status', 'izin')->count(),
                $absensiFiltered->where('status', 'sakit')->count(),
                $absensiFiltered->where('status', 'terlambat')->count(),
            ]
        ];

        return view('admin.dashboard', compact(
            'absensiHariIni',
            'chartData',
            'bulanList',
            'bulan',
            'nama',
            'pieChart'
        ));
    }
}
