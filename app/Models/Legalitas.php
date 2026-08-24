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
}
