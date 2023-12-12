<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OneD extends Model
{
    use HasFactory;

    protected $table = 'one_ds';
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
