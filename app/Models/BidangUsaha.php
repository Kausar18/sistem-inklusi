<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BidangUsaha extends Model
{
    protected $table = 'bidang_usaha';

    protected $fillable = ['nama_bidang'];

    public function startups(): HasMany
    {
        return $this->hasMany(Startup::class);
    }
}
