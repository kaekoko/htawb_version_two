<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guard = 'agent';

    protected $fillable = [
        'name','phone', 'password',
    ];

    protected $hidden = [
      'password', 'remember_token',
    ];

    public function cash_ins()
    {
        return $this->hasMany(CashIn::class);
    }

    public function cash_outs()
    {
        return $this->hasMany(CashOut::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function credit_histories()
    {
        return $this->hasMany(CreditHistory::class, 'agent_id');
    }

    public function credit_out_histories()
    {
        return $this->hasMany(CreditOutHistory::class, 'agent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'master_agent_id');
    }

    public function commission_histories()
    {
        return $this->hasMany(CommissionHistory::class, 'agent_id');
    }
}
