<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    use HasFactory;

    
    protected $table = 'rekap_absensi';

    protected $fillable = ['user_id', 'tanggal', 'hari', 'keterangan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

