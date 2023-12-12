<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBet1d extends Model
{
    use HasFactory;

     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_bets_1d';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function bettings()
    {
        return $this->belongsToMany(Betting1d::class, 'user_bet_has_betting_1d');
    }
}
