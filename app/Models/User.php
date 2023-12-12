<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function cash_ins()
    {
        return $this->hasMany(CashIn::class);
    }

    public function cash_outs()
    {
        return $this->hasMany(CashOut::class);
    }

    public function user_bets() // thai 2d
    {
        return $this->hasMany(UserBet::class);
    }

    public function user_bets_3d() // thai 3d
    {
        return $this->hasMany(UserBet3d::class);
    }

    public function user_bets_c2d() // crypto 2d
    {
        return $this->hasMany(UserBetCrypto2d::class);
    }

}