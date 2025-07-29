<?php

// app/Http/Middleware/ValidateDeviceToken.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidateDeviceToken
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $cookieToken = $request->cookie('device_token');

        if (!$user || !$cookieToken || $user->device_token !== $cookieToken) {
            return redirect()->back()->with('error', 'Akses ditolak: perangkat tidak valid untuk melakukan absensi.');
        }

        return $next($request);
    }
}
