<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
