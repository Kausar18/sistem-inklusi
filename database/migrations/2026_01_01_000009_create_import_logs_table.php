<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Riwayat import Excel - untuk menelusuri jika ada data
        // yang keliru setelah proses import.
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();

            $table->string('nama_file', 255);
            $table->string('batch', 20)->nullable();
            $table->unsignedInteger('jumlah_berhasil')->default(0);
            $table->unsignedInteger('jumlah_gagal')->default(0);
            $table->text('catatan')->nullable()->comment('ringkasan error jika ada');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
