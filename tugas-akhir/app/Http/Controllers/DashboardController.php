<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboard.admin');
    }

    public function pimpinan()
    {
        return view('dashboard.pimpinan');
    }

    public function karyawan()
    {
        return view('dashboard.karyawan');
    }
}
