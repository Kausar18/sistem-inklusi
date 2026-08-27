<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Legalitas extends Model
{
    protected $table = 'legalitas';

    protected $guarded = ['id'];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }

    /** Cek apakah nilai kolom file berupa URL eksternal (mis. Google Drive). */
    public function getIsExternalAttribute(): bool
    {
        return str_starts_with((string) $this->file, 'http://')
            || str_starts_with((string) $this->file, 'https://');
    }

    /**
     * Link ke berkas asli. Link eksternal dipakai apa adanya; path lokal
     * (hasil upload lewat panel admin) diberi awalan asset('storage/...').
     */
    public function getUrlAsliAttribute(): ?string
    {
        if (blank($this->file)) {
            return null;
        }

        return $this->is_external ? $this->file : asset('storage/'.$this->file);
    }
}
