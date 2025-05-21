<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
        $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable',
            'email' => 'required|email',
            'no_hp' => 'nullable',
            'jabatan' => 'nullable',
            'status' => 'nullable',
            'username' => 'required',
            'password' => 'nullable|min:6',
        ]);

        // SOLUSI MENGGUNAKAN DB::table() - Pasti bekerja
        $updateData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
            'username' => $request->username,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')
            ->where('id', Auth::id())
            ->update($updateData);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
