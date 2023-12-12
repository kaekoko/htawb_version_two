<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    public function super_admin_notis()
    {
        return $this->hasMany(SuperAdminNoti::class, 'noti_id');
    }

    public function senior_agent_notis()
    {
        return $this->hasMany(SeniorAgentNoti::class, 'noti_id');
    }
}
