<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShamelessGameCategory extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'shameless_game_categories';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'code',
        'active',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function categoryGames()
    {
        return $this->hasMany(ShamelessGame::class, 'category_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function categoryGameProviders()
    {
        return $this->belongsToMany(ShamelessGameProvider::class, 'game_category_game_provider');
    }
}
