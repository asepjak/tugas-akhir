<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionApprovalController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('user')
            ->whereIn('keterangan', ['Cuti', 'Perjalanan Keluar Kota'])
            ->where('status', 'Menunggu')
            ->latest()
            ->get();

        return view('pimpinan.permissions.index', compact('permissions'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $permission = Permission::findOrFail($id);

        if (!in_array($permission->keterangan, ['Cuti', 'Perjalanan Keluar Kota'])) {
            abort(403, 'Pimpinan hanya dapat memverifikasi pengajuan cuti dan perjalanan dinas.');
        }

        $permission->status = $request->status;
        $permission->save();

        return redirect()->route('pimpinan.permissions.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
    public function riwayat()
    {
        $permissions = Permission::with('user')
            ->whereIn('keterangan', ['Cuti', 'Perjalanan Keluar Kota'])
            ->whereIn('status', ['Disetujui', 'Ditolak'])
            ->latest()
            ->get();

        return view('pimpinan.permissions.riwayat', compact('permissions'));
    }
}
