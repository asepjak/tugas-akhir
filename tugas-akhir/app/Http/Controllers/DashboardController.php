<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;

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

    $attendances = Attendance::where('user_id', $user->id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->get();

    $hadir = $attendances->where('status', 'Hadir')->count();
    $sakit = $attendances->where('status', 'Sakit')->count();
    $izin  = $attendances->where('status', 'Izin')->count();

    return view('dashboard.karyawan', compact('hadir', 'sakit', 'izin', 'month', 'year'));
}
}
