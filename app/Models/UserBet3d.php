<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBet3d extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_bets_3d';

    public function bettings_3d()
    {
        return $this->belongsToMany(Betting3d::class, 'user_bet_has_betting_3d');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
