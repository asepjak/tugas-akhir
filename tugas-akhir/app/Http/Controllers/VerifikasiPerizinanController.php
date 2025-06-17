<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifikasiPerizinan;
use App\Models\Permission;
use App\Models\User;

class VerifikasiPerizinanController extends Controller
{
    public function index()
    {
        $data = VerifikasiPerizinan::with('user')->latest()->get();
        return view('admin.verifikasi.index', compact('data'));
    }

    public function create()
    {
        $users = User::where('role', 'karyawan')->get();
        return view('admin.verifikasi.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'hari' => 'required|string',
            'keterangan' => 'required',
            'detail' => 'nullable|string',
            'tanggal_cuti' => 'nullable|date',
            'selesai_cuti' => 'nullable|date',
            'jumlah_hari_cuti' => 'nullable|integer',
        ]);

        VerifikasiPerizinan::create($request->all());

        return redirect()->route('verifikasi.index')->with('success', 'Data perizinan berhasil ditambahkan.');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Ditolak',
        ]);


        $permission = Permission::findOrFail($id);
        $permission->status = $request->status;
        $permission->save();

        return redirect()->route('verifikasi.permissions')->with('success', 'Status izin berhasil diperbarui.');
        }



    public function permissions()
    {
        $permissions = Permission::with('user')->latest()->get();
        return view('admin.verifikasi.permissions', compact('permissions'));
    }
}
