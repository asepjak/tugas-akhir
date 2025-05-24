<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'keterangan', 'alasan', 'tanggal_mulai', 'tanggal_selesai', 'file_surat', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
