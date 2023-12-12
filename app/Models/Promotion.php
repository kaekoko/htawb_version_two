<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $table ='promotions';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
