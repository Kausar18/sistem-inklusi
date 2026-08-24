<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('startups')->cascadeOnDelete();

            $table->enum('kategori', [
                'foto_ceo', 'logo_startup', 'foto_produk', 'company_profile',
                'bmc', 'proposal', 'infografis', 'lainnya',
            ]);
            $table->string('judul', 255)->nullable();

            // Menampung link Google Drive maupun path file lokal.
            $table->string('file', 500);

            $table->timestamps();

            $table->index(['startup_id', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};
