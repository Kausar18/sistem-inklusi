<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TargetOutput extends Model
{
    protected $table = 'target_output';

    protected $guarded = ['id'];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }
}
