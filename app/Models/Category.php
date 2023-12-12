<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    public function over_all_amount_limits()
    {
        return $this->hasMany(OverAllAmountLimit::class, 'category_id');
    }

    public function two_ds()
    {
        return $this->hasMany(TwoD::class, 'category_id');
    }
    public function one_ds()
    {
        return $this->hasMany(OneD::class, 'category_id');
    }

    public function three_ds()
    {
        return $this->hasMany(ThreeD::class, 'category_id');
    }

    public function lottery_off_days()
    {
        return $this->hasMany(LotteryOffDay::class, 'category_id');
    }

    public function lucky_numbers()
    {
        return $this->hasMany(LuckyNumber::class, 'category_id');
    }

    public function bettings()
    {
        return $this->hasMany(Betting::class, 'category_id');
    }

    public function bettings_3d()
    {
        return $this->hasMany(Betting3d::class, 'category_id');
    }
}
