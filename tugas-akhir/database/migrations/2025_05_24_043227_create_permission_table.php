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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('keterangan'); // Sakit, Izin, Cuti
            $table->text('alasan') -> nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('file_surat')->nullable(); // file path
            $table->string('perjalanan_keluar_kota')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('muatan')->nullable();
            $table->string('merek_muatan')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission');
    }
};
