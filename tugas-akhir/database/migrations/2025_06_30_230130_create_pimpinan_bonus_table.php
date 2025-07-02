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
        Schema::create('pimpinan_bonus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('bulan')->unsigned(); // 1-12
            $table->year('tahun'); // Format tahun yang lebih sesuai
            $table->decimal('jumlah_bonus', 15, 2); // Lebih besar untuk menampung bonus besar
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index(['bulan', 'tahun']);
            $table->index('user_id');

            // Unique constraint untuk mencegah duplikasi bonus per user per bulan
            $table->unique(['user_id', 'bulan', 'tahun'], 'unique_user_bonus_per_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pimpinan_bonus');
    }
};
