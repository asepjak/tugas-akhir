<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::where('user_id', Auth::id())->latest()->get();
        return view('karyawan.absensi.index', compact('absensi'));
    }

    public function store(Request $request)
    {
        // IP yang diizinkan - sesuaikan dengan environment
        $allowedIps = $this->getAllowedIps();

        $clientIp = $this->getRealIpAddr($request);

        // Debug mode - set di .env
        if (config('app.debug')) {
            Log::info('=== ABSENSI DEBUG ===');
            Log::info('Environment: ' . app()->environment());
            Log::info('Client IP: ' . $clientIp);
            Log::info('Allowed IPs: ' . implode(', ', $allowedIps));
            Log::info('User Agent: ' . $request->userAgent());
        }

        // 1. Cek IP kantor
        if (!in_array($clientIp, $allowedIps)) {
            $message = app()->environment('local')
                ? "Development mode - IP terdeteksi: {$clientIp}. IP yang diizinkan: " . implode(', ', $allowedIps)
                : 'Anda hanya dapat absen dari jaringan kantor!';

            return back()->with('error', $message);
        }

        // 2. Cek apakah sudah absen hari ini
        $existing = Absensi::whereDate('tanggal', now())
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah absen hari ini.');
        }

        // 3. Catat absensi
        Absensi::create([
            'user_id' => Auth::id(),
            'tanggal' => now()->toDateString(),
            'jam' => now()->toTimeString(),
            'ip_address' => $clientIp,
        ]);

        return back()->with('success', 'Absensi berhasil dicatat.');
    }

    public function keluar(Request $request)
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $absensi = Absensi::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($absensi->jam_keluar) {
            return back()->with('error', 'Anda sudah melakukan absen keluar.');
        }

        $absensi->update([
            'jam_keluar' => now()->toTimeString(),
        ]);

        return back()->with('success', 'Absen keluar berhasil dicatat.');
    }



    /**
     * Mendapatkan daftar IP yang diizinkan berdasarkan environment
     */
    private function getAllowedIps()
    {
        if (app()->environment('local')) {
            // Development - izinkan localhost dan IP lokal
            return [
                '127.0.0.1',
                '::1',
                '10.10.8.194',
                '192.168.1.100', // contoh IP lokal lain
                // tambahkan IP development lainnya
            ];
        }

        if (app()->environment('staging')) {
            // Staging - IP testing
            return [
                '10.10.8.194',
                // IP staging server
            ];
        }

        // Production - hanya IP kantor
        return [
            '10.10.8.194'
        ];
    }

    /**
     * Fungsi untuk mendapatkan IP address yang sebenarnya
     */
    private function getRealIpAddr($request)
    {
        // Prioritas header untuk deteksi IP
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            $ip = $request->server($header);
            if (!empty($ip) && $ip !== 'unknown') {
                // Jika ada multiple IP (comma separated), ambil yang pertama
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);

                // Validasi IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                } elseif (filter_var($ip, FILTER_VALIDATE_IP)) {
                    // Terima IP private/reserved untuk development
                    return $ip;
                }
            }
        }

        // Fallback ke Laravel default
        return $request->ip();
    }

    /**
     * Method untuk testing - tampilkan informasi IP
     */
    public function checkIp(Request $request)
    {
        $ipInfo = [
            'environment' => app()->environment(),
            'detected_ip' => $this->getRealIpAddr($request),
            'allowed_ips' => $this->getAllowedIps(),
            'laravel_ip' => $request->ip(),
            'server_remote_addr' => $request->server('REMOTE_ADDR'),
            'headers' => [
                'HTTP_CLIENT_IP' => $request->server('HTTP_CLIENT_IP'),
                'HTTP_X_FORWARDED_FOR' => $request->server('HTTP_X_FORWARDED_FOR'),
                'HTTP_X_REAL_IP' => $request->server('HTTP_X_REAL_IP'),
                'HTTP_CF_CONNECTING_IP' => $request->server('HTTP_CF_CONNECTING_IP'), // Cloudflare
            ]
        ];

        return response()->json($ipInfo, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Method untuk bypass IP checking (hanya untuk development)
     */
    public function storeBypass(Request $request)
    {
        if (!app()->environment('local')) {
            abort(403, 'Method ini hanya tersedia di development');
        }

        // Cek apakah sudah absen hari ini
        $existing = Absensi::whereDate('tanggal', now())
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah absen hari ini.');
        }

        // Catat absensi tanpa cek IP
        Absensi::create([
            'user_id' => Auth::id(),
            'tanggal' => now()->toDateString(),
            'jam' => now()->toTimeString(),
            'ip_address' => $this->getRealIpAddr($request) . ' (bypass)',
        ]);

        return back()->with('success', 'Absensi berhasil dicatat (development mode).');
    }
}
