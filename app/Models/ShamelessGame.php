<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShamelessGame extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'shameless_games';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'category_id',
        'provider_id',
        'g_code',
        'html_type',
        'active',
        'is_hot',
        'cate_code',
        'p_code',
        'is_new',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function category()
    {
        return $this->belongsTo(ShamelessGameCategory::class, 'category_id');
    }

    public function provider()
    {
        return $this->belongsTo(ShamelessGameProvider::class, 'provider_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
