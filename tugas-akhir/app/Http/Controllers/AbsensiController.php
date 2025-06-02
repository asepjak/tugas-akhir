<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // Konstanta untuk jam batas masuk
    const JAM_BATAS_MASUK = '08:30:00';

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

        // Log untuk debugging
        if (config('app.debug')) {
            Log::info('=== ABSENSI DEBUG ===');
            Log::info('Environment: ' . app()->environment());
            Log::info('Client IP: ' . $clientIp);
            Log::info('Allowed IPs: ' . implode(', ', $allowedIps));
            Log::info('User Agent: ' . $request->userAgent());
            Log::info('Jam Masuk: ' . $jamMasuk);
            Log::info('Jam Batas: ' . self::JAM_BATAS_MASUK);
        }

        // Validasi IP Address
        if (!in_array($clientIp, $allowedIps)) {
            $message = app()->environment('local')
                ? "Development mode - IP terdeteksi: {$clientIp}. IP yang diizinkan: " . implode(', ', $allowedIps)
                : 'Anda hanya dapat absen dari jaringan kantor!';

            return back()->with('error', $message);
        }

        // Cek apakah sudah absen hari ini
        $existing = Absensi::whereDate('tanggal', $now->toDateString())
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah absen masuk hari ini.');
        }

        // PERBAIKAN LOGIKA KETERLAMBATAN
        // Buat Carbon instance untuk hari yang sama
        $tanggalHariIni = $now->toDateString();
        $jamBatasMasuk = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalHariIni . ' ' . self::JAM_BATAS_MASUK);
        $jamMasukCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalHariIni . ' ' . $jamMasuk);

        $isTerlambat = $jamMasukCarbon->gt($jamBatasMasuk);
        $status = $isTerlambat ? 'terlambat' : 'hadir';

        // Hitung durasi keterlambatan jika terlambat
        $durasiTerlambat = null;
        if ($isTerlambat) {
            $selisihMenit = $jamMasukCarbon->diffInMinutes($jamBatasMasuk);
            $jam = intdiv($selisihMenit, 60);
            $menit = $selisihMenit % 60;

            if ($jam > 0) {
                $durasiTerlambat = "{$jam} jam {$menit} menit";
            } else {
                $durasiTerlambat = "{$menit} menit";
            }
        }

        // Debug log untuk memastikan logika benar
        if (config('app.debug')) {
            Log::info('=== LOGIKA KETERLAMBATAN ===');
            Log::info('Jam Batas: ' . $jamBatasMasuk->format('Y-m-d H:i:s'));
            Log::info('Jam Masuk: ' . $jamMasukCarbon->format('Y-m-d H:i:s'));
            Log::info('Is Terlambat: ' . ($isTerlambat ? 'Ya' : 'Tidak'));
            Log::info('Status: ' . $status);
            Log::info('Durasi Terlambat: ' . ($durasiTerlambat ?? 'Tidak terlambat'));
        }

        // Simpan data absensi
        Absensi::create([
            'user_id' => Auth::id(),
            'tanggal' => $now->toDateString(),
            'jam' => $jamMasuk,
            'ip_address' => $clientIp,
            'status' => $status,
            'jam_terlambat' => $isTerlambat ? $jamMasuk : null,
            'durasi_terlambat' => $durasiTerlambat,
        ]);

        // Pesan response
        $message = $isTerlambat
            ? "Absensi berhasil dicatat. Anda terlambat masuk pada jam {$jamMasuk} (terlambat {$durasiTerlambat})."
            : 'Absensi berhasil dicatat. Anda masuk tepat waktu.';

        return back()->with('success', $message);
    }

    public function keluar(Request $request)
    {
        $allowedIps = $this->getAllowedIps();
        $clientIp = $this->getRealIpAddr($request);
        $userId = Auth::id();
        $today = Carbon::now()->toDateString();

        // Validasi IP Address untuk absen keluar juga
        if (!in_array($clientIp, $allowedIps)) {
            $message = app()->environment('local')
                ? "Development mode - IP terdeteksi: {$clientIp}. IP yang diizinkan: " . implode(', ', $allowedIps)
                : 'Anda hanya dapat absen dari jaringan kantor!';

            return back()->with('error', $message);
        }

        // Cari data absensi hari ini
        $absensi = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($absensi->jam_keluar) {
            return back()->with('error', 'Anda sudah melakukan absen keluar hari ini.');
        }

        // Update jam keluar
        $jamKeluar = '00:38:00';
        $absensi->update([
            'jam_keluar' => $jamKeluar,
        ]);

        return back()->with('success', "Absen keluar berhasil dicatat pada jam {$jamKeluar}.");
    }

    private function getAllowedIps()
    {
        $ips = [
            'local' => [
                '127.0.0.1',
                '::1',
                '10.10.8.194',
                '192.168.1.100',
                '192.168.1.1',
                '192.168.0.1',
            ],
            'staging' => [
                '10.10.8.194',
            ],
            'production' => [
                '10.10.8.194',
            ]
        ];

        $environment = app()->environment();
        return $ips[$environment] ?? $ips['production'];
    }

    private function getRealIpAddr($request)
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            $ip = $request->server($header);

            if (!empty($ip) && $ip !== 'unknown') {
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }

                $ip = trim($ip);

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $request->ip();
    }

    public function checkIp(Request $request)
    {
        $clientIp = $this->getRealIpAddr($request);
        $allowedIps = $this->getAllowedIps();

        $ipInfo = [
            'environment' => app()->environment(),
            'detected_ip' => $clientIp,
            'allowed_ips' => $allowedIps,
            'is_allowed' => in_array($clientIp, $allowedIps),
            'laravel_ip' => $request->ip(),
            'server_remote_addr' => $request->server('REMOTE_ADDR'),
            'all_headers' => [
                'HTTP_CF_CONNECTING_IP' => $request->server('HTTP_CF_CONNECTING_IP'),
                'HTTP_CLIENT_IP' => $request->server('HTTP_CLIENT_IP'),
                'HTTP_X_FORWARDED_FOR' => $request->server('HTTP_X_FORWARDED_FOR'),
                'HTTP_X_REAL_IP' => $request->server('HTTP_X_REAL_IP'),
                'HTTP_X_FORWARDED' => $request->server('HTTP_X_FORWARDED'),
                'REMOTE_ADDR' => $request->server('REMOTE_ADDR'),
            ],
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ];

        return response()->json($ipInfo, 200, [], JSON_PRETTY_PRINT);
    }

    public function reset(Request $request)
    {
        if (!app()->environment('local')) {
            abort(403, 'Method ini hanya tersedia di development environment');
        }

        $deleted = Absensi::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::now()->toDateString())
            ->delete();

        $message = $deleted > 0
            ? 'Data absensi hari ini berhasil direset.'
            : 'Tidak ada data absensi hari ini untuk direset.';

        return back()->with('success', $message);
    }
}
