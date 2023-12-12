<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the games for the GameProvider
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function games()
    {
        return $this->hasMany(Game::class, 'provider_id');
    }

    public function game(){
        return $this->hasOne(Game::class, 'provider_id');
    }

    /**
     * Get all of the provider_minimum_amounts for the GameProvider
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function provider_minimum_amounts()
    {
        return $this->hasMany(GameProvider::class);
    }
}
