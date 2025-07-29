<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan ini ada
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;



class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Buat device token baru & simpan ke DB + Cookie
            $deviceToken = Str::random(40);
            $user->device_token = $deviceToken;
            $user->save();

            Cookie::queue('device_token', $deviceToken, 60 * 24 * 30); // 30 hari

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'pimpinan') {
                return redirect()->intended('/pimpinan/dashboard');
            } else {
                return redirect()->intended('/karyawan/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus cookie device_token
        cookie()->queue(cookie()->forget('device_token'));

        return redirect('/');
    }
}
