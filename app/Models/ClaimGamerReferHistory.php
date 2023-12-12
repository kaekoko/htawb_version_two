<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimGamerReferHistory extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'claim_refer_game_histories';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
