<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guard = 'super_admin';

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
        return $this->hasMany(CreditHistory::class, 'super_admin_id');
    }

    public function credit_out_histories()
    {
        return $this->hasMany(CreditOutHistory::class, 'super_admin_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'super_admin_id');
    }

    public function admin_role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function activity_logs()
    {
        return $this->hasMany(ActivityLog::class, 'super_admin_id');
    }

}
