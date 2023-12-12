<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Betting3d extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bettings_3d';

    public function user_bets_3d()
    {
        return $this->belongsToMany(UserBet3d::class, 'user_bet_has_betting_3d');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
