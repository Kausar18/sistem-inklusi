<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Setiap baris = 1 snapshot kinerja. Record TERBARU dipakai
        // sebagai titik "AFTER" pada infografis before-after.
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('startups')->cascadeOnDelete();

            $table->date('tanggal');
            $table->string('periode', 50)->nullable()->comment('mis. "Triwulan 1 2026"');

            $table->decimal('omzet', 18, 2)->nullable();
            $table->unsignedInteger('tenaga_kerja_l')->nullable();
            $table->unsignedInteger('tenaga_kerja_p')->nullable();
            $table->unsignedInteger('jumlah_mitra')->nullable();
            $table->string('wilayah_penjualan', 255)->nullable();
            $table->string('izin_edar', 255)->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['startup_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring');
    }
};
