<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startups', function (Blueprint $table) {
            $table->id();

            // ---------- Pengelompokan program ----------
            $table->string('batch', 20)->nullable()->comment('Batch 1 / Batch 2 / dst');
            $table->year('tahun_program')->nullable();
            $table->string('skema_program', 50)->nullable()
                  ->comment('Pra-Akselerasi / Inkubasi / Akselerasi');

            // ---------- Identitas startup & CEO ----------
            $table->string('nama_startup', 200);
            $table->string('nama_ceo', 150);
            $table->date('tanggal_lahir_ceo')->nullable();
            $table->enum('jenis_kelamin_ceo', ['L', 'P']);
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('asal_sekolah', 200)->nullable();
            $table->string('jurusan', 200)->nullable();
            $table->string('semester', 20)->nullable()->comment('jika belum lulus');
            $table->string('tahun_lulus', 10)->nullable()->comment('jika sudah lulus');

            // ---------- Kontak & alamat ----------
            // Alamat asli disimpan utuh; kota/kecamatan/provinsi diisi
            // hasil ekstraksi saat import (untuk filter wilayah).
            $table->text('alamat_rumah')->nullable();
            $table->text('alamat_usaha')->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->nullable()->comment('kota/kabupaten - filter wilayah');
            $table->string('provinsi', 100)->nullable();
            $table->string('no_wa', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('instagram', 150)->nullable();

            // ---------- Usaha & produk ----------
            $table->foreignId('bidang_usaha_id')
                  ->nullable()
                  ->constrained('bidang_usaha')
                  ->nullOnDelete();
            $table->date('mulai_usaha')->nullable();
            $table->string('nama_produk', 255)->nullable();
            $table->text('deskripsi_produk')->nullable();
            $table->text('judul_proposal')->nullable();

            // ---------- Asal invensi ----------
            $table->enum('asal_invensi', ['IPB', 'Mandiri', 'Kombinasi'])->default('Mandiri');
            $table->text('keterangan_invensi')->nullable()->comment('jawaban asli responden');
            $table->string('nama_dosen_pembimbing', 255)->nullable();

            // ---------- Tenaga kerja (BASELINE / BEFORE) ----------
            $table->unsignedInteger('tenaga_kerja_l')->default(0);
            $table->unsignedInteger('tenaga_kerja_p')->default(0);

            // ---------- Modal ----------
            // Numerik untuk hitungan + teks asli sebagai cadangan,
            // karena di Excel ditulis bebas ("RP. 150 JUTA", dst).
            $table->decimal('modal_awal', 18, 2)->nullable();
            $table->string('modal_awal_teks', 255)->nullable()->comment('nilai asli dari Excel');
            $table->string('sumber_modal', 100)->nullable();

            // ---------- Produksi & pasar (BASELINE) ----------
            $table->text('kapasitas_produksi')->nullable();
            $table->text('harga_produk')->nullable();
            $table->string('jangkauan_pasar', 100)->nullable();

            // ---------- Omset BASELINE (titik "BEFORE") ----------
            $table->decimal('omset_awal', 18, 2)->nullable()->default(0);
            $table->string('omset_awal_teks', 255)->nullable()->comment('nilai asli dari Excel');
            $table->string('periode_omset_awal', 50)->nullable()
                  ->comment('mis. "Tahun 2025", "Jan-Mar 2026"');

            // ---------- Narasi ----------
            $table->text('permasalahan_utama')->nullable();
            $table->text('rencana_pengembangan')->nullable();

            $table->enum('status', ['pendaftar', 'aktif', 'lulus', 'nonaktif'])->default('aktif');

            $table->timestamps();

            // ---------- Index pencarian & filter ----------
            $table->index('kota');
            $table->index('batch');
            $table->index('omset_awal');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startups');
    }
};
