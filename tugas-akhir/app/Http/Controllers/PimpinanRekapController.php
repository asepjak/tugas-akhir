<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RekapAbsensiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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

    /**
     * Get detailed attendance data for specific user and month (untuk kompatibilitas dengan route existing)
     */
    public function getDetailIzinSakit(Request $request)
    {
        return $this->detail($request);
    }

    /**
     * Get detailed attendance data for specific user and month
     */
    public function detail(Request $request)
    {
        // Validate input
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'bulan' => 'required|string|size:2',
                'tahun' => 'required|integer'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        }

        try {
            // Check if User model exists
            if (!class_exists('\App\Models\User')) {
                throw new \Exception('User model tidak ditemukan');
            }

            // Get user data
            $user = \App\Models\User::find($request->user_id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Get basic attendance data
            $data = $this->getSimpleAttendanceDetail($request->user_id, $request->bulan, $request->tahun);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama ?? $user->name ?? 'N/A',
                    'name' => $user->name ?? $user->nama ?? 'N/A'
                ],
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'detail_izin' => $data['detail_izin'] ?? [],
                'detail_sakit' => $data['detail_sakit'] ?? [],
                'summary' => $data['summary'] ?? [
                    'total_hadir_efektif' => 0,
                    'total_tidak_masuk' => 0,
                    'total_keseluruhan' => 0
                ]
            ]);

        } catch (\Exception $e) {
            // Log the actual error for debugging
            Log::error('Error in PimpinanRekapController detail method', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data detail',
                'error_detail' => config('app.debug') ? $e->getMessage() : 'Server error'
            ], 500);
        }
    }

    /**
     * Get simple attendance detail data from absensi table only
     */
    private function getSimpleAttendanceDetail($userId, $bulan, $tahun)
    {
        try {
            $startDate = "{$tahun}-{$bulan}-01";
            $endDate = date('Y-m-t', strtotime($startDate));

            // Check if we can access the database
            $absensiRecords = collect();

            // Try to use Absensi model first
            if (class_exists('\App\Models\Absensi')) {
                try {
                    $absensiRecords = \App\Models\Absensi::where('user_id', $userId)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->select('tanggal', 'status', 'keterangan')
                        ->orderBy('tanggal')
                        ->get();
                } catch (\Exception $e) {
                    Log::warning('Absensi model query failed: ' . $e->getMessage());
                }
            }

            // Fallback to DB query if model doesn't work
            if ($absensiRecords->isEmpty()) {
                try {
                    $absensiRecords = DB::table('absensi')
                        ->where('user_id', $userId)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->select('tanggal', 'status', 'keterangan')
                        ->orderBy('tanggal')
                        ->get();
                } catch (\Exception $e) {
                    Log::error('DB query for absensi failed: ' . $e->getMessage());
                    // Return empty data if both methods fail
                    return $this->getEmptyAttendanceData($tahun, $bulan);
                }
            }

            // Process izin records
            $izinRecords = $absensiRecords->where('status', 'izin');
            $detailIzin = $izinRecords->map(function ($item) {
                return [
                    'tanggal_mulai' => $this->formatDate($item->tanggal),
                    'tanggal_selesai' => $this->formatDate($item->tanggal),
                    'alasan' => $item->keterangan ?? 'Izin',
                    'jumlah_hari' => 1
                ];
            })->values();

            // Process sakit records
            $sakitRecords = $absensiRecords->where('status', 'sakit');
            $detailSakit = $sakitRecords->map(function ($item) {
                return [
                    'tanggal_mulai' => $this->formatDate($item->tanggal),
                    'tanggal_selesai' => $this->formatDate($item->tanggal),
                    'alasan' => $item->keterangan ?? 'Sakit',
                    'jumlah_hari' => 1
                ];
            })->values();

            // Calculate summary
            $totalHadir = $absensiRecords->where('status', 'hadir')->count();
            $totalIzin = $izinRecords->count();
            $totalSakit = $sakitRecords->count();
            $totalTerlambat = $absensiRecords->where('status', 'terlambat')->count();
            $totalTidakMasuk = $totalIzin + $totalSakit;

            // Calculate working days in the month (excluding weekends)
            $workingDays = $this->getWorkingDaysInMonth($tahun, $bulan);

            return [
                'detail_izin' => $detailIzin->toArray(),
                'detail_sakit' => $detailSakit->toArray(),
                'summary' => [
                    'total_hadir_efektif' => $totalHadir + $totalTerlambat,
                    'total_tidak_masuk' => $totalTidakMasuk,
                    'total_keseluruhan' => $workingDays
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error in getSimpleAttendanceDetail: ' . $e->getMessage());
            return $this->getEmptyAttendanceData($tahun, $bulan);
        }
    }

    /**
     * Format date safely
     */
    private function formatDate($date)
    {
        try {
            if (class_exists('\Carbon\Carbon')) {
                return \Carbon\Carbon::parse($date)->format('d/m/Y');
            } else {
                return date('d/m/Y', strtotime($date));
            }
        } catch (\Exception $e) {
            return $date; // Return original if formatting fails
        }
    }

    /**
     * Get empty attendance data structure
     */
    private function getEmptyAttendanceData($tahun, $bulan)
    {
        $workingDays = $this->getWorkingDaysInMonth($tahun, $bulan);

        return [
            'detail_izin' => [],
            'detail_sakit' => [],
            'summary' => [
                'total_hadir_efektif' => 0,
                'total_tidak_masuk' => 0,
                'total_keseluruhan' => $workingDays
            ]
        ];
    }

    /**
     * Calculate working days in a month (excluding weekends)
     */
    private function getWorkingDaysInMonth($year, $month)
    {
        try {
            if (class_exists('\Carbon\Carbon')) {
                $startDate = \Carbon\Carbon::createFromDate($year, $month, 1);
                $endDate = $startDate->copy()->endOfMonth();
                $workingDays = 0;

                while ($startDate <= $endDate) {
                    if (!$startDate->isWeekend()) {
                        $workingDays++;
                    }
                    $startDate->addDay();
                }

                return $workingDays;
            } else {
                // Fallback without Carbon
                $startDate = mktime(0, 0, 0, $month, 1, $year);
                $endDate = mktime(0, 0, 0, $month, date('t', $startDate), $year);
                $workingDays = 0;

                for ($i = $startDate; $i <= $endDate; $i += 86400) {
                    $dayOfWeek = date('w', $i);
                    if ($dayOfWeek != 0 && $dayOfWeek != 6) { // Not Sunday (0) or Saturday (6)
                        $workingDays++;
                    }
                }

                return $workingDays;
            }
        } catch (\Exception $e) {
            Log::error('Error calculating working days: ' . $e->getMessage());
            // Return approximate working days (22 days average)
            return 22;
        }
    }
}
