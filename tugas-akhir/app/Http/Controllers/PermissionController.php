<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('user_id', Auth::user()->id)->latest()->get();
        return view('karyawan.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('karyawan.permissions.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|in:Sakit,Izin,Cuti',
            'alasan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'file_surat' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
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
            'status' => 'Menunggu', // <- jangan lupa default status
        ]);

        return redirect()->route('dashboard')->with('success', 'Permohonan izin berhasil dikirim.');
    }
}
