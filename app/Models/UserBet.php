<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBet extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_bets';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function bettings()
    {
        return $this->belongsToMany(Betting::class, 'user_bet_has_betting');
    }

}
