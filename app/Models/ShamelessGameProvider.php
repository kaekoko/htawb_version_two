<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShamelessGameProvider extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'shameless_game_providers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'p_code',
        'active',
        'image',
        'sec_image',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function providerGames()
    {
        return $this->hasMany(ShamelessGame::class, 'provider_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function categories()
    {
        return $this->belongsToMany(ShamelessGameCategory::class, 'game_category_game_provider');
    }
}
