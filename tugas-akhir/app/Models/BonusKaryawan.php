<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusKaryawan extends Model
{
    use HasFactory;

    protected $table = 'pimpinan_bonus';

    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'jumlah_bonus',
        'keterangan'
    ];

    protected $casts = [
        'jumlah_bonus' => 'decimal:2',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope filter bulan & tahun
    public function scopeByMonth($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    // Accessor format rupiah
    public function getFormattedBonusAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bonus, 0, ',', '.');
    }

    // Accessor nama bulan
    public function getBulanNamaAttribute()
    {
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulanList[$this->bulan] ?? '';
    }
}
