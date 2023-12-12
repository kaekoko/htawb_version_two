<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MasterAgent extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guard = 'master_agent';

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

    public function credit_histories()
    {
        return $this->hasMany(CreditHistory::class, 'master_agent_id');
    }

    public function credit_out_histories()
    {
        return $this->hasMany(CreditOutHistory::class, 'master_agent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'master_agent_id');
    }

    public function commission_histories()
    {
        return $this->hasMany(CommissionHistory::class, 'master_agent_id');
    }
}
