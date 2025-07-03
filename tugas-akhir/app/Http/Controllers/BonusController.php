<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BonusKaryawan;
use App\Models\User;
use App\Models\Absensi;

class BonusController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));

        $bonusData = BonusKaryawan::with('user')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderByDesc('created_at')
            ->get();

        $employees = User::where('role', 'karyawan')->get();

        return view('pimpinan.bonus.index', compact('bonusData', 'employees', 'bulan', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'total_keuntungan' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($request->employee_id);
        $bulan = now()->format('m');
        $tahun = now()->format('Y');

        $totalHadir = Absensi::where('user_id', $user->id)
            ->where('status', 'hadir')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        $bonusKerajinan = $totalHadir * 20000 * 0.20;
        $bonusKeuntungan = ($request->total_keuntungan ?? 0) * 0.05;

        $totalBonus = $bonusKerajinan + $bonusKeuntungan;

        $bonus = BonusKaryawan::create([
            'user_id' => $user->id,
            'jumlah_bonus' => $totalBonus,
            'keterangan' => "Bonus kerajinan dan keuntungan",
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        return redirect()->back()->with('success', 'Bonus berhasil disimpan.');
    }
}
