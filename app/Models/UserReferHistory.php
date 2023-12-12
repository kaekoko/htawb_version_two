<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReferHistory extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_referral_histories';

    public function user()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }

    public function main_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ref_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
