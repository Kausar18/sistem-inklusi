<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu startup bisa punya BEBERAPA legalitas sekaligus,
        // contoh nyata di data asli: "PT, NIB".
        Schema::create('legalitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('startups')->cascadeOnDelete();

            $table->enum('tipe', ['usaha', 'produk']);
            $table->string('nama', 150)->comment('PT / CV / NIB / BPOM / Halal / PIRT / SVLK');
            $table->string('file', 500)->nullable()->comment('link atau path berkas');

            $table->timestamps();

            $table->index(['startup_id', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legalitas');
    }
};
