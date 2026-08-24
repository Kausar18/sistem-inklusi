<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Startup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_lahir_ceo' => 'date',
        'mulai_usaha'       => 'date',
        'modal_awal'        => 'decimal:2',
        'omset_awal'        => 'decimal:2',
    ];

    // ==================================================================
    // RELASI
    // ==================================================================

    public function bidangUsaha(): BelongsTo
    {
        return $this->belongsTo(BidangUsaha::class);
    }

    public function legalitas(): HasMany
    {
        return $this->hasMany(Legalitas::class);
    }

    public function anggotaTim(): HasMany
    {
        return $this->hasMany(AnggotaTim::class);
    }

    public function dokumentasi(): HasMany
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function pendampingan(): HasMany
    {
        return $this->hasMany(Pendampingan::class);
    }

    public function monitoring(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }

    public function targetOutput(): HasMany
    {
        return $this->hasMany(TargetOutput::class);
    }

    /**
     * Record monitoring TERBARU = titik "AFTER" untuk infografis.
     */
    public function monitoringTerbaru(): HasOne
    {
        return $this->hasOne(Monitoring::class)->latestOfMany('tanggal');
    }

    // ==================================================================
    // ACCESSOR
    // ==================================================================

    public function getTotalTenagaKerjaAttribute(): int
    {
        return (int) $this->tenaga_kerja_l + (int) $this->tenaga_kerja_p;
    }

    /**
     * Persentase kenaikan omset (before -> after).
     * Mengembalikan null jika data belum lengkap.
     */
    public function getPersenKenaikanOmsetAttribute(): ?float
    {
        $before = (float) $this->omset_awal;
        $after  = (float) optional($this->monitoringTerbaru)->omzet;

        if ($before <= 0 || $after <= 0) {
            return null;
        }

        return round((($after - $before) / $before) * 100, 2);
    }

    // ==================================================================
    // SCOPE PENCARIAN & FILTER
    // ==================================================================

    /** Pencarian bebas: nama startup, nama CEO, atau nama produk. */
    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('nama_startup', 'like', "%{$keyword}%")
              ->orWhere('nama_ceo', 'like', "%{$keyword}%")
              ->orWhere('nama_produk', 'like', "%{$keyword}%");
        });
    }

    /** Filter wilayah (kota/kabupaten). */
    public function scopeWilayah(Builder $query, ?string $kota): Builder
    {
        return blank($kota) ? $query : $query->where('kota', 'like', "%{$kota}%");
    }

    /** Filter bidang usaha. */
    public function scopeBidang(Builder $query, $bidangUsahaId): Builder
    {
        return blank($bidangUsahaId) ? $query : $query->where('bidang_usaha_id', $bidangUsahaId);
    }

    /** Filter batch program. */
    public function scopeBatch(Builder $query, ?string $batch): Builder
    {
        return blank($batch) ? $query : $query->where('batch', $batch);
    }

    /** Filter rentang omset baseline. */
    public function scopeRentangOmset(Builder $query, $min = null, $max = null): Builder
    {
        if (filled($min)) {
            $query->where('omset_awal', '>=', $min);
        }

        if (filled($max)) {
            $query->where('omset_awal', '<=', $max);
        }

        return $query;
    }

    /** Filter asal invensi (IPB / Mandiri / Kombinasi). */
    public function scopeAsalInvensi(Builder $query, ?string $asal): Builder
    {
        return blank($asal) ? $query : $query->where('asal_invensi', $asal);
    }

    /** Filter jenis kelamin CEO. */
    public function scopeGenderCeo(Builder $query, ?string $gender): Builder
    {
        return blank($gender) ? $query : $query->where('jenis_kelamin_ceo', $gender);
    }
}
