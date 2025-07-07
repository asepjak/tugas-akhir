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
            ->where('keterangan', 'Cuti')
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

        // Pastikan hanya izin "Cuti" yang bisa diubah oleh pimpinan
        if ($permission->keterangan !== 'Cuti') {
            abort(403, 'Pimpinan hanya dapat memverifikasi pengajuan cuti.');
        }

        $permission->status = $request->status;
        $permission->save();

        return redirect()->route('pimpinan.permissions.index')->with('success', 'Status cuti berhasil diperbarui.');
    }
}
