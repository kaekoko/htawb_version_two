<?php

namespace App\Models;

use App\Helper\helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferOut extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transfer_outs';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getErrorCodeAttribute($value){
        return $value . " : " . helper::game_errorcode()[$value];
    }
}
