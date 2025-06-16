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
        Schema::create('verifikasi_perizinan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // relasi ke users
            $table->date('tanggal');
            $table->string('hari');
            $table->enum('keterangan', ['hadir', 'sakit', 'izin', 'cuti', 'perjalanan keluar kota']);
            $table->text('detail')->nullable(); // keterangan tambahan

            // Khusus jika jenis keterangan = cuti
            $table->date('tanggal_cuti')->nullable();
            $table->date('selesai_cuti')->nullable();
            $table->integer('jumlah_hari_cuti')->nullable();

            // Status verifikasi
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_perizinan');
    }
};
