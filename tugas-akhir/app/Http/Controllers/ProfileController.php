<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User; // ✅ Tambahkan ini

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && Storage::exists('public/foto/'.$user->foto)) {
                Storage::delete('public/foto/'.$user->foto);
            }

            $filename = time().'.'.$request->foto->extension();
            $request->foto->storeAs('public/foto', $filename);
            $validated['foto'] = $filename; // Tambahkan ke validated data
        }

        // Update fields secara manual jika fill() masih bermasalah
        $user->nama = $validated['nama'];
        $user->alamat = $validated['alamat'] ?? null;
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'] ?? null;
        $user->jabatan = $validated['jabatan'] ?? null;
        $user->status = $validated['status'] ?? null;
        $user->username = $validated['username'];
        $user->foto = $validated['foto'] ?? $user->foto;

        // Update password jika ada
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        try {
            $user->save();
            return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: '.$e->getMessage());
        }
    }
}
