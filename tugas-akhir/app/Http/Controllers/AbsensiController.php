<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // Jam batas masuk kantor
    const JAM_BATAS_MASUK = '08:00:00';

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        $absensi = Absensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->limit(30)
            ->get();

        $absenHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return view('karyawan.absensi.index', compact('absensi', 'absenHariIni'));
    }

    public function store(Request $request)
    {
        $allowedIps = $this->getAllowedIps();
        $clientIp = $this->getRealIpAddr($request);
        $now = Carbon::now();
        $jamMasuk = $now->format('H:i:s');

        if (!in_array($clientIp, $allowedIps)) {
            return back()->with('error', 'Anda hanya dapat absen dari jaringan kantor.');
        }

        $existing = Absensi::whereDate('tanggal', $now->toDateString())
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah absen masuk hari ini.');
        }

        // Perhitungan keterlambatan
        $tanggalHariIni = $now->toDateString();
        $jamMasukCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalHariIni . ' ' . $jamMasuk);
        $jamBatasMasuk = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalHariIni . ' ' . self::JAM_BATAS_MASUK);

        $isTerlambat = $jamMasukCarbon->gt($jamBatasMasuk);
        $status = $isTerlambat ? 'terlambat' : 'hadir';

        $durasiTerlambat = null;
        if ($isTerlambat) {
            $selisih = $jamMasukCarbon->diff($jamBatasMasuk);
            $jam = $selisih->h;
            $menit = $selisih->i;

            $durasiTerlambat = $jam > 0
                ? "{$jam} jam {$menit} menit"
                : "{$menit} menit";
        }

        Absensi::create([
            'user_id' => Auth::id(),
            'tanggal' => $now->toDateString(),
            'jam' => $jamMasuk,
            'ip_address' => $clientIp,
            'status' => $status,
            'jam_terlambat' => $isTerlambat ? $jamMasuk : null,
            'durasi_terlambat' => $durasiTerlambat,
        ]);

        $message = $isTerlambat
            ? "Absensi berhasil dicatat. Anda terlambat {$durasiTerlambat}."
            : 'Absensi berhasil dicatat. Anda masuk tepat waktu.';

        return back()->with('success', $message);
    }

    public function keluar(Request $request)
    {
        $allowedIps = $this->getAllowedIps();
        $clientIp = $this->getRealIpAddr($request);
        $userId = Auth::id();
        $today = Carbon::now()->toDateString();

        if (!in_array($clientIp, $allowedIps)) {
            return back()->with('error', 'Anda hanya dapat absen keluar dari jaringan kantor.');
        }

        $absensi = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($absensi->jam_keluar) {
            return back()->with('error', 'Anda sudah melakukan absen keluar hari ini.');
        }

        $jamKeluar = Carbon::now()->format('H:i:s');
        $absensi->update([
            'jam_keluar' => $jamKeluar,
        ]);

        return back()->with('success', "Absen keluar berhasil pada jam {$jamKeluar}.");
    }

    private function getAllowedIps()
    {
        $ips = [
            'local' => ['127.0.0.1', '::1', '10.10.8.194', '192.168.1.1'],
            'staging' => ['10.10.8.194'],
            'production' => ['10.10.8.194']
        ];

        return $ips[app()->environment()] ?? $ips['production'];
    }

    private function getRealIpAddr($request)
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            $ip = $request->server($header);
            if (!empty($ip) && strtolower($ip) !== 'unknown') {
                $ipList = explode(',', $ip);
                foreach ($ipList as $i) {
                    $cleanIp = trim($i);
                    if (filter_var($cleanIp, FILTER_VALIDATE_IP)) {
                        return $cleanIp;
                    }
                }
            }
        }

        return $request->ip();
    }

    public function checkIp(Request $request)
    {
        $clientIp = $this->getRealIpAddr($request);
        $allowedIps = $this->getAllowedIps();

        return response()->json([
            'environment' => app()->environment(),
            'client_ip' => $clientIp,
            'allowed_ips' => $allowedIps,
            'is_allowed' => in_array($clientIp, $allowedIps),
        ]);
    }

    public function reset(Request $request)
    {
        if (!app()->environment('local')) {
            abort(403, 'Reset hanya tersedia di local.');
        }

        $deleted = Absensi::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::now()->toDateString())
            ->delete();

        return back()->with('success', $deleted
            ? 'Data absensi berhasil direset.'
            : 'Tidak ada data untuk direset.');
    }
}
