<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

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
            'nama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/' . $user->foto)) {
                Storage::delete('public/' . $user->foto);
            }
            $filename = $request->file('foto')->store('foto', 'public');
            $validated['foto'] = $filename;
        }


        // ✅ SOLUSI 1: Update field by field (paling aman)
        if (isset($validated['nama'])) $user->nama = $validated['nama'];
        if (isset($validated['alamat'])) $user->alamat = $validated['alamat'];
        if (isset($validated['email'])) $user->email = $validated['email'];
        if (isset($validated['no_hp'])) $user->no_hp = $validated['no_hp'];
        if (isset($validated['jabatan'])) $user->jabatan = $validated['jabatan'];
        if (isset($validated['status'])) $user->status = $validated['status'];
        if (isset($validated['username'])) $user->username = $validated['username'];

        // Update foto jika ada
        if (isset($validated['foto'])) {
            $user->foto = $validated['foto'];
        }

        // Update password hanya jika diisi
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        try {
            $user->save();
            return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}
