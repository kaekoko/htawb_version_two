<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderMinimumAmount extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the provider that owns the ProviderMinimumAmount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(GameProvider::class);
    }
}
