<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiPerizinan extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_perizinan';

    protected $fillable = [
        'user_id',
        'tanggal',
        'hari',
        'keterangan',
        'detail',
        'tanggal_cuti',
        'selesai_cuti',
        'jumlah_hari_cuti',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
