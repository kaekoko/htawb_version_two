<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdminNoti extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'super_admin_notis';

    public function notifications()
    {
        return $this->belongsTo(Notification::class,'noti_id');
    }
}
