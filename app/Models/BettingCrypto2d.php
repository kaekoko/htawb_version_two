<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BettingCrypto2d extends Model
{
    use HasFactory;

    protected $table = 'bettings_crypto_2d';

    public function user_bets_c2d()
    {
        return $this->belongsToMany(UserBetCrypto2d::class, 'user_bet_has_betting_c2d');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
