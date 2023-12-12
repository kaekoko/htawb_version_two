<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBetCrypto2d extends Model
{
    use HasFactory;

    protected $table = 'user_bets_crypto_2d';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bettings_c2d()
    {
        return $this->belongsToMany(BettingCrypto2d::class, 'user_bet_has_betting_c2d');
    }
}
