<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = now()->format('Y');

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

        // ✅ Ambil absensi hari ini
        $absensiHariIni = \App\Models\Absensi::with('user')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->latest()
            ->get();

        // Data grafik absensi per hari di bulan tertentu
        $chartRaw = \App\Models\Absensi::selectRaw('DAY(created_at) as hari, COUNT(*) as total')
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

        // ✅ Pastikan semua variabel dikirim ke view
        return view('admin.dashboard', [
            'absensiHariIni' => $absensiHariIni,
            'chartData' => $chartData,
            'bulanList' => $bulanList,
            'bulanSekarang' => $bulan, // ← tambahkan ini
        ]);
    }
}
