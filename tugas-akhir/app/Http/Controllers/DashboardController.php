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
    public function karyawan(Request $request)
    {
        $user = Auth::user();
        $month = (int)$request->input('month', now()->month); // Ensure month is integer
        $year = (int)$request->input('year', now()->year); // Ensure year is integer

        // Hitung absensi hadir berdasarkan bulan dan tahun
        $absensis = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();

        $hadir = $absensis->count();

        // Ambil data permissions yang disetujui
        $approvedPermissions = Permission::where('user_id', $user->id)
            ->where('status', 'Disetujui')
            ->where(function($query) use ($month, $year) {
                $query->whereMonth('tanggal_mulai', $month)
                      ->whereYear('tanggal_mulai', $year);
            })
            ->get();

        // Hitung berdasarkan keterangan
        $sakit = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'sakit';
        })->count();

        $izin = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'izin';
        })->count();

        $cuti = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'cuti';
        })->count();

        $perjalanan = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'perjalanan keluar kota';
        })->count();

        // Ambil permissions untuk tabel riwayat
        $permissions = Permission::where('user_id', $user->id)
            ->where(function($query) use ($month, $year) {
                $query->whereMonth('tanggal_mulai', $month)
                      ->whereYear('tanggal_mulai', $year);
            })
            ->latest()
            ->get();

        return view('dashboard.karyawan', compact(
            'month',
            'year',
            'hadir',
            'sakit',
            'izin',
            'cuti',
            'perjalanan',
            'permissions'
        ));
    }
}
