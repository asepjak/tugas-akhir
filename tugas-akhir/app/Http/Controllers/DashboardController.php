<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboard.admin');
    }

    public function pimpinan()
    {
        return view('dashboard.pimpinan');
    }

    public function karyawan(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', now()->format('m')); // default bulan ini
        $year = $request->input('year', now()->format('Y')); // default tahun ini

        $absensis = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();

        $hadir = $absensis->count(); // karena jika ada absensi, dianggap hadir

        // Ambil data izin milik user
        $permissions = Permission::where('user_id', $user->id)->latest()->get();

        // Hitung jumlah izin dan sakit dari data permissions jika ada kolom jenis
        $sakit = $permissions->where('jenis', 'sakit')->count();
        $izin  = $permissions->where('jenis', 'izin')->count();

        return view('dashboard.karyawan', compact(
            'month',
            'year',
            'hadir',
            'sakit',
            'izin',
            'permissions'
        ));
    }
}
