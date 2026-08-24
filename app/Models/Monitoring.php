<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monitoring extends Model
{
    protected $table = 'monitoring';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'omzet'   => 'decimal:2',
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }

    public function getTotalTenagaKerjaAttribute(): int
    {
        return (int) $this->tenaga_kerja_l + (int) $this->tenaga_kerja_p;
    }
}
