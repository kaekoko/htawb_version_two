<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinWheelLuckyHistory extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'spin_wheel_lucky_histories';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
