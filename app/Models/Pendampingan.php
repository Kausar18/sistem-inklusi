<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendampingan extends Model
{
    protected $table = 'pendampingan';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }
}
