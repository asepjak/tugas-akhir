<?php

use Illuminate\Support\Facades\Auth; // ✅ tambahkan ini
use App\Models\ActivityLog;

if (!function_exists('log_activity')) {
    function log_activity($type, $description)
    {
        ActivityLog::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'type' => $type,
            'description' => $description,
        ]);
    }
}
