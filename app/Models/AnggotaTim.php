<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaTim extends Model
{
    protected $table = 'anggota_tim';

    protected $guarded = ['id'];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }
}
