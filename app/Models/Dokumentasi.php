<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';

    protected $guarded = ['id'];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }

    /** Cek apakah nilai kolom file berupa URL eksternal (mis. Google Drive). */
    public function getIsExternalAttribute(): bool
    {
        return str_starts_with($this->file, 'http://')
            || str_starts_with($this->file, 'https://');
    }

    /**
     * URL gambar dengan lebar tertentu.
     *
     * Link Google Drive hasil form ("/open?id=XXX" atau "/file/d/XXX/view")
     * tidak bisa dipakai langsung pada <img>, jadi diubah ke bentuk
     * thumbnail. Hanya berlaku untuk file yang izinnya publik.
     */
    public function gambar(int $lebar = 400): ?string
    {
        if (! $this->is_external) {
            return $this->file ? asset('storage/'.$this->file) : null;
        }

        if (preg_match('#[?&]id=([\w-]+)#', $this->file, $cocok)
            || preg_match('#/file/d/([\w-]+)#', $this->file, $cocok)) {
            return 'https://drive.google.com/thumbnail?id='.$cocok[1].'&sz=w'.$lebar;
        }

        return $this->file;
    }

    /** Alias supaya bisa dipanggil sebagai properti: $dokumentasi->url_gambar */
    public function getUrlGambarAttribute(): ?string
    {
        return $this->gambar(400);
    }

    /**
     * Link ke berkas ASLI (bukan thumbnail) — dipakai untuk "lihat ukuran
     * penuh" atau unduh. Link eksternal dipakai apa adanya; path lokal
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
