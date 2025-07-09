<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Perizinan extends Model
{
    protected $table = 'perizinan';

    protected $fillable = [
        'user_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'dokumen_pendukung',
        'status',
        'approved_by',
        'approved_at',
        'approval_note'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke pengaju izin (user)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke user yang menyetujui
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Durasi izin (dalam hari)
    public function getDurasiAttribute()
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
        }
        return 1;
    }

    // Accessor status badge
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    // Accessor label jenis
    public function getJenisLabelAttribute()
    {
        return match ($this->jenis) {
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'cuti' => 'Cuti',
            'dinas' => 'Dinas Luar',
            default => ucfirst($this->jenis),
        };
    }

    // Accessor tanggal format lokal
    public function getTanggalMulaiFormatAttribute()
    {
        return $this->tanggal_mulai ? $this->tanggal_mulai->format('d/m/Y') : '-';
    }

    public function getTanggalSelesaiFormatAttribute()
    {
        return $this->tanggal_selesai ? $this->tanggal_selesai->format('d/m/Y') : '-';
    }

    // Scope: Perizinan pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope: Hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    // Scope: Bulan ini
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
    }

    // Scope: Berdasarkan user tertentu
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope: Berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Berdasarkan jenis
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    // Cek apakah izin sedang aktif (hari ini dalam range tanggal_mulai - tanggal_selesai)
    public function isAktif()
    {
        $today = Carbon::today();
        return $this->status === 'approved' &&
               $this->tanggal_mulai && $this->tanggal_selesai &&
               $today->between($this->tanggal_mulai, $this->tanggal_selesai);
    }

    // Statistik Izin per User
    public static function statistikPerUser($userId, $tahun = null)
    {
        $query = self::where('user_id', $userId);

        if ($tahun) {
            $query->whereYear('tanggal_mulai', $tahun);
        }

        return [
            'total' => $query->count(),
            'cuti' => (clone $query)->where('jenis', 'cuti')->count(),
            'izin' => (clone $query)->where('jenis', 'izin')->count(),
            'sakit' => (clone $query)->where('jenis', 'sakit')->count(),
            'dinas' => (clone $query)->where('jenis', 'dinas')->count(),
        ];
    }
}
