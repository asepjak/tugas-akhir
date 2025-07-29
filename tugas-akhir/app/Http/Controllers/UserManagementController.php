<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'karyawan')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'nullable|string|unique:users,username',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jabatan' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('public/foto', $foto);
        }

        User::create([
            'nama' => $validated['nama'],
            'name' => $validated['nama'], // field default Laravel
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'username' => $validated['username'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'status' => $validated['status'] ?? null,
            'foto' => $foto,
            'role' => 'karyawan'
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun karyawan berhasil ditambahkan.');
    }
    public function resetToken(Request $request, User $user)
    {
        $newToken = Str::random(60);

        $user->device_token = $newToken;
        $user->save();

        return back()->with('success', 'Token berhasil direset. Token baru: ' . $newToken);
    }
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'username' => 'nullable|string|unique:users,username,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jabatan' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $foto = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('public/foto', $foto);
            $user->foto = $foto;
        }

        $user->nama = $validated['nama'];
        $user->name = $validated['nama'];
        $user->email = $validated['email'];
        $user->username = $validated['username'] ?? null;
        $user->alamat = $validated['alamat'] ?? null;
        $user->no_hp = $validated['no_hp'] ?? null;
        $user->jabatan = $validated['jabatan'] ?? null;
        $user->status = $validated['status'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Akun karyawan berhasil diperbarui.');
    }
}
