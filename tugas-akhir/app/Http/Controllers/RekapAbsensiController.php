<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.rekap.bulanan');
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $users = User::where('role', 'karyawan')->get();
        $data = [];

        // Hitung jumlah hari kerja (Senin - Jumat)
        $jumlahHariKerja = 0;
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekday()) {
                $jumlahHariKerja++;
            }
        }

        foreach ($users as $user) {
            $absensi = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            if ($absensi->count() > 0) {
                $hadirTepat = $absensi->where('status', 'hadir')->count();
                $terlambat = $absensi->where('status', 'terlambat')->count();
                $hadir = $hadirTepat + $terlambat;

                $izin = $absensi->where('status', 'izin')->count();
                $sakit = $absensi->where('status', 'sakit')->count();

                $data[] = [
                    'user' => $user,
                    'jumlah_hadir' => $hadir,
                    'jumlah_izin' => $izin,
                    'jumlah_sakit' => $sakit,
                    'jumlah_terlambat' => $terlambat,
                    'jumlah_total' => $hadir + $izin + $sakit,
                ];
            }
        }

        return view('admin.rekap.bulanan', compact('data', 'bulan', 'tahun', 'jumlahHariKerja'));
    }

    public function exportBulanan(Request $request)
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

            if ($absensi->count() > 0) {
                $hadirTepat = $absensi->where('status', 'hadir')->count();
                $terlambat = $absensi->where('status', 'terlambat')->count();
                $hadir = $hadirTepat + $terlambat;

                $izin = $absensi->where('status', 'izin')->count();
                $sakit = $absensi->where('status', 'sakit')->count();

                $data[] = [
                    'Nama' => $user->nama ?? $user->name,
                    'Hadir' => $hadir,
                    'Izin' => $izin,
                    'Sakit' => $sakit,
                    'Terlambat' => $terlambat,
                    'Total' => $hadir + $izin + $sakit,
                ];
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Absensi Bulan');

        // Header
        $headers = ['Nama', 'Hadir', 'Izin', 'Sakit', 'Terlambat', 'Total'];
        $sheet->fromArray($headers, null, 'A1');

        // Data
        $sheet->fromArray($data, null, 'A2');

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="rekap_absensi_' . $bulan . '_' . $tahun . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function print(Request $request)
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

            if ($absensi->count() > 0) {
                $hadirTepat = $absensi->where('status', 'hadir')->count();
                $terlambat = $absensi->where('status', 'terlambat')->count();
                $hadir = $hadirTepat + $terlambat;

                $izin = $absensi->where('status', 'izin')->count();
                $sakit = $absensi->where('status', 'sakit')->count();

                $data[] = [
                    'user' => $user,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'terlambat' => $terlambat,
                    'total' => $hadir + $izin + $sakit,
                ];
            }
        }

        return view('admin.rekap.print', compact('data', 'bulan', 'tahun'));
    }
}
