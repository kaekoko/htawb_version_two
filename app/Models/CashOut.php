<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOut extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cash_outs';

    protected $with= [
        "payment_method"
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_id');
    }

    public function super_admin()
    {
        return $this->belongsTo(SuperAdmin::class,'super_admin_id');
    }

    public function senior_agent()
    {
        return $this->belongsTo(SeniorAgent::class,'senior_agent_id');
    }

    public function master_agent()
    {
        return $this->belongsTo(MasterAgent::class,'master_agent_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class,'agent_id');
    }

}
