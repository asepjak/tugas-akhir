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

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Validation rules
        $rules = [
            'nama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:1000',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'status' => 'nullable|in:Aktif,Tidak Aktif,Cuti',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ];

        // Custom validation messages
        $messages = [
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'username.unique' => 'Username sudah digunakan oleh user lain.',
            'password.min' => 'Password minimal 6 karakter.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus: jpeg, png, jpg, atau gif.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'status.in' => 'Status harus salah satu dari: Aktif, Tidak Aktif, atau Cuti.',
        ];

        try {
            $validated = $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            try {
                // Delete old photo if exists
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }

                // Store new photo
                $path = $request->file('foto')->store('foto', 'public');
                $validated['foto'] = $path;
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage())->withInput();
            }
        }

        // Update user data
        try {
            // Update basic fields
            $user->nama = $validated['nama'] ?? $user->nama;
            $user->alamat = $validated['alamat'] ?? $user->alamat;
            $user->email = $validated['email'] ?? $user->email;
            $user->no_hp = $validated['no_hp'] ?? $user->no_hp;
            $user->jabatan = $validated['jabatan'] ?? $user->jabatan;
            $user->status = $validated['status'] ?? $user->status;
            $user->username = $validated['username'] ?? $user->username;

            // Update photo if uploaded
            if (isset($validated['foto'])) {
                $user->foto = $validated['foto'];
            }

            // Update password only if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Dynamic redirect based on user role
            $redirectRoute = $this->getProfileEditRoute($user->role);

            return redirect()->route($redirectRoute)->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Get the appropriate profile edit route based on user role
     */
    private function getProfileEditRoute($role)
    {
        switch ($role) {
            case 'admin':
                return 'admin.profile.edit';
            case 'pimpinan':
                return 'pimpinan.profile.edit';
            case 'karyawan':
                return 'profile.edit';
            default:
                return 'profile.edit';
        }
    }

    /**
     * Get user profile data (for API or AJAX requests)
     */
    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'username' => $user->username,
                'alamat' => $user->alamat,
                'no_hp' => $user->no_hp,
                'jabatan' => $user->jabatan,
                'status' => $user->status,
                'role' => $user->role,
                'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Change password only (separate endpoint for security)
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak benar.']);
        }

        try {
            $user->password = Hash::make($validated['new_password']);
            $user = Auth::user();

            $redirectRoute = $this->getProfileEditRoute($user->role);

            return redirect()->route($redirectRoute)->with('success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}
