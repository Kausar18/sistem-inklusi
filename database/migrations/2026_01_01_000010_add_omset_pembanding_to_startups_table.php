<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data pendaftaran memuat DUA titik omzet:
     *   - Omzet Tahun 2025      -> disimpan di omset_awal (baseline)
     *   - Omzet Jan-Maret 2026  -> kolom baru di bawah ini
     *
     * Keduanya disimpan terpisah karena panjang periodenya berbeda
     * (12 bulan vs 3 bulan), sehingga tidak boleh dijumlah atau
     * dibandingkan sebagai persentase begitu saja.
     */
    public function up(): void
    {
        Schema::table('startups', function (Blueprint $table) {
            $table->decimal('omset_pembanding', 18, 2)->nullable()->after('periode_omset_awal');
            $table->string('omset_pembanding_teks', 255)->nullable()->after('omset_pembanding')
                ->comment('nilai asli dari Excel');
            $table->string('periode_omset_pembanding', 50)->nullable()->after('omset_pembanding_teks')
                ->comment('mis. "Jan-Mar 2026"');
            $table->unsignedTinyInteger('bulan_periode_awal')->nullable()->after('periode_omset_pembanding')
                ->comment('panjang periode baseline dalam bulan');
            $table->unsignedTinyInteger('bulan_periode_pembanding')->nullable()->after('bulan_periode_awal')
                ->comment('panjang periode pembanding dalam bulan');
        });
    }

    public function down(): void
    {
        Schema::table('startups', function (Blueprint $table) {
            $table->dropColumn([
                'omset_pembanding',
                'omset_pembanding_teks',
                'periode_omset_pembanding',
                'bulan_periode_awal',
                'bulan_periode_pembanding',
            ]);
        });
    }
};
