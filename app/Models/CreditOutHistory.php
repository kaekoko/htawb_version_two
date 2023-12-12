<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditOutHistory extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'credit_out_histories';

    public function super_admin()
    {
        return $this->belongsTo(SuperAdmin::class);
    }

    public function senior_agent()
    {
        return $this->belongsTo(SeniorAgent::class);
    }

    public function master_agent()
    {
        return $this->belongsTo(MasterAgent::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
