<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BettingCrypto1d extends Model
{
    use HasFactory;

    

    protected $table = 'crypto1d_bettings';

    //bet all data
    // protected $guarded = [];

    public function user_bets()
    {
        return $this->belongsToMany(UserBetCrypto1d::class, 'user_bet_has_betting_crypto1d');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
