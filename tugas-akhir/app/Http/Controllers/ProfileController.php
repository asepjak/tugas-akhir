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
            'karyawan' => 'karyawan.profile.edit'
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
            'password' => 'nullable|string|min:6',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Dapatkan user dari database untuk memastikan instance model yang benar
            $userModel = User::findOrFail($user->id);

            // Siapkan data untuk update
            $updateData = [
                'nama'     => $validated['nama'],
                'name'     => $validated['nama'], // Untuk kolom default Laravel
                'email'    => $validated['email'],
                'username' => $validated['username'],
                'alamat'   => $validated['alamat'] ?? null,
                'no_hp'    => $validated['no_hp'] ?? null,
                'jabatan'  => $validated['jabatan'] ?? null,
                'status'   => $validated['status'] ?? null,
            ];

            // Proses upload foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($userModel->foto && Storage::disk('public')->exists($userModel->foto)) {
                    Storage::disk('public')->delete($userModel->foto);
                }

                // Upload foto baru
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('foto', $filename, 'public');

                // Tambahkan path foto ke data update
                $updateData['foto'] = $path;
            }

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Update data ke database menggunakan query builder
            $updated = User::where('id', $user->id)->update($updateData);

            if ($updated) {
                return redirect()->route($this->getProfileEditRoute($user->role))
                    ->with('success', 'Profil berhasil diperbarui.');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal memperbarui profil.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function getProfileEditRoute($role)
    {
        return match ($role) {
            'admin' => 'admin.profile.edit',
            'pimpinan' => 'pimpinan.profile.edit',
            'karyawan' => 'karyawan.profile.edit',
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

        // Update password menggunakan query builder
        User::where('id', $user->id)->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->route($this->getProfileEditRoute($user->role))
            ->with('success', 'Password berhasil diubah.');
    }
}
