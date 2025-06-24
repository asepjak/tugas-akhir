<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $view = match ($user->role) {
            'admin' => 'admin.profile.edit',
            'pimpinan' => 'pimpinan.profile.edit',
            
        };

        return view($view, compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'alamat'   => 'nullable|string|max:1000',
            'no_hp'    => 'nullable|string|max:20',
            'jabatan'  => 'nullable|string|max:255',
            'status'   => 'nullable|in:Aktif,Tidak Aktif,Cuti',
            'password' => 'nullable|string|min:6|confirmed',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload foto baru
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')->store('foto', 'public');
            $user->foto = $path;
        }

        // Simpan data
        $user->nama     = $validated['nama'];
        $user->name     = $validated['nama']; // wajib untuk kolom default Laravel
        $user->email    = $validated['email'];
        $user->username = $validated['username'];
        $user->alamat   = $validated['alamat'] ?? null;
        $user->no_hp    = $validated['no_hp'] ?? null;
        $user->jabatan  = $validated['jabatan'] ?? null;
        $user->status   = $validated['status'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user = Auth::user();

        return redirect()->route($this->getProfileEditRoute($user->role))
            ->with('success', 'Profil berhasil diperbarui.');
    }

    private function getProfileEditRoute($role)
    {
        return match ($role) {
            'admin' => 'admin.profile.edit',
            'pimpinan' => 'pimpinan.profile.edit',

        };
    }

    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id'       => $user->id,
                'nama'     => $user->nama,
                'email'    => $user->email,
                'username' => $user->username,
                'alamat'   => $user->alamat,
                'no_hp'    => $user->no_hp,
                'jabatan'  => $user->jabatan,
                'status'   => $user->status,
                'role'     => $user->role,
                'foto'     => $user->foto ? asset('storage/' . $user->foto) : null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->password = Hash::make($validated['new_password']);
        $user = Auth::user();

        return redirect()->route($this->getProfileEditRoute($user->role))
            ->with('success', 'Password berhasil diubah.');
    }
}
