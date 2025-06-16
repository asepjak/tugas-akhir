<?php

namespace App\Http\Controllers;

use App\Models\RekapAbsensi;
use App\Models\User;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    public function index()
    {
        $data = RekapAbsensi::with('user')->latest()->get();
        return view('admin.rekap.index', compact('data'));
    }

    public function create()
    {
        $users = User::where('role', 'karyawan')->get();
        return view('admin.rekap.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'hari' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        RekapAbsensi::create($request->all());

        return redirect()->route('rekap.index')->with('success', 'Data rekap absensi berhasil ditambahkan.');
    }
}
