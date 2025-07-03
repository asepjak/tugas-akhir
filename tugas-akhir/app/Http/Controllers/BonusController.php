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
        // Validasi input sesuai dengan nama field di form
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah_bonus' => 'required|numeric|min:0',
            'bulan' => 'required|string|size:2',
            'tahun' => 'required|numeric|min:2020',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah ada bonus untuk karyawan di bulan dan tahun yang sama
        $existingBonus = BonusKaryawan::where('user_id', $request->user_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();

        if ($existingBonus) {
            return redirect()->back()
                ->with('error', 'Bonus untuk karyawan ini di bulan dan tahun tersebut sudah ada!')
                ->withInput();
        }

        try {
            // Simpan bonus baru
            $bonus = BonusKaryawan::create([
                'user_id' => $request->user_id,
                'jumlah_bonus' => $request->jumlah_bonus,
                'keterangan' => $request->keterangan,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            return redirect()->back()->with('success', 'Bonus berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan bonus: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Method untuk menghitung bonus otomatis berdasarkan kehadiran dan keuntungan
    public function calculateBonus(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'total_keuntungan' => 'nullable|numeric|min:0',
            'bulan' => 'required|string|size:2',
            'tahun' => 'required|numeric|min:2020',
        ]);

        $user = User::findOrFail($request->employee_id);
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Hitung total kehadiran
        $totalHadir = Absensi::where('user_id', $user->id)
            ->where('status', 'hadir')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // Hitung bonus kerajinan (20% dari total kehadiran * 20000)
        $bonusKerajinan = $totalHadir * 20000 * 0.20;

        // Hitung bonus keuntungan (5% dari total keuntungan)
        $bonusKeuntungan = ($request->total_keuntungan ?? 0) * 0.05;

        $totalBonus = $bonusKerajinan + $bonusKeuntungan;

        // Cek apakah sudah ada bonus untuk karyawan di bulan dan tahun yang sama
        $existingBonus = BonusKaryawan::where('user_id', $user->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        if ($existingBonus) {
            return redirect()->back()
                ->with('error', 'Bonus untuk karyawan ini di bulan dan tahun tersebut sudah ada!')
                ->withInput();
        }

        try {
            $bonus = BonusKaryawan::create([
                'user_id' => $user->id,
                'jumlah_bonus' => $totalBonus,
                'keterangan' => "Bonus kerajinan ({$totalHadir} hari): Rp " . number_format($bonusKerajinan, 0, ',', '.') .
                              " + Bonus keuntungan: Rp " . number_format($bonusKeuntungan, 0, ',', '.'),
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            return redirect()->back()->with('success', 'Bonus otomatis berhasil dihitung dan disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan bonus: ' . $e->getMessage())
                ->withInput();
        }
    }
}
