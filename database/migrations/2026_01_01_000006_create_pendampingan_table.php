<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendampingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('startups')->cascadeOnDelete();

            $table->enum('jenis', [
                'training', 'mentoring', 'business_matching', 'form_pendaftaran', 'lainnya',
            ]);
            $table->date('tanggal')->nullable();
            $table->string('pendamping', 150)->nullable()->comment('nama tim pendamping');
            $table->string('lokasi', 255)->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['startup_id', 'jenis']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendampingan');
    }
};
