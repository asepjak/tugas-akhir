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
    const JAM_BATAS_MASUK = '08:30:00'; // Disesuaikan dengan jam di view

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

        // Tentukan view berdasarkan role
        $view = $this->getAbsensiViewByRole($user->role);

        return view($view, compact('absensi', 'absenHariIni'));
    }

    public function store(Request $request)
    {
        $allowedIps = $this->getAllowedIps();
        $clientIp = $this->getRealIpAddr($request);
        $now = Carbon::now();
        $jamMasuk = $now->format('H:i:s');
        $user = Auth::user();

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

        return redirect()->route($this->getAbsensiRouteByRole($user->role))
                         ->with('success', $message);
    }

    public function keluar(Request $request)
    {
        $allowedIps = $this->getAllowedIps();
        $clientIp = $this->getRealIpAddr($request);
        $userId = Auth::id();
        $user = Auth::user();
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

        return redirect()->route($this->getAbsensiRouteByRole($user->role))
                         ->with('success', "Absen keluar berhasil pada jam {$jamKeluar}.");
    }

    /**
     * Method untuk menentukan view berdasarkan role user
     */
    private function getAbsensiViewByRole($role)
    {
        return match ($role) {
            'admin' => 'admin.absensi.index',
            'pimpinan' => 'pimpinan.absensi.index',
            'karyawan' => 'karyawan.absensi.index',
            default => 'karyawan.absensi.index'
        };
    }

    /**
     * Method untuk menentukan route redirect berdasarkan role
     */
    private function getAbsensiRouteByRole($role)
    {
        return match ($role) {
            'admin' => 'admin.absensi.index',
            'pimpinan' => 'pimpinan.absensi.index',
            'karyawan' => 'karyawan.absensi.index',
            default => 'karyawan.absensi.index'
        };
    }

    /**
     * Method untuk menentukan route keluar berdasarkan role
     */
    private function getKeluarRouteByRole($role)
    {
        return match ($role) {
            'admin' => 'admin.absensi.keluar',
            'pimpinan' => 'pimpinan.absensi.keluar',
            'karyawan' => 'absensi.keluar',
            default => 'absensi.keluar'
        };
    }

    /**
     * Method untuk menentukan route store berdasarkan role
     */
    private function getStoreRouteByRole($role)
    {
        return match ($role) {
            'admin' => 'admin.absensi.store',
            'pimpinan' => 'pimpinan.absensi.store',
            'karyawan' => 'absensi.store',
            default => 'absensi.store'
        };
    }

    /**
     * Method untuk menentukan route check-ip berdasarkan role
     */
    private function getCheckIpRouteByRole($role)
    {
        return match ($role) {
            'admin' => 'admin.absensi.check-ip',
            'pimpinan' => 'pimpinan.absensi.check-ip',
            'karyawan' => 'karyawan.absensi.check-ip',
            default => 'karyawan.absensi.check-ip'
        };
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

    /**
     * Method untuk mendapatkan data routes yang diperlukan untuk view
     * Ini akan digunakan di view untuk menentukan action form
     */
    public function getRoutesForView()
    {
        $user = Auth::user();

        return [
            'store' => route($this->getStoreRouteByRole($user->role)),
            'keluar' => route($this->getKeluarRouteByRole($user->role)),
            'check_ip' => route($this->getCheckIpRouteByRole($user->role)),
            'reset' => $user->role === 'karyawan' ? route('absensi.reset') : route($user->role . '.absensi.reset')
        ];
    }
}
