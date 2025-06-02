<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam'); // Jam masuk
            $table->time('jam_keluar')->nullable(); // Jam keluar
            $table->string('ip_address', 45); // Support IPv6
            $table->enum('status', ['hadir', 'terlambat', 'alpha', 'izin', 'sakit'])->default('hadir');
            $table->time('jam_terlambat')->nullable(); // Jam ketika terlambat
            $table->string('durasi_terlambat')->nullable(); // Durasi keterlambatan (string)
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->timestamps();

            // Indexes untuk performa
            $table->index(['user_id', 'tanggal']);
            $table->index(['tanggal']);
            $table->index(['status']);

            // Unique constraint untuk mencegah double absensi per hari
            $table->unique(['user_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
