<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RekapAbsensiController;

class PimpinanRekapController extends RekapAbsensiController
{
    /**
     * Display monthly report with different view for pimpinan
     */
    
    public function bulanan(Request $request)
    {
        $parentResponse = parent::bulanan($request);
        return view('pimpinan.rekap.bulanan', $parentResponse->getData());
    }

    /**
     * Print report with different view for pimpinan
     */
    public function print(Request $request)
    {
        $parentResponse = parent::print($request);
        return view('pimpinan.rekap.print', $parentResponse->getData());
    }
}
