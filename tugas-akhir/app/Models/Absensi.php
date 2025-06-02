<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam',
        'jam_keluar',
        'ip_address',
        'status',
        'jam_terlambat',
        'durasi_terlambat',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam' => 'string',
        'jam_keluar' => 'string',
        'jam_terlambat' => 'string',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk format tanggal Indonesia
    public function getTanggalFormatAttribute()
    {
        return Carbon::parse($this->tanggal)->format('d/m/Y');
    }

    // Accessor untuk format jam masuk
    public function getJamFormatAttribute()
    {
        return Carbon::parse($this->jam)->format('H:i:s');
    }

    // Accessor untuk format jam keluar
    public function getJamKeluarFormatAttribute()
    {
        return $this->jam_keluar ? Carbon::parse($this->jam_keluar)->format('H:i:s') : null;
    }

    // Method untuk cek apakah terlambat
    public function isTerlambat($jamBatas = '08:30:00')
    {
        $jamMasuk = Carbon::createFromFormat('H:i:s', $this->jam);
        $batas = Carbon::createFromFormat('H:i:s', $jamBatas);

        return $jamMasuk->gt($batas);
    }

    // Method untuk hitung durasi keterlambatan
    public function hitungDurasiTerlambat($jamBatas = '08:30:00')
    {
        if (!$this->isTerlambat($jamBatas)) {
            return 'Tepat waktu';
        }

        $jamMasuk = Carbon::createFromFormat('H:i:s', $this->jam);
        $batas = Carbon::createFromFormat('H:i:s', $jamBatas);

        $selisihMenit = $jamMasuk->diffInMinutes($batas);
        $jam = intdiv($selisihMenit, 60);
        $menit = $selisihMenit % 60;

        return $jam > 0 ? "{$jam} jam {$menit} menit" : "{$menit} menit";
    }

    // Method untuk hitung total jam kerja
    public function hitungJamKerja()
    {
        if (!$this->jam_keluar) {
            return null;
        }

        $masuk = Carbon::createFromFormat('H:i:s', $this->jam);
        $keluar = Carbon::createFromFormat('H:i:s', $this->jam_keluar);

        $totalMenit = $keluar->diffInMinutes($masuk);
        $jam = intdiv($totalMenit, 60);
        $menit = $totalMenit % 60;

        return "{$jam} jam {$menit} menit";
    }

    // Scope untuk data hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }

    // Scope untuk data bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year);
    }

    // Scope untuk data user tertentu
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk statistik keterlambatan
    public static function statistikKeterlambatan($userId, $bulan = null, $tahun = null)
    {
        $query = self::where('user_id', $userId);

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
        } elseif ($tahun) {
            $query->whereYear('tanggal', $tahun);
        } else {
            // Default bulan ini
            $query->whereMonth('tanggal', Carbon::now()->month)
                  ->whereYear('tanggal', Carbon::now()->year);
        }

        $total = $query->count();
        $terlambat = $query->where('status', 'terlambat')->count();
        $tepat_waktu = $total - $terlambat;

        return [
            'total_hari' => $total,
            'terlambat' => $terlambat,
            'tepat_waktu' => $tepat_waktu,
            'persentase_terlambat' => $total > 0 ? round(($terlambat / $total) * 100, 2) : 0,
            'persentase_tepat_waktu' => $total > 0 ? round(($tepat_waktu / $total) * 100, 2) : 0,
        ];
    }
}
