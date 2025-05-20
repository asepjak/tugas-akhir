<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'karyawan') {
            $today = Carbon::today();
            $user = Auth::user();

            // Cek apakah karyawan sudah absen hari ini
            $attendance = Attendance::where('user_id', $user->id)
                                  ->whereDate('date', $today)
                                  ->first();

            // Jika belum absen dan sudah lewat jam 9 pagi, tandai sebagai terlambat
            if (!$attendance && Carbon::now()->hour >= 9) {
                session()->flash('warning', 'Anda terlambat untuk absen masuk hari ini.');
            }

            // Share data attendance ke semua view
            view()->share('todayAttendance', $attendance);
        }

        return $next($request);
    }
}
