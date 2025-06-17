<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAbsensiExport;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $users = User::where('role', 'karyawan')->get();
        $data = [];

        foreach ($users as $user) {
            $absensi = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $data[] = [
                'user' => $user,
                'jumlah_hadir' => $absensi->where('status', 'hadir')->count(),
                'jumlah_izin' => $absensi->where('status', 'izin')->count(),
                'jumlah_sakit' => $absensi->where('status', 'sakit')->count(),
                'jumlah_terlambat' => $absensi->where('status', 'terlambat')->count(),
            ];
        }

        return view('admin.rekap.index', compact('data', 'bulan', 'tahun'));
    }

    public function export(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        return Excel::download(new RekapAbsensiExport($bulan, $tahun), 'rekap-absensi.xlsx');
    }
}
