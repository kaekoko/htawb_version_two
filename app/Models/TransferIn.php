<?php

namespace App\Models;

use App\Helper\helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferIn extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transfer_ins';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getErrorCodeAttribute($value){
        return $value . " : " . helper::game_errorcode()[$value];
    }
}
