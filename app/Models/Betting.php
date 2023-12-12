<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Betting extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bettings';

    //bet all data
    // protected $guarded = [];

    public function user_bets()
    {
        return $this->belongsToMany(UserBet::class, 'user_bet_has_betting');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
