<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('user_id', Auth::id())->latest()->get();
        return view('karyawan.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('karyawan.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|in:Sakit,Izin,Cuti,Perjalanan Keluar Kota',
            'alasan' => 'nullable',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'file_surat' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'perjalanan_keluar_kota' => 'nullable|string|max:255'
        ]);

        $file = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat')->store('surat_izin', 'public');
        }

        Permission::create([
            'user_id' => Auth::id(),
            'keterangan' => $request->keterangan,
            'alasan' => $request->alasan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'file_surat' => $file,
            'status' => 'Menunggu',
            'nomor_unit' => $request->nomor_unit,
            'muatan' => $request->muatan,
            'merek_muatan' => $request->merek_muatan,
            'perjalanan_keluar_kota' => $request->perjalanan_keluar_kota,
        ]);


        return redirect()->route('karyawan.permissions.index')->with('success', 'Permohonan izin berhasil dikirim.');
    }

    public function destroy($id)
    {
        $izin = Permission::findOrFail($id);
        if ($izin->user_id != Auth::id()) {
            abort(403);
        }

        if ($izin->file_surat) {
            Storage::disk('public')->delete($izin->file_surat);
        }

        $izin->delete();

        return redirect()->route('karyawan.permissions.index')->with('success', 'Data izin berhasil dihapus.');
    }

    public function print($id)
    {
        $izin = Permission::with('user')->findOrFail($id);
        if ($izin->user_id != Auth::id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('karyawan.permissions.template_pdf', compact('izin'));
        return $pdf->stream('surat-izin.pdf');
    }
}
