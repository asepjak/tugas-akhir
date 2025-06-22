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

        // Hitung absensi hadir berdasarkan bulan dan tahun
        $absensis = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();

        $hadir = $absensis->count(); // karena jika ada absensi, dianggap hadir

        // Ambil data permissions yang disetujui berdasarkan bulan dan tahun yang dipilih
        $approvedPermissions = Permission::where('user_id', $user->id)
            ->where('status', 'Disetujui') // hanya yang disetujui
            ->where(function($query) use ($month, $year) {
                // Cek apakah tanggal mulai atau rentang tanggal izin ada di bulan/tahun yang dipilih
                $query->whereMonth('tanggal_mulai', $month)
                      ->whereYear('tanggal_mulai', $year);
            })
            ->get();

        // Hitung berdasarkan keterangan (case insensitive)
        $sakit = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'sakit';
        })->count();

        $izin = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'izin';
        })->count();

        $cuti = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'cuti';
        })->count();

        // Jika ada keterangan lain seperti 'perjalanan keluar kota', bisa ditambahkan
        $perjalanan = $approvedPermissions->filter(function($item) {
            return strtolower($item->keterangan) === 'perjalanan keluar kota';
        })->count();

        // Ambil semua permissions untuk tabel riwayat (tidak dibatasi bulan/tahun)
        $allPermissions = Permission::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard.karyawan', compact(
            'month',
            'year',
            'hadir',
            'sakit',
            'izin',
            'cuti',
            'perjalanan'
        ))->with('permissions', $allPermissions);
    }
}
