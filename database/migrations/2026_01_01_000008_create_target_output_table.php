<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('target_output', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('startups')->cascadeOnDelete();

            $table->string('nama_target', 255);
            $table->enum('status', ['belum_tercapai', 'proses', 'tercapai'])
                  ->default('belum_tercapai');
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->index('startup_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_output');
    }
};
